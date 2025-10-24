# Laboratory Exercise 7 - File Upload System Testing Guide

## Overview
This document provides step-by-step instructions for testing the file upload and materials management system for the LMS.

## Prerequisites
- XAMPP running (Apache and MySQL)
- CodeIgniter development server running on http://localhost:8080
- Database migrations completed
- Sample user accounts created (admin, teacher, student)

## Testing Steps

### Step 1: Verify Database Schema
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select your database
3. Verify the `materials` table exists with the following columns:
   - `id` (INT, Primary Key, Auto Increment)
   - `course_id` (INT, Foreign Key)
   - `file_name` (VARCHAR 255)
   - `file_path` (VARCHAR 255)
   - `created_at` (DATETIME)

**Screenshot Required:** Database schema showing materials table structure

---

### Step 2: Test Admin File Upload
1. Navigate to http://localhost:8080/auth/login
2. Login with admin credentials
3. On the admin dashboard, you should see "Course Materials Management" section
4. Click "📤 Upload Material" button for any course
5. On the upload form:
   - Select a file (PDF, DOC, PPT, etc. - Max 10MB)
   - Click "Upload Material"
6. Verify success message appears
7. Check `writable/uploads/materials/` folder to confirm file exists

**Screenshots Required:**
- Admin dashboard showing Course Materials Management section
- Upload form page
- Success message after upload
- File system showing uploaded file in writable/uploads/materials/

---

### Step 3: Test Material Viewing (Admin)
1. While logged in as admin
2. Click "📁 View Materials" for the course you uploaded to
3. Verify the uploaded material appears in the table with:
   - File name
   - Upload date
   - Download button
   - Delete button (admin/teacher only)

**Screenshot Required:** Materials list view showing uploaded files

---

### Step 4: Test Student Enrollment and Material Access
1. Logout from admin account
2. Login with student credentials
3. Navigate to the student dashboard
4. Enroll in a course that has materials
5. Verify "📚 View Materials" link appears in enrolled courses
6. Verify "📁 My Course Materials" section shows materials from enrolled courses
7. Click "⬇️ Download" button to download a material
8. Verify file downloads successfully

**Screenshots Required:**
- Student dashboard showing enrolled courses with "View Materials" link
- Student dashboard showing "My Course Materials" section
- Downloaded file confirmation

---

### Step 5: Test Access Control
1. While logged in as student
2. Try to access a course material download link for a course you're NOT enrolled in
3. Verify you receive an error message: "You are not enrolled in this course"
4. Try to access upload page directly: http://localhost:8080/admin/course/1/upload
5. Verify access is denied (redirect or error)

**Screenshot Required:** Error message when trying to access restricted material

---

### Step 6: Test Material Deletion
1. Login as admin or teacher
2. Navigate to a course's materials page
3. Click "Delete" button next to a material
4. Confirm deletion in the popup
5. Verify:
   - Material removed from database
   - Physical file deleted from writable/uploads/materials/
   - Success message displayed

**Screenshot Required:** Materials list before and after deletion

---

### Step 7: Test File Upload Validation
1. Login as admin
2. Navigate to upload page
3. Try uploading:
   - A file larger than 10MB (should fail)
   - An invalid file type like .exe (should fail)
   - A valid file (should succeed)
4. Verify appropriate error messages for failed uploads

**Screenshot Required:** Error message showing file validation

---

## Expected Results Summary

✅ Materials table created successfully
✅ Admin can upload files to courses
✅ Files stored in writable/uploads/materials/ directory
✅ Students can view materials for enrolled courses only
✅ Students can download materials
✅ Admin/Teacher can delete materials
✅ Access control prevents unauthorized downloads
✅ File validation works correctly
✅ Database records match uploaded files

## Database Verification Queries

```sql
-- View all materials
SELECT m.*, c.title as course_title 
FROM materials m 
JOIN courses c ON c.id = m.course_id 
ORDER BY m.created_at DESC;

-- Count materials per course
SELECT c.title, COUNT(m.id) as material_count 
FROM courses c 
LEFT JOIN materials m ON m.course_id = c.id 
GROUP BY c.id, c.title;

-- View materials for enrolled students
SELECT u.name as student_name, c.title as course_title, m.file_name, m.created_at
FROM users u
JOIN enrollments e ON e.user_id = u.id
JOIN courses c ON c.id = e.course_id
JOIN materials m ON m.course_id = c.id
WHERE u.role = 'student'
ORDER BY u.name, m.created_at DESC;
```

## Troubleshooting

### Issue: File upload fails
- Check `writable/uploads/materials/` directory exists and has write permissions
- Verify file size is under 10MB
- Check file extension is allowed

### Issue: Download not working
- Verify file exists in `writable/uploads/materials/` directory
- Check database record has correct file_path
- Ensure user is enrolled in the course

### Issue: Access denied errors
- Verify routes are configured correctly
- Check RoleAuth filter is working
- Confirm user session is active

## Submission Requirements

For laboratory submission, include:
1. ✅ Screenshot of materials table schema (phpMyAdmin)
2. ✅ Screenshot of admin upload form
3. ✅ Screenshot of file system with uploaded files
4. ✅ Screenshot of student dashboard with materials section
5. ✅ Screenshot of material download confirmation
6. ✅ Screenshot of GitHub repository with latest commit
7. ✅ Brief report documenting testing process and results

## Additional Features Implemented

- **Security**: File type validation, size limits, access control
- **User Experience**: Bootstrap UI, success/error messages, intuitive navigation
- **Database Integrity**: Foreign keys, proper relationships
- **File Management**: Secure file storage, organized directory structure
- **Role-Based Access**: Separate views for admin, teacher, and student roles

---

**Laboratory Exercise Completed Successfully!** 🎉
