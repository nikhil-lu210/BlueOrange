git checkout develop
git pull origin develop
git checkout -b release-v1.6.4
git push origin release-v1.6.4
git add .
git commit -m "Blood Group Update Request For individual Users done and code re-factored in release v1.6.4"
git push origin release-v1.6.4
git checkout master
git pull origin master
git merge --no-ff release-v1.6.4 -m "Merge release v1.6.4 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.6.4 -m "Merge release v1.6.4 into develop"
git push origin develop
git branch -d release-v1.6.4
git push origin --delete release-v1.6.4
