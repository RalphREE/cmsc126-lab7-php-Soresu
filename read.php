<?php
/**
 * read.php
 * --------
 * Fetches a student record by student_id and displays it.
 * Called two ways:
 *   1. Included by index.php when ?student_id=N is in the URL.
 *   2. Accessed directly (standalone) – works the same way.
 */

// Only run the DB logic if we actually have an ID to look up
if (isset($_GET['student_id']) && trim($_GET['student_id']) !== '') {

    include_once 'db_connect.php';

    $raw_id     = $_GET['student_id'];
    $student_id = intval($raw_id);   // Cast to int — prevents SQL injection for numeric IDs

    // JOIN both tables so we retrieve every column in one query
    $sql = "SELECT 
                s.student_id,
                s.name,
                s.age,
                s.email,
                a.record_id,
                a.course,
                a.year_level,
                a.graduation_status,
                a.profile_image
            FROM students s
            INNER JOIN academic_records a ON s.student_id = a.student_id
            WHERE s.student_id = $student_id
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $grad_label = $row['graduation_status'] ? 'Yes' : 'No';
        $year_labels = ['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year'];
        $year_label  = $year_labels[$row['year_level']] ?? $row['year_level'];
        ?>

        <!-- ── SEARCH RESULT CARD ─────────────────────────── -->
        <div class="result-card">
            <div class="result-photo">
                <?php if (!empty($row['profile_image']) && file_exists($row['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($row['profile_image']) ?>"
                         alt="Profile photo of <?= htmlspecialchars($row['name']) ?>">
                <?php else: ?>
                    <div class="no-photo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.2">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                        <small>No image</small>
                    </div>
                <?php endif; ?>
            </div>

            <div class="result-details">
                <div class="result-id">Student ID: <?= htmlspecialchars($row['student_id']) ?></div>
                <h3 class="result-name"><?= htmlspecialchars($row['name']) ?></h3>

                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Age</span>
                        <span class="detail-value"><?= htmlspecialchars($row['age']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?= htmlspecialchars($row['email']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Course</span>
                        <span class="detail-value"><?= htmlspecialchars($row['course']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Year Level</span>
                        <span class="detail-value"><?= htmlspecialchars($year_label) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Graduating</span>
                        <span class="detail-value grad-badge <?= $row['graduation_status'] ? 'grad-yes' : 'grad-no' ?>">
                            <?= $grad_label ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        // No record found
        echo '<div class="alert alert-error">No student found with ID <strong>' 
             . htmlspecialchars($raw_id) . '</strong>. Please check the ID and try again.</div>';
    }

    $conn->close();
}
?>