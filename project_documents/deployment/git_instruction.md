In Git, a **release branch** is created to prepare for a new release while keeping the `master` or `main` branch stable. Below are the steps to create a release branch and merge it into the `master` branch.

---

### 🚀 **Step 1: Create a Release Branch**
1. **Switch to the `develop` branch** (or the main working branch):
   ```sh
   git checkout develop
   ```

2. **Ensure the branch is up to date**:
   ```sh
   git pull origin develop
   ```

3. **Create a new release branch** (e.g., `release-v1.0.0`):
   ```sh
   git checkout -b release-v1.0.0
   ```

4. **Push the branch to remote**:
   ```sh
   git push origin release-v1.0.0
   ```

---

### 🔄 **Step 2: Test and Fix Bugs on the Release Branch**
- Perform testing and fix bugs on the `release-v1.0.0` branch.
- If fixes are needed, commit them on this branch:
  ```sh
  git add .
  git commit -m "Fix bug in release v1.0.0"
  git push origin release-v1.0.0
  ```

---

### ✅ **Step 3: Merge the Release Branch into `master`**
1. **Switch to the `master` branch**:
   ```sh
   git checkout master
   ```

2. **Ensure it's up to date**:
   ```sh
   git pull origin master
   ```

3. **Merge the release branch**:
   ```sh
   git merge --no-ff release-v1.0.0 -m "Merge release v1.0.0 into master"
   ```

4. **Push the changes to `master`**:
   ```sh
   git push origin master
   ```

---

### 🔄 **Step 4: Merge the Release Branch into `develop`**
Since some bug fixes may have been made on the release branch, merge it back into `develop`:

1. **Switch to `develop`**:
   ```sh
   git checkout develop
   ```

2. **Merge the release branch**:
   ```sh
   git merge --no-ff release-v1.0.0 -m "Merge release v1.0.0 into develop"
   ```

3. **Push the changes to `develop`**:
   ```sh
   git push origin develop
   ```

---

### 🗑️ **Step 5: Delete the Release Branch**
Once the release is complete and merged, delete the release branch:

1. **Delete locally**:
   ```sh
   git branch -d release-v1.0.0
   ```

2. **Delete remotely**:
   ```sh
   git push origin --delete release-v1.0.0
   ```

---

### 🎉 **Now your release is merged and cleaned up!**  
Would you like to automate this process with a script? 😃