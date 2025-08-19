git checkout develop
git pull origin develop
git checkout -b release-v2.1.0
git push origin release-v2.1.0
git add .
git commit -m "Recognition Module Implemented in release v2.1.0"
git push origin release-v2.1.0
git checkout master
git pull origin master
git merge --no-ff release-v2.1.0 -m "Merge release v2.1.0 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.1.0 -m "Merge release v2.1.0 into develop"
git push origin develop
git branch -d release-v2.1.0
git push origin --delete release-v2.1.0
