git checkout develop
git pull origin develop
git checkout -b release-v2.2.2
git push origin release-v2.2.2
git add .
git commit -m "Import and Export Implemented on Inventory in release v2.2.2"
git push origin release-v2.2.2
git checkout master
git pull origin master
git merge --no-ff release-v2.2.2 -m "Merge release v2.2.2 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.2.2 -m "Merge release v2.2.2 into develop"
git push origin develop
git branch -d release-v2.2.2
git push origin --delete release-v2.2.2
