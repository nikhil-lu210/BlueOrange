git checkout develop
git pull origin develop
git checkout -b release-v1.7.2
git push origin release-v1.7.2
git add .
git commit -m "Educational Level Updated in release v1.7.2"
git push origin release-v1.7.2
git checkout master
git pull origin master
git merge --no-ff release-v1.7.2 -m "Merge release v1.7.2 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.7.2 -m "Merge release v1.7.2 into develop"
git push origin develop
git branch -d release-v1.7.2
git push origin --delete release-v1.7.2
