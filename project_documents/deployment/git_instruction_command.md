git checkout develop
git pull origin develop
git checkout -b release-v2.5.5
git push origin release-v2.5.5
git add .
git commit -m "Translation Modified with Customization in release v2.5.5"
git push origin release-v2.5.5
git checkout master
git pull origin master
git merge --no-ff release-v2.5.5 -m "Merge release v2.5.5 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.5.5 -m "Merge release v2.5.5 into develop"
git push origin develop
git branch -d release-v2.5.5
git push origin --delete release-v2.5.5
