<?php
/**
 * read.php
 * --------
 * Fetches a student record by student_id and displays it.
 */

// Only run the DB logic if we actually have an ID to look up
if (isset($_GET['student_id']) && trim($_GET['student_id']) !== '') {

    include_once 'db_connect.php';

    $raw_id     = $_GET['student_id'];
    $student_id = $conn->real_escape_string(trim($raw_id));   

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
            WHERE s.student_id = '$student_id'
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $year_labels = [1 => '1st Year', 2 => '2nd Year', 3 => '3rd Year', 4 => '4th Year'];
        $year_label  = isset($year_labels[$row['year_level']]) ? $year_labels[$row['year_level']] : $row['year_level'];
        $grad_label  = $row['graduation_status'] ? 'Yes' : 'No';
        ?>

        <div class="result-card" style="display: flex; gap: 20px; align-items: flex-start; margin-top: 15px;">
            <img src="<?= htmlspecialchars($row['profile_image']) ?>" class="result-photo" style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #ccc;" alt="Profile Photo">
            <div class="result-details" style="flex-grow: 1;">
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Student ID:</span>
                    <span class="detail-value"><?= htmlspecialchars($row['student_id']) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($row['name']) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Age:</span>
                    <span class="detail-value"><?= htmlspecialchars($row['age']) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Email:</span>
                    <span class="detail-value"><?= htmlspecialchars($row['email']) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Course:</span>
                    <span class="detail-value"><?= htmlspecialchars($row['course']) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Year Level:</span>
                    <span class="detail-value"><?= htmlspecialchars($year_label) ?></span>
                </div>
                <div class="detail-item" style="margin-bottom: 8px;">
                    <span class="detail-label" style="font-weight: bold; color: var(--maroon);">Graduating:</span>
                    <span class="detail-value grad-badge <?= $row['graduation_status'] ? 'grad-yes' : 'grad-no' ?>">
                        <?= $grad_label ?>
                    </span>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo '<div class="alert alert-error">No student found with ID <strong>' 
             . htmlspecialchars($raw_id) . '</strong>. Please check the ID and try again.</div>';
    }

    $conn->close();
}
?>