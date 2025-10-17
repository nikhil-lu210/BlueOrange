git checkout develop
git pull origin develop
git checkout -b release-v2.5.10
git push origin release-v2.5.10
git add .
git commit -m "The Checkin method from Offline app Updated in release v2.5.10"
git push origin release-v2.5.10
git checkout master
git pull origin master
git merge --no-ff release-v2.5.10 -m "Merge release v2.5.10 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.5.10 -m "Merge release v2.5.10 into develop"
git push origin develop
git branch -d release-v2.5.10
git push origin --delete release-v2.5.10
