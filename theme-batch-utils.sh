#!/bin/zsh

git remote update > /dev/null
current_branch=$(git branch --show-current)
hash_at_divergence=$(git merge-base origin/trunk ${current_branch})

# version bump (patch) any theme that has any *comitted* changes since it was branched from /trunk or any *uncomitted* changes
version-bump() {
	# Only version bump things that haven't already had their version changed
	if [[ $1 = 'ROOT' ]]; then
		package_string=$(git show ${hash_at_divergence}:package.json)
	else
		package_string=$(git show ${hash_at_divergence}:$1package.json)
	fi
 	current_version=$(node -p "require('./package.json').version")
	previous_version=$(node -pe "JSON.parse(process.argv[1]).version" "${package_string}")
	if [[ $current_version != $previous_version ]]; then
		return
	fi

	# Only version bump things that have changes
	uncomitted_changes=$(git diff-index --name-only HEAD -- .)
	comitted_changes=$(git diff --name-only ${hash_at_divergence} HEAD -- .)
	if [ -z "$comitted_changes" ] && [ -z "$uncomitted_changes" ]; then
		return
	fi

	echo "Version bumping $1"
	npm version patch --no-git-tag-version
	if [[ $1 != 'ROOT' ]]; then
		apply-version $1
	fi
	echo
}

# copy the version from package.json (the source of truth) to other standard locations (including style.css, style.scss and style-child-theme.scss).
apply-version() {

 	current_version=$(node -p "require('./package.json').version")
	files_to_update=( $(find . -name style.css -o -name style.scss -o -name style-child-theme.scss) )

	for file_to_update in "${files_to_update[@]}"; do
		if test -f "$file_to_update"; then
			echo "Apply version from package.json to $file_to_update"
	 		perl -pi -e 's/Version: (.*)$/"Version: '$current_version'"/ge' $file_to_update 
		fi
	done

}

command=$1
echo

# Do things for all of the themes
for theme in */ ; do
	if test -f "./${theme}/package.json"; then
		cd './'${theme}
		case $command in
			"install-dependencies")
				echo 'Installing Dependencies for '${theme}
				npm install
				echo
				;;
			"audit-dependencies")
				echo 'Auditing and fixing dependencies for '${theme}
				npm audit fix
				echo
				;;
			"build")
				echo 'Building '${theme}
				npm run build
				echo
				;;
			"version-bump")
				version-bump ${theme}
				;;
			"apply-version")
				echo 'Applying version from package.json throughout '${theme}
				apply-version ${theme}
				echo
				;;
		esac	
		cd ..
	else
		# echo 'Skipping '${theme}
	fi
done

# Do things for the root folder
case $command in
	"audit-dependencies")
		echo 'Auditing and fixing dependencies for root project'
		npm audit fix
		;;
	"version-bump")
		version-bump "ROOT"
		;;
esac