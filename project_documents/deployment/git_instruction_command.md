git checkout develop
git pull origin develop
git checkout -b release-v1.9.1
git push origin release-v1.9.1
git add .
git commit -m "UI Updated For Quiz Test & Result Page in release v1.9.1"
git push origin release-v1.9.1
git checkout master
git pull origin master
git merge --no-ff release-v1.9.1 -m "Merge release v1.9.1 into master"
git push origin master
git checkout develop
git merge --no-ff release-v1.9.1 -m "Merge release v1.9.1 into develop"
git push origin develop
git branch -d release-v1.9.1
git push origin --delete release-v1.9.1
