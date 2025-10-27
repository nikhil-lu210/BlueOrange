git checkout develop
git pull origin develop
git checkout -b release-v2.6.0
git push origin release-v2.6.0
git add .
git commit -m "Impement drag and drop file at Task comment Updated in release v2.6.0"
git push origin release-v2.6.0
git checkout master
git pull origin master
git merge --no-ff release-v2.6.0 -m "Merge release v2.6.0 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.6.0 -m "Merge release v2.6.0 into develop"
git push origin develop
git branch -d release-v2.6.0
git push origin --delete release-v2.6.0
