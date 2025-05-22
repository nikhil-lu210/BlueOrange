git checkout develop
git pull origin develop
git checkout -b release-v1.6.6
git push origin release-v1.6.6
git add .
git commit -m "Blood Group Issue Solved in release v1.6.6"
git push origin release-v1.6.6
git checkout master
git pull origin master
git merge --no-ff release-v1.6.6 -m "Merge release v1.6.6 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.6.6 -m "Merge release v1.6.6 into develop"
git push origin develop
git branch -d release-v1.6.6
git push origin --delete release-v1.6.6
