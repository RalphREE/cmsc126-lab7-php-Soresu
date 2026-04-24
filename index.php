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

    <!-- ── HEADER ───────────────────────────────────────── -->
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

    <!-- ── MAIN ─────────────────────────────────────────── -->
    <main class="main-content">

        <!-- REGISTRATION FORM -->
        <section class="card" id="registration">
            <div class="card-header">
                <h1>Student Registration</h1>
                <p>All fields marked <span class="req">*</span> are required.</p>
            </div>

            <form action="create.php" method="POST" enctype="multipart/form-data" class="reg-form">

                <!-- PERSONAL INFORMATION -->
                <fieldset>
                    <legend>Personal Information</legend>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="name">Name <span class="req">*</span></label>
                            <input type="text" id="name" name="name"
                                   placeholder="e.g. Juan dela Cruz"
                                   maxlength="40" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age <span class="req">*</span></label>
                            <input type="number" id="age" name="age"
                                   placeholder="0 – 99"
                                   min="0" max="99" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" id="email" name="email"
                               placeholder="e.g. juan@example.com"
                               maxlength="40" required>
                        <span class="hint">Must be a valid email address (max 40 chars).</span>
                    </div>
                </fieldset>

                <!-- ACADEMIC INFORMATION -->
                <fieldset>
                    <legend>Academic Information</legend>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="course">Course <span class="req">*</span></label>
                            <input type="text" id="course" name="course"
                                   placeholder="e.g. BS Computer Science"
                                   maxlength="40" required>
                        </div>
                        <div class="form-group">
                            <label for="year_level">Year Level <span class="req">*</span></label>
                            <select id="year_level" name="year_level" required>
                                <option value="" disabled selected>Select year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            Graduating this year? <span class="req">*</span>
                        </label>
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="graduation_status" value="1">
                            <span class="checkmark"></span>
                            Yes
                        </label>
                    </div>
                </fieldset>

                <!-- PROFILE PHOTO -->
                <fieldset>
                    <legend>Profile Photo</legend>

                    <div class="form-group">
                        <label>Profile Image <span class="req">*</span></label>
                        <div class="upload-zone" id="uploadZone">
                            <input type="file" id="profile_image" name="profile_image"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   required class="file-input">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <p>Choose a file or drag it here</p>
                                <small>JPG, PNG, GIF, WEBP accepted</small>
                            </div>
                            <img id="imagePreview" class="image-preview hidden" src="" alt="Preview">
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-primary btn-full">
                    + Submit Registration
                </button>

            </form>
        </section>

        <!-- RECORD MANAGEMENT -->
        <section class="card" id="record-management">
            <div class="card-header">
                <h2>Record Management</h2>
            </div>

            <!-- SEARCH / READ -->
            <div class="rm-block">
                <h3>Look Up by Student ID</h3>
                <form action="read.php" method="GET" class="inline-form">
                    <input type="text" name="student_id"
                           placeholder="Enter Student ID (e.g. 1)"
                           class="inline-input">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </form>
            </div>

            <!-- SEARCH RESULTS (populated by read.php via GET redirect or include) -->
            <?php
            // If read.php sets a $student result in session or we inline-include results here
            if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
                include 'read.php';
            }
            ?>

            <!-- UPDATE -->
            <div class="rm-block">
                <h3>Update Record</h3>
                <form action="update.php" method="GET" class="inline-form">
                    <input type="text" name="student_id"
                           placeholder="Enter Student ID to update"
                           class="inline-input">
                    <button type="submit" class="btn btn-warning">Update</button>
                </form>
            </div>

            <!-- DELETE -->
            <div class="rm-block">
                <h3>Delete Record</h3>
                <form action="delete.php" method="POST" class="inline-form"
                      onsubmit="return confirm('Are you sure you want to delete this record? This cannot be undone.');">
                    <input type="text" name="student_id"
                           placeholder="Enter Student ID to delete"
                           class="inline-input">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>

        </section>

    </main>

    <footer class="site-footer">
        <p>CMSC 126 — Web Programming &nbsp;|&nbsp; University of the Philippines Visayas &nbsp;|&nbsp; AY 2024–2025</p>
    </footer>

    <script>
        // Image drag and drop/preview
        const input    = document.getElementById('profile_image');
        const zone     = document.getElementById('uploadZone');
        const preview  = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => showPreview(input.files[0]));

        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                // Transfer to input
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showPreview(file);
            }
        });
    </script>

</body>
</html>