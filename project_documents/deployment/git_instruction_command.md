git checkout develop
git pull origin develop
git checkout -b release-v2.3.3
git push origin release-v2.3.3
git add .
git commit -m "Employee Recognition UI Updated in release v2.3.3"
git push origin release-v2.3.3
git checkout master
git pull origin master
git merge --no-ff release-v2.3.3 -m "Merge release v2.3.3 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.3.3 -m "Merge release v2.3.3 into develop"
git push origin develop
git branch -d release-v2.3.3
git push origin --delete release-v2.3.3
