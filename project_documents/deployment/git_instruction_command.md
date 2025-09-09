git checkout develop
git pull origin develop
git checkout -b release-v2.3.7
git push origin release-v2.3.7
git add .
git commit -m "Custom Error Handler Modified and Code Refactored in release v2.3.7"
git push origin release-v2.3.7
git checkout master
git pull origin master
git merge --no-ff release-v2.3.7 -m "Merge release v2.3.7 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.3.7 -m "Merge release v2.3.7 into develop"
git push origin develop
git branch -d release-v2.3.7
git push origin --delete release-v2.3.7
