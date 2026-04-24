<?php
include 'db_connect.php';

// Check if an action was requested (read, update, delete)
$action = isset($_GET['action']) ? $_GET['action'] : '';
$student_id = isset($_GET['student_id']) ? $conn->real_escape_string(trim($_GET['student_id'])) : '';

$record = null;

// If the user wants to update or delete, we need to pre-fetch their data for the popup
if (($action === 'update' || $action === 'delete') && $student_id !== '') {
    $res = $conn->query("SELECT s.*, a.* FROM students s JOIN academic_records a ON s.student_id = a.student_id WHERE s.student_id = '$student_id'");
    if ($res && $res->num_rows > 0) {
        $record = $res->fetch_assoc();
    } else {
        // If they typed an ID that doesn't exist, change action to 'notfound' to trigger the error popup
        $action = 'notfound';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration — UPV</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,600;1,400&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="site-header">
        <div class="header-inner">
            <div class="header-logo">
                <div class="logo-mark">UPV</div>
                <div class="header-text">
                    <span class="header-title">University of the Philippines Visayas</span>
                    <span class="header-sub">Student Information System</span>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">

        <section class="card" id="registration">
            <div class="card-header">
                <h1>Student Registration</h1>
                <p>All fields marked <span class="req">*</span> are required.</p>
            </div>
            
            <form action="create.php" method="POST" enctype="multipart/form-data" class="reg-form">
                
                <h3 style="margin-top: 0; color: var(--maroon);">PERSONAL INFORMATION</h3>
                <label>Name <span class="req">*</span></label>
                <input type="text" name="name" placeholder="e.g. Juan dela Cruz" required>
                
                <label>Email <span class="req">*</span></label>
                <input type="email" name="email" placeholder="e.g. juan@example.com" required>

                <label>Age <span class="req">*</span></label>
                <input type="number" name="age" placeholder="0-99" min="1" max="99" required>

                <h3 style="margin-top: 30px; color: var(--maroon);">ACADEMIC INFORMATION</h3>
                <label>Course <span class="req">*</span></label>
                <input type="text" name="course" placeholder="e.g. BS Computer Science" required>

                <label>Year Level <span class="req">*</span></label>
                <select name="year_level" required>
                    <option value="" disabled selected>Select year</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>

                <h3 style="margin-top: 30px; color: var(--maroon);">GRADUATING?</h3>

                <label style="margin-top: 15px;">
                    <input type="checkbox" name="graduation_status" value="1"> Yes, Graduating this year
                </label>

                <h3 style="margin-top: 30px; color: var(--maroon);">PROFILE PHOTO</h3>
                <label>Profile Image <span class="req">*</span></label>
                
                <div id="uploadZone" class="upload-zone">
                    
                    <div id="uploadPlaceholder">
                        <p style="margin: 0; color: var(--gray-700); font-weight: bold; font-size: 1.1rem;">Click to choose a file or drag it here</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: var(--gray-500);">JPG, PNG, GIF, WEBP accepted</p>
                    </div>
                    
                    <div id="previewContainer">
                        <img id="imagePreview" src="" alt="Profile Preview">
                        <button type="button" id="removeImageBtn">&times;</button>
                    </div>

                    <input type="file" name="profile_image" id="reg_profile_image" accept="image/*" required style="display: none;">
                </div>

                <button type="submit" class="btn" style="width: 100%; margin-top: 25px; padding: 15px; font-size: 1.1rem;">+ Submit Registration</button>
            </form>
        </section>

        <section class="card">
            <div class="card-header">
                <h1>Record Management</h1>
            </div>
            <div style="padding: 20px;">
                <form action="index.php" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <input type="text" name="student_id" placeholder="Enter Student ID (e.g. 2026-12345)" required style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" name="action" value="read" class="btn">Search</button>
                    <button type="submit" name="action" value="update" class="btn" style="background: var(--warning);">Update</button>
                    <button type="submit" name="action" value="delete" class="btn" style="background: var(--danger);">Delete</button>
                </form>
            </div>
        </section>
    </main>

    <?php if (isset($_GET['new_id'])): ?>
    <div class="modal" style="display: flex;">
        <div class="modal-content" style="text-align: center;">
            <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
            <h2 style="color: var(--maroon);">Registration Successful!</h2>
            <p>Your new Student ID is:</p>
            <h1 style="color: var(--maroon); font-size: 3rem; margin: 10px 0;"><?= htmlspecialchars($_GET['new_id']) ?></h1>
            <p style="color: var(--gray-500); margin-bottom: 20px;">Please save this ID to search, update, or delete your record.</p>
            <button class="btn" onclick="window.location.href='index.php'" style="width: 100%;">Close</button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($action === 'notfound'): ?>
    <div class="modal" style="display: flex;">
        <div class="modal-content" style="text-align: center;">
            <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
            <h2 style="color: var(--danger);">Record Not Found</h2>
            <p>No student exists with that ID.</p>
            <button class="btn" onclick="window.location.href='index.php'" style="background: var(--gray-500); width: 100%; margin-top: 15px;">Close</button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($action === 'read' && $student_id !== ''): ?>
    <div class="modal" style="display: flex;">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
            <h2 style="margin-top:0; border-bottom: 2px solid var(--gray-100); padding-bottom: 10px; color: var(--maroon);">Student Record</h2>
            
            <?php include 'read.php'; ?>
            
            <div style="text-align: right; margin-top: 20px;">
                <button class="btn" onclick="window.location.href='index.php'" style="background: var(--gray-500);">Close</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($action === 'update' && $record): ?>
    <div class="modal" style="display: flex;">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
            <h2 style="margin-top:0; border-bottom: 2px solid var(--gray-100); padding-bottom: 10px; color: var(--maroon);">Update Record</h2>
            
            <form action="update.php" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                <input type="hidden" name="student_id" value="<?= htmlspecialchars($record['student_id']) ?>">
                
                <div style="text-align: center; margin-bottom: 15px;">
                    <img src="<?= htmlspecialchars($record['profile_image']) ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #ccc;">
                    <p style="margin: 5px 0; font-size: 0.9rem; color: var(--gray-500);">Change Profile Image (Optional)</p>
                    <input type="file" name="profile_image" accept="image/*" style="display:block; margin: 0 auto;">
                </div>

                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($record['name']) ?>" required style="width:100%; padding:8px; margin-bottom:10px;">
                
                <label>Age</label>
                <input type="number" name="age" value="<?= htmlspecialchars($record['age']) ?>" required style="width:100%; padding:8px; margin-bottom:10px;">
                
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($record['email']) ?>" required style="width:100%; padding:8px; margin-bottom:10px;">
                
                <label>Course</label>
                <input type="text" name="course" value="<?= htmlspecialchars($record['course']) ?>" required style="width:100%; padding:8px; margin-bottom:10px;">
                
                <label>Year Level</label>
                <select name="year_level" required style="width:100%; padding:8px; margin-bottom:10px;">
                    <option value="1" <?= $record['year_level'] == 1 ? 'selected' : '' ?>>1st Year</option>
                    <option value="2" <?= $record['year_level'] == 2 ? 'selected' : '' ?>>2nd Year</option>
                    <option value="3" <?= $record['year_level'] == 3 ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4" <?= $record['year_level'] == 4 ? 'selected' : '' ?>>4th Year</option>
                </select>

                <label style="display: block; margin-top: 10px;">
                    <input type="checkbox" name="graduation_status" value="1" <?= $record['graduation_status'] ? 'checked' : '' ?>> Graduating this year
                </label>

                <button type="submit" name="update_action" class="btn" style="width:100%; margin-top:20px; background: var(--warning);">Save Changes</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($action === 'delete' && $record): ?>
    <div class="modal" style="display: flex;">
        <div class="modal-content" style="text-align: center;">
            <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
            <h2 style="color: var(--danger); margin-top:0;">Confirm Deletion</h2>
            <p>Are you sure you want to permanently delete the record for:</p>
            <h3 style="color: var(--maroon);"><?= htmlspecialchars($record['name']) ?> <br>(<?= htmlspecialchars($record['student_id']) ?>)</h3>
            
            <form action="delete.php" method="POST" style="margin-top: 20px; display:flex; gap:10px; justify-content:center;">
                <input type="hidden" name="student_id" value="<?= htmlspecialchars($record['student_id']) ?>">
                <button type="submit" class="btn" style="background: var(--danger); flex-grow: 1;">Yes, Delete</button>
                <button type="button" class="btn" onclick="window.location.href='index.php'" style="background: var(--gray-500); flex-grow: 1;">Cancel</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <footer class="site-footer" style="text-align: center; padding: 20px; color: var(--gray-500); font-size: 0.9rem;">
        &copy; 2026 University of the Philippines Visayas - CMSC 126
    </footer>

    <script>
        const input = document.getElementById('reg_profile_image');
        const zone = document.getElementById('uploadZone');
        const previewContainer = document.getElementById('previewContainer');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const removeBtn = document.getElementById('removeImageBtn');

        // 1. Click zone to open file dialog (ignore remove button)
        zone.addEventListener('click', (e) => {
            if (e.target !== removeBtn && e.target !== input) {
                input.click();
            }
        });

        // 2. Show instant preview
        function showPreview(file) {
            if (!file) return;
            
            const objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            
            placeholder.style.display = 'none';
            previewContainer.style.display = 'block';
            
            // Add the CSS class to change the border
            zone.classList.add('has-image'); 
        }

        // 3. When file is selected via clicking
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                showPreview(this.files[0]);
            }
        });

        // 4. Remove Button Logic
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation(); 
            input.value = '';    
            preview.src = '';    
            
            previewContainer.style.display = 'none';
            placeholder.style.display = 'block';
            
            // Remove the CSS class to reset the border
            zone.classList.remove('has-image');
        });

        // 5. Drag and Drop Mechanics
        zone.addEventListener('dragover', e => { 
            e.preventDefault(); 
            zone.style.backgroundColor = 'var(--gray-50)'; 
        });
        
        zone.addEventListener('dragleave', e => {
            e.preventDefault();
            zone.style.backgroundColor = 'var(--white)';
        });
        
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.style.backgroundColor = 'var(--white)';
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showPreview(file);
            }
        });
    </script>
</body>
</html>