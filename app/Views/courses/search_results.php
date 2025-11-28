<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Search Results - ITE311-SANTILLAN</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            margin: 0;
            color: #333;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #b52a38;
        }
        
        .search-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-form input {
            flex: 1;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .search-form input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-form button {
            padding: 12px 25px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .search-form button:hover {
            background: #5568d3;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 15px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .results-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        
        .results-info strong {
            color: #667eea;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .course-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .course-card h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }
        
        .course-card p {
            margin: 0 0 15px 0;
            color: #666;
            font-size: 14px;
            flex-grow: 1;
        }
        
        .course-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .enroll-btn {
            flex: 1;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .enroll-btn:hover {
            background: #218838;
        }
        
        .enroll-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .enrolled-badge {
            flex: 1;
            padding: 10px;
            background: #e8f5e9;
            color: #28a745;
            border: 2px solid #28a745;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .no-results {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #666;
        }
        
        .no-results h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .client-side-filter {
            margin-bottom: 20px;
        }
        
        .client-side-filter input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .client-side-filter input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .filter-info {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4d4d;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <div>
            <h1>🔍 Search Results</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Welcome, <?= esc($user_name) ?></p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="logout-btn">← Back to Dashboard</a>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <a href="<?= base_url('dashboard') ?>" class="back-link">← Back to Dashboard</a>
        
        <!-- Unified Search Form -->
        <h3>🔎 Search Courses</h3>
        <form id="searchResultsForm" class="search-form">
            <?= csrf_field() ?>
            <input type="text" id="searchResultsInput" name="search" placeholder="Search by course title or description..." 
                   value="<?= esc($searchTerm) ?>">
            <button type="submit" id="searchResultsBtn">Search</button>
        </form>
        <p class="filter-info">💡 Type to filter results instantly or click Search to perform a new search</p>

        <!-- Results Info -->
        <?php if (!empty($searchTerm)): ?>
            <div class="results-info">
                📊 Showing results for: <strong>"<?= esc($searchTerm) ?>"</strong> 
                (Found <strong><?= count($courses) ?></strong> course<?= count($courses) !== 1 ? 's' : '' ?>)
            </div>
        <?php else: ?>
            <div class="results-info">
                📊 Showing all available courses (<strong><?= count($courses) ?></strong> total)
            </div>
        <?php endif; ?>
    </div>

    <!-- Courses Display -->
    <?php if (!empty($courses)): ?>
        <div class="courses-grid" id="coursesContainer">
            <?php foreach ($courses as $course): ?>
                <div class="course-card" data-title="<?= esc(strtolower($course['title'])) ?>" 
                     data-description="<?= esc(strtolower($course['description'] ?? '')) ?>">
                    <h3><?= esc($course['title']) ?></h3>
                    <p><?= esc($course['description'] ?? 'No description available') ?></p>
                    
                    <div class="course-actions">
                        <?php 
                            $isEnrolled = in_array($course['id'], $enrolledCourses);
                        ?>
                        <?php if ($isEnrolled): ?>
                            <div class="enrolled-badge">✅ Enrolled</div>
                        <?php else: ?>
                            <button class="enroll-btn" onclick="enrollCourse(<?= $course['id'] ?>, this)">
                                Enroll Now
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <h2>😕 No Courses Found</h2>
            <p>
                <?php if (!empty($searchTerm)): ?>
                    No courses match your search for "<strong><?= esc($searchTerm) ?></strong>". 
                    Try a different search term.
                <?php else: ?>
                    No courses are available at the moment.
                <?php endif; ?>
            </p>
            <a href="<?= base_url('course/search') ?>" style="color: #667eea; text-decoration: none;">
                ← Try a new search
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for Client-Side Filtering and AJAX Enrollment -->
<script>
const baseUrl = '<?= base_url() ?>';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ============================================
// UNIFIED SEARCH BAR - INSTANT FILTERING
// ============================================
const searchResultsInput = document.getElementById('searchResultsInput');
if (searchResultsInput) {
    searchResultsInput.addEventListener('keyup', function(e) {
        // Don't filter if Enter key is pressed (let form submit instead)
        if (e.key === 'Enter') {
            return;
        }
        
        const filterValue = this.value.toLowerCase().trim();
        const courseCards = document.querySelectorAll('.course-card');
        let visibleCount = 0;

        courseCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const description = card.getAttribute('data-description');

            // If filter is empty, show all
            if (filterValue === '') {
                card.style.display = '';
                visibleCount++;
            } else if (title.includes(filterValue) || description.includes(filterValue)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update results info
        const resultsInfo = document.querySelector('.results-info');
        if (filterValue !== '') {
            resultsInfo.innerHTML = `⚡ Instant filter: <strong>"${filterValue}"</strong> (Showing <strong>${visibleCount}</strong> of ${courseCards.length} courses)`;
        }
    });
}

// ============================================
// AJAX SEARCH FORM SUBMISSION (Search Results Page)
// ============================================
console.log('🔵 ========== AJAX SEARCH RESULTS SCRIPT STARTING ==========');

function setupSearchResultsForm() {
    console.log('🔵 setupSearchResultsForm() called');
    const searchResultsForm = document.getElementById('searchResultsForm');
    const searchInput = document.getElementById('searchResultsInput');
    const searchBtn = document.getElementById('searchResultsBtn');
    
    console.log('🔵 searchResultsForm element:', searchResultsForm ? '✅ FOUND' : '❌ NOT FOUND');
    console.log('🔵 searchInput element:', searchInput ? '✅ FOUND' : '❌ NOT FOUND');
    console.log('🔵 searchBtn element:', searchBtn ? '✅ FOUND' : '❌ NOT FOUND');
    
    if (!searchResultsForm) {
        console.error('❌ searchResultsForm not found! Cannot attach listener');
        return false;
    }
    
    console.log('🔵 ✅ All elements found! Attaching submit listener...');
    
    searchResultsForm.addEventListener('submit', function(e) {
        console.log('🔵 ✅✅✅ FORM SUBMIT EVENT TRIGGERED (Results Page)! ✅✅✅');
        e.preventDefault();
        e.stopPropagation();
            
            const searchTerm = document.getElementById('searchResultsInput').value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const searchBtn = document.getElementById('searchResultsBtn');
            
            console.log('🔵 Search term:', searchTerm);
            console.log('🔵 CSRF Token present:', csrfToken ? 'YES' : 'NO');
            
            if (!searchTerm) {
                console.log('🔵 Empty search, submitting normally');
                return;
            }
            
            const searchUrl = `${baseUrl}course/search`;
            console.log('🔵 Search URL:', searchUrl);
            console.log('🔵 Sending AJAX POST request...');
            
            // Show loading state
            const originalText = searchBtn.textContent;
            searchBtn.textContent = '⏳ Searching...';
            searchBtn.disabled = true;
            
            fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `search=${encodeURIComponent(searchTerm)}`,
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('🔵 Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('🟢 AJAX SUCCESS! Response received');
                console.log('🟢 Search results:', data);
                console.log('🟢 Found', data.count, 'courses');
                console.log('✅ AJAX request complete - results ready');
                console.log('✅ AJAX request will stay visible in Network tab for 15 seconds');
                console.log('✅ Waiting 15 seconds before navigating...');
                
                // Wait 15 seconds so AJAX request stays visible in Network tab
                setTimeout(() => {
                    console.log('✅ 15 seconds elapsed - now navigating to search results...');
                    
                    // Navigate using GET method which shows in Network tab
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = searchUrl;
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'search';
                    input.value = searchTerm;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }, 15000);
            })
            .catch(error => {
                console.error('❌ AJAX Error:', error);
                alert('❌ Search failed: ' + error.message);
                searchBtn.textContent = originalText;
                searchBtn.disabled = false;
            });
        }, false);
    
    return true;
}

// Try immediately
console.log('🔵 Attempting immediate setup...');
const setupResult1 = setupSearchResultsForm();
console.log('🔵 Immediate setup result:', setupResult1);

// Try on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔵 DOMContentLoaded fired, attempting setup...');
    const setupResult2 = setupSearchResultsForm();
    console.log('🔵 DOMContentLoaded setup result:', setupResult2);
});

// Try on readystatechange
document.addEventListener('readystatechange', function() {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        console.log('🔵 Document ready state:', document.readyState);
        const setupResult3 = setupSearchResultsForm();
        console.log('🔵 readystatechange setup result:', setupResult3);
    }
});

console.log('🔵 ========== AJAX SEARCH RESULTS SCRIPT LOADED ==========');

// ============================================
// AJAX ENROLLMENT
// ============================================
function enrollCourse(courseId, button) {
    console.log('🔵 Enrollment started for course ID:', courseId);
    
    if (!courseId) {
        console.error('❌ Invalid course ID');
        alert('Invalid course ID');
        return;
    }

    // Disable button to prevent multiple clicks
    button.disabled = true;
    button.textContent = 'Enrolling...';
    console.log('🔵 Button disabled, sending AJAX request...');

    const enrollUrl = `${baseUrl}course/enroll`;
    console.log('🔵 Enrollment URL:', enrollUrl);
    console.log('🔵 CSRF Token:', csrfToken);

    fetch(enrollUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        body: `course_id=${courseId}`
    })
    .then(response => {
        console.log('🔵 Response received:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('🟢 AJAX Response:', data);
        
        if (data.status === 'success') {
            console.log('✅ Enrollment successful!');
            
            // Replace button with enrolled badge
            const actionsDiv = button.closest('.course-actions');
            actionsDiv.innerHTML = '<div class="enrolled-badge">✅ Enrolled</div>';
            
            // Show success message
            alert('✅ Successfully enrolled in the course!');
            console.log('✅ Success message shown');
            
            // Update unread count
            updateUnreadCount();
        } else {
            console.error('❌ Enrollment failed:', data.message);
            alert('❌ ' + (data.message || 'Enrollment failed'));
            button.disabled = false;
            button.textContent = 'Enroll Now';
        }
    })
    .catch(error => {
        console.error('❌ AJAX Error:', error);
        alert('❌ An error occurred during enrollment: ' + error.message);
        button.disabled = false;
        button.textContent = 'Enroll Now';
    });
}

// Update unread notification count
function updateUnreadCount() {
    fetch(`${baseUrl}/notifications/unread_count`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Unread count updated:', data.unreadCount);
        }
    })
    .catch(error => console.error('Error fetching unread count:', error));
}
</script>
</body>
</html>
