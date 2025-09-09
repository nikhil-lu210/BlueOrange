git checkout develop
git pull origin develop
git checkout -b release-v2.3.6
git push origin release-v2.3.6
git add .
git commit -m "504 Gateway Timeout errors resolved in release v2.3.6"
git push origin release-v2.3.6
git checkout master
git pull origin master
git merge --no-ff release-v2.3.6 -m "Merge release v2.3.6 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.3.6 -m "Merge release v2.3.6 into develop"
git push origin develop
git branch -d release-v2.3.6
git push origin --delete release-v2.3.6
