#!/bin/sh

myDir=$(
  cd $(dirname "$0")
  pwd
)
appRootDir=$(dirname "${myDir}")
imRoot="${appRootDir}/vendor/inter-mediator/inter-mediator"
editorDir="${imRoot}/editors"

cd "${editorDir}"
sed -e "s|//IM_Entry|IM_Entry|" "defedit.php" > "tempfile"
rm "defedit.php"
mv "tempfile" "defedit.php"
sed -e "s|//IM_Entry|IM_Entry|" "pageedit.php" > "tempfile"
rm "pageedit.php"
mv "tempfile" "pageedit.php"