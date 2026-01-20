#!/bin/sh

myDir=$(
  cd $(dirname "$0")
  pwd
)
appRootDir=$(dirname "${myDir}")
imRoot="${appRootDir}/vendor/inter-mediator/inter-mediator"
editorDir="${imRoot}/editors"

# Activate page/definition file editors.
cd "${editorDir}"
sed -e "s|//IM_Entry|IM_Entry|" "defedit.php" > "tempfile"
rm "defedit.php"
mv "tempfile" "defedit.php"
sed -e "s|//IM_Entry|IM_Entry|" "pageedit.php" > "tempfile"
rm "pageedit.php"
mv "tempfile" "pageedit.php"

# Pass to the INTER-Mediator for Samples
cd "${appRootDir}"
rm vendor/inter-mediator
if [ ! -e "vendor" ]; then
  ln -s ../ vendor
fi
