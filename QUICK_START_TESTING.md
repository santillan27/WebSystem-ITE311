# Quick Start Testing Guide
## Test Your Laboratory Exercise 7 in 10 Minutes

### ⚡ Prerequisites Check
- [x] XAMPP Apache and MySQL running
- [x] Development server running on http://localhost:8080
- [x] Database migrations completed
- [x] Browser ready

---

## 🚀 Fast Testing Path (10 Steps)

### Step 1: Access the Application (30 seconds)
```
Open browser: http://localhost:8080
You should see the home page
```

### Step 2: Login as Admin (1 minute)
```
Navigate to: http://localhost:8080/auth/login
Email: Your admin email
Password: Your admin password
```
**Expected:** Redirect to admin dashboard

### Step 3: View Materials Management (30 seconds)
```
On admin dashboard, scroll to "Course Materials Management" section
```
**Expected:** See list of courses with upload and view buttons

### Step 4: Upload a Test File (2 minutes)
```
1. Click "📤 Upload Material" for any course
2. Choose a PDF file (less than 10MB)
3. Click "Upload Material"
```
**Expected:** Success message and redirect to dashboard

**Take Screenshots:**
- ✅ Upload form page
- ✅ Success message

### Step 5: Verify File in System (1 minute)
```
Open File Explorer:
Navigate to: C:\xampp\htdocs\ITE311-SANTILLAN\writable\uploads\materials\

You should see a file with random name
```
**Take Screenshot:** ✅ File system showing uploaded file

### Step 6: View Materials List (1 minute)
```
Back to admin dashboard
Click "📁 View Materials" for the course you uploaded to
```
**Expected:** See table with your uploaded file

**Take Screenshot:** ✅ Materials list view

### Step 7: Check Database (1 minute)
```
Open: http://localhost/phpmyadmin
Navigate to your database
Click on "materials" table
Click "Browse" tab
```
**Expected:** See your uploaded file record

**Take Screenshot:** ✅ Database materials table with data

### Step 8: Test Student Access (2 minutes)
```
1. Logout from admin
2. Login as student (or create one if needed)
3. Enroll in the course that has materials
4. View dashboard - scroll to "My Course Materials"
```
**Expected:** See uploaded materials in the list

**Take Screenshot:** ✅ Student dashboard showing materials

### Step 9: Test Download (1 minute)
```
Click "⬇️ Download" button next to a material
```
**Expected:** File downloads successfully

**Take Screenshot:** ✅ Downloaded file in downloads folder

### Step 10: Test Access Control (30 seconds)
```
Still logged in as student
Try to access: http://localhost:8080/admin/course/1/upload
```
**Expected:** Access denied or redirect to login

**Take Screenshot:** ✅ Access denied message

---

## ✅ Quick Test Result Summary

After completing the 10 steps above, you should have:

**Functionality Verified:**
- ✅ Admin can access upload form
- ✅ Files upload successfully
- ✅ Files stored in correct directory
- ✅ Database records created
- ✅ Students can view materials
- ✅ Students can download materials
- ✅ Access control works

**Screenshots Captured:**
- ✅ Upload form
- ✅ Success message
- ✅ File system
- ✅ Materials list
- ✅ Database table
- ✅ Student dashboard
- ✅ Downloaded file
- ✅ Access denied

---

## 🔍 Quick Troubleshooting

### Problem: Can't access upload page
**Solution:** Make sure you're logged in as admin or teacher

### Problem: Upload fails
**Solution:** 
- Check file size (must be under 10MB)
- Check file type (must be PDF, DOC, PPT, etc.)
- Verify upload directory exists

### Problem: Download not working
**Solution:** 
- Verify file exists in writable/uploads/materials/
- Check student is enrolled in the course

### Problem: Page not found errors
**Solution:** 
- Verify routes are configured correctly
- Clear browser cache
- Restart development server

---

## 📸 Screenshot Checklist

Before moving to GitHub submission, ensure you have:

1. ✅ Database schema (phpMyAdmin)
2. ✅ Admin dashboard with materials section
3. ✅ Upload form
4. ✅ File system with uploaded files
5. ✅ Student dashboard with materials
6. ✅ Downloaded file confirmation
7. ✅ Access control demonstration

---

## 🚀 Ready for GitHub?

If all tests passed, proceed with:

```bash
git add .
git commit -m "Lab 7: File upload system - Tested and working"
git push origin main
```

Then take final screenshot of GitHub repository!

---

## 🎯 Success Criteria

Your lab is complete when:
- ✅ All 10 tests pass
- ✅ All screenshots captured
- ✅ Code pushed to GitHub
- ✅ No error messages during testing
- ✅ All features work as expected

---

## ⏱️ Time Estimate

**Total Time:** ~10-15 minutes
- Testing: 10 minutes
- Screenshots: 3 minutes
- GitHub push: 2 minutes

---

## 🎉 You're Almost Done!

Complete the quick testing above, and you'll be ready to submit your laboratory exercise!

**Need detailed testing?** See `LABORATORY_TESTING_GUIDE.md`
**Need implementation details?** See `LAB7_IMPLEMENTATION_SUMMARY.md`
**Need Git help?** See `GIT_COMMANDS.md`
**Need submission checklist?** See `SUBMISSION_CHECKLIST.md`

---

**Good luck with your testing! You've got this! 💪**
