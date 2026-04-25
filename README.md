A PHP/MySQL CRUD application for managing student records at the University of the Philippines Visayas. Built with a two-table relational database and a clean web interface.

---

# Prerequisites
Make sure you have the following installed:

- **XAMPP** (or any local server stack with Apache + MySQL + PHP)
- A modern web browser (Chrome, Firefox, Edge)
- No additional libraries or dependencies are required

---

# Project Structure

```
project-folder/
│
├── index.php          # Main page — registration form + record management UI
├── create.php         # Handles new student registration (INSERT)
├── read.php           # Fetches and displays a student record (SELECT)
├── update.php         # Processes record edits (UPDATE)
├── delete.php         # Permanently removes a record (DELETE)
├── db_connect.php     # Database connection configuration
├── setup.php          # One-time database and table creation script
├── style.css          # Stylesheet
└── uploads/           # Auto-created folder where profile images are stored
```
---

# Setup Instructions

# Step 1 — Place the project files

Copy the entire project folder into your XAMPP web root:

```
C:/xampp/htdocs/cmsc126-lab7-php-Soresu
```

# Step 2 — Start XAMPP services

Open the **XAMPP Control Panel** and start both:
- **Apache**
- **MySQL**

# Step 3 — Run the database setup script

Open your browser and navigate to:

```
http://localhost/cmsc126-lab7-php-Soresu/setup.php
```

You should see the following success messages:

```
Database created successfully.
Table 'students' created successfully.
Table 'academic_records' created successfully.
```

This script creates the `soresu_db` database and two tables:

| Table                 | Key Columns                                                                                      |

| `students`            | `student_id`(PK), `name`, `age`, `email`                                                         |
| `academic_records`    | `record_id`(PK), `student_id` (FK), `course`, `year_level`, `graduation_status`, `profile_image` |

> Caution. Running `setup.php` again will drop and recreate the database, erasing all existing records. Only run it once during initial setup.

# Step 4 — Open the application

```
http://localhost/your-project-folder/index.php
```

---

# How to operate

# Register a New Student (Create)

1. Fill in the *Personal Information* section: Name, Email, Age.
2. Fill in the *Academic Information* section: Course, Year Level.
3. Check the *Graduating** checkbox if applicable.
4. Upload a *profile photo* by clicking the upload zone or dragging an image into it.
   - Accepted formats: JPG, JPEG, PNG, GIF, WEBP
5. Click *+ Submit Registration*.
6. A popup will appear displaying the newly assigned *Student ID* (e.g., `2026-04821`). *Save this ID* — it is required for all future operations on that record.

---

# View a Student Record (Read)

1. In the *Record Management* section, type the Student ID into the search field.
2. Click *Search*.
3. A popup will display the student's full profile, including their photo, personal details, and academic information.

---

# Update a Student Record (Update)

1. Enter the Student ID in the search field.
2. Click the yellow *Update* button.
3. An edit form will appear pre-filled with the student's current data.
4. Modify any fields as needed.
5. Optionally upload a new profile photo (leave the file input empty to keep the existing one).
6. Click **Save Changes**.
7. A confirmation alert will appear, and you will be redirected to the student's updated record.

---

# Delete a Student Record (Delete)

1. Enter the Student ID in the search field.
2. Click the red *Delete* button.
3. A confirmation popup will appear showing the student's name and ID.
4. Click *Yes, Delete* to permanently remove the record, or **Cancel** to go back.
5. Deletion removes the student from both database tables and deletes their uploaded profile image from the server.

---

# Notes & Known Behavior

- *Student IDs* are automatically generated in the format `2026-XXXXX` (e.g., `2026-04821`). They are not sequential — a random 5-digit number is appended each time.
- *Profile images* are stored in the `uploads/` folder inside the project directory. This folder is created automatically on the first registration if it does not exist.
- The `academic_records` table uses `ON DELETE CASCADE`, so deleting a student from the `students` table also removes their linked academic record automatically.
- The application does NOT have a login or authentication system — it is intended for local/academic use only.

---