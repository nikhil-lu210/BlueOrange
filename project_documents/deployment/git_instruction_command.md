git checkout develop
git pull origin develop
git checkout -b release-v2.0.2.1
git push origin release-v2.0.2.1
git add .
git commit -m "Upcoming Birthdays List in release v2.0.2.1"
git push origin release-v2.0.2.1
git checkout master
git pull origin master
git merge --no-ff release-v2.0.2.1 -m "Merge release v2.0.2.1 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.0.2.1 -m "Merge release v2.0.2.1 into develop"
git push origin develop
git branch -d release-v2.0.2.1
git push origin --delete release-v2.0.2.1
