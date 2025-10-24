# Git Commands for Laboratory Submission

## Step-by-Step Guide to Push Laboratory Exercise 7 to GitHub

### 1. Check Current Status
```bash
git status
```
This shows which files have been modified or created.

### 2. Add All Changes
```bash
git add .
```
This stages all changes for commit.

### 3. Commit Changes
```bash
git commit -m "Lab 7: Implemented file upload and materials management system"
```

### 4. Push to GitHub
```bash
git push origin main
```
OR if your branch is named differently:
```bash
git push origin master
```

## Detailed Commit Message (Optional)

For a more detailed commit message:

```bash
git commit -m "Lab 7: File Upload and Materials Management System

- Created materials database migration with proper foreign keys
- Implemented MaterialModel with CRUD operations
- Created Materials controller with upload, download, delete methods
- Added secure file upload with validation (10MB, specific file types)
- Implemented role-based access control for materials
- Updated student dashboard to display enrolled course materials
- Updated admin dashboard with materials management section
- Created upload and view materials views with Bootstrap styling
- Added materials routes configuration
- Implemented download functionality with enrollment verification
- Created comprehensive testing and documentation

Files created:
- app/Database/Migrations/20251024114017_CreateMaterialsTable.php
- app/Models/MaterialModel.php
- app/Controllers/Materials.php
- app/Views/materials/upload.php
- app/Views/materials/view_course.php
- LABORATORY_TESTING_GUIDE.md
- LAB7_IMPLEMENTATION_SUMMARY.md

Files modified:
- app/Controllers/Auth.php
- app/Controllers/Admin.php
- app/Views/auth/dashboard.php
- app/Views/admin_dashboard.php
- app/Config/Routes.php

Learning objectives achieved:
✅ Database schema design for file uploads
✅ CodeIgniter File Uploading Library
✅ Administrative interface for file management
✅ Access control implementation
✅ Bootstrap UI enhancement"
```

## Verify Push Success

After pushing, verify on GitHub:
1. Go to your repository on GitHub.com
2. Refresh the page
3. Check that all files are updated
4. Click on the commit history to see your latest commit
5. Take a screenshot for laboratory submission

## Common Issues and Solutions

### Issue: "Please commit your changes or stash them"
```bash
git stash
git pull origin main
git stash pop
```

### Issue: "Updates were rejected"
```bash
git pull origin main --rebase
git push origin main
```

### Issue: "Permission denied"
- Check your GitHub authentication
- Use GitHub Desktop or configure SSH keys
- Try HTTPS clone URL instead

### Issue: Large files rejected
```bash
# Remove large files from commit
git reset HEAD large-file.txt
# Or use .gitignore to exclude them
```

## .gitignore Check

Make sure these are in your `.gitignore` file:
```
writable/uploads/*
!writable/uploads/.htaccess
vendor/
.env
```

## Quick Commands Reference

```bash
# View commit history
git log --oneline

# View changes before commit
git diff

# Undo last commit (keep changes)
git reset --soft HEAD~1

# View remote repository URL
git remote -v

# Create new branch
git checkout -b lab7-materials-system

# Switch branch
git checkout main

# Merge branch
git merge lab7-materials-system
```

## Screenshot Checklist for GitHub Submission

✅ Repository main page showing all files  
✅ Latest commit message visible  
✅ Commit history showing "Lab 7" commit  
✅ File count updated in repository  
✅ Commit date/time visible  

## Final Verification

Before marking as complete:
1. ✅ All files committed
2. ✅ Changes pushed to GitHub
3. ✅ Repository accessible
4. ✅ Screenshots taken
5. ✅ Documentation complete
6. ✅ Testing completed
7. ✅ Ready for submission

---

**Ready to push your laboratory work to GitHub!** 🚀
