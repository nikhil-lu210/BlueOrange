git checkout develop
git pull origin develop
git checkout -b release-v1.5.0
git push origin release-v1.5.0
git add .
git commit -m "Chattings Media, Reply Features Done and Email+Notification done for User's Shift Update and Team Leader Update in release v1.5.0"
git push origin release-v1.5.0
git checkout master
git pull origin master
git merge --no-ff release-v1.5.0 -m "Merge release v1.5.0 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.5.0 -m "Merge release v1.5.0 into develop"
git push origin develop
git branch -d release-v1.5.0
git push origin --delete release-v1.5.0
