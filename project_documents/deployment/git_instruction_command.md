git checkout develop
git pull origin develop
git checkout -b release-v2.3.8
git push origin release-v2.3.8
git add .
git commit -m "Error Handler Updated and Chatting module restricted in release v2.3.8"
git push origin release-v2.3.8
git checkout master
git pull origin master
git merge --no-ff release-v2.3.8 -m "Merge release v2.3.8 into master"
git push origin master
git checkout develop
git merge --no-ff release-v2.3.8 -m "Merge release v2.3.8 into develop"
git push origin develop
git branch -d release-v2.3.8
git push origin --delete release-v2.3.8
