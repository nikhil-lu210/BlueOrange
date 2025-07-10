git checkout develop
git pull origin develop
git checkout -b release-v1.9.5
git push origin release-v1.9.5
git add .
git commit -m "Employee Note and File View in browser in release v1.9.5"
git push origin release-v1.9.5
git checkout master
git pull origin master
git merge --no-ff release-v1.9.5 -m "Merge release v1.9.5 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.9.5 -m "Merge release v1.9.5 into develop"
git push origin develop
git branch -d release-v1.9.5
git push origin --delete release-v1.9.5
