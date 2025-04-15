Sure! Below is a **step-by-step guide** to accessing **cPanel Terminal locally on your Windows PC using Git Bash** without entering the password every time.  

---

## **Step 1: Open Git Bash**
1. Press **Win + S** and type **Git Bash**.
2. Click on **Git Bash** to open it.

---

## **Step 2: Generate SSH Key (Skip if You Already Have One)**
Run the following command in Git Bash:
```bash
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"
```
- **Press Enter** to save it in the default location:  
  ```
  ~/.ssh/id_rsa
  ```
- **When asked for a passphrase, leave it empty** and press **Enter**.

Now, two files are created in `~/.ssh/`:
- **id_rsa** (Private Key) → Keep this safe, **do not share**.
- **id_rsa.pub** (Public Key) → This will be added to cPanel.

---

## **Step 3: Copy the Public Key**
Run:
```bash
cat ~/.ssh/id_rsa.pub
```
- This will display your **SSH public key**.
- **Copy** the full key (starting with `ssh-rsa`).

---

## **Step 4: Add SSH Key to cPanel**
1. **Login to cPanel**.
2. Search for **SSH Access** and click on it.
3. Click **Manage SSH Keys** → **Import Key**.
4. **Leave the private key field empty**.
5. **Paste the copied public key** into the **Public Key** field.
6. Click **Import** and then **Authorize Key**.

---

## **Step 5: Test SSH Connection**
Now, in Git Bash, run:
```bash
ssh username@yourdomain.com -p 2222
```
- Replace `username` with your **cPanel username**.
- Replace `yourdomain.com` with your **actual domain name**.
For example:
```bash
ssh staffi7@yourdomain.com -p 2222
```

If everything is correct, it should connect **without asking for a password**.

---

## **Step 6: Automate SSH Login (Optional)**
To make it easier to connect without typing the full command every time:

1. Run:
   ```bash
   nano ~/.ssh/config
   ```
2. Add the following content:
   ```
   Host mycpanel
       HostName yourdomain.com
       User your-cpanel-username
       Port 22
       IdentityFile ~/.ssh/id_rsa
   ```
3. **Save the file** (Press **CTRL + X**, then **Y**, then **Enter**).

Now, instead of typing `ssh username@yourdomain.com -p 22`, you can just run:
```bash
ssh mycpanel
```
And it will connect instantly.

---

## **Step 7: Using Git to Pull/Push in cPanel**
Now that SSH is set up, you can use Git in cPanel.

### **Clone a Repository to cPanel**
On your cPanel server:
```bash
git clone git@github.com:your-username/your-repo.git
```

### **Pull the Latest Code**
```bash
cd your-repo
git pull origin main
```

### **Push Code from cPanel**
```bash
git add .
git commit -m "Updated files"
git push origin main
```

---

## **Done! 🎉**
Now you can access your **cPanel terminal locally using Git Bash** **without entering a password** and use **Git commands easily**.

Let me know if you have any issues! 🚀
