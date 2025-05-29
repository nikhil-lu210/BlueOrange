git checkout develop
git pull origin develop
git checkout -b release-v1.7.3
git push origin release-v1.7.3
git add .
git commit -m "Alias Name Set over Real Name in release v1.7.3"
git push origin release-v1.7.3
git checkout master
git pull origin master
git merge --no-ff release-v1.7.3 -m "Merge release v1.7.3 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.7.3 -m "Merge release v1.7.3 into develop"
git push origin develop
git branch -d release-v1.7.3
git push origin --delete release-v1.7.3
