git checkout develop
git pull origin develop
git checkout -b release-v2.3.4
git push origin release-v2.3.4
git add .
git commit -m "Dashboard Clockout Form Bug fixed in release v2.3.4"
git push origin release-v2.3.4
git checkout master
git pull origin master
git merge --no-ff release-v2.3.4 -m "Merge release v2.3.4 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.3.4 -m "Merge release v2.3.4 into develop"
git push origin develop
git branch -d release-v2.3.4
git push origin --delete release-v2.3.4
