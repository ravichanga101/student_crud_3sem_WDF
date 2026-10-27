# Student CRUD - Setup Guide (XAMPP)

This project is a simple **Student Management System** built with PHP and MySQL.
It lets you **Create, Read, Update and Delete (CRUD)** student records.

This guide is written step by step for complete beginners. Just follow it from top to bottom.

---

## 1. What you need

- **XAMPP** installed on your computer (includes Apache web server + MySQL/MariaDB database).
  - Download: https://www.apachefriends.org/download.html
- This project folder (`student_crud`) placed inside the XAMPP `htdocs` folder.
  - On Windows, that is usually: `C:\xampp\htdocs\student_crud`
  - If you already see this folder at that path, you're good to go.

---

## 2. Start Apache and MySQL in XAMPP

1. Open the **XAMPP Control Panel** (search for "XAMPP Control Panel" in the Start menu).
2. Find the row **Apache** and click its **Start** button.
3. Find the row **MySQL** and click its **Start** button.
4. Both rows should turn **green** and show "Running". If either fails to start:
   - Make sure no other program (like Skype, IIS, or another MySQL) is using ports **80** and **3306**.
   - Close the conflicting program and click **Start** again.

---

## 3. Open phpMyAdmin

1. In the XAMPP Control Panel, click the **Admin** button next to MySQL
   (or open your browser and go to: `http://localhost/phpmyadmin`).
2. phpMyAdmin is a web page tool used to manage your databases without typing complex commands.

---

## 4. Create the database

1. In phpMyAdmin, click **New** in the left sidebar.
2. In the **Database name** box, type exactly:
   ```
   student_db
   ```
3. Leave the collation as default (or choose `utf8mb4_general_ci`).
4. Click **Create**.
5. You should now see `student_db` listed in the left sidebar. Click on it to open it.

> The name `student_db` must match what is in [db.php](/c:/xampp/htdocs/student_crud/db.php) (`DB_NAME`). If you use a different name, update that file too.

---

## 5. Create the `students` table

1. With `student_db` selected (open) in phpMyAdmin, click the **SQL** tab at the top.
2. Copy the SQL below and paste it into the big text box.

```sql
CREATE TABLE students (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    semester INT(2) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);
```

3. Click the **Go** button (usually bottom-right) to run it.
4. You should see a success message. Click on **students** in the left sidebar (under `student_db`) to confirm the table was created with the columns: `id`, `name`, `semester`, `email`, `password`, `city`, and `profile_picture`.

> You do not need to add any rows manually — the website's "Add Student" form will insert rows for you.

> If you already created the `students` table using an earlier version of this guide, run this once in the phpMyAdmin **SQL** tab:
> ```sql
> ALTER TABLE students ADD profile_picture VARCHAR(255) DEFAULT NULL;
> ```

---

## 6. Check the database connection settings

Open [db.php](/c:/xampp/htdocs/student_crud/db.php) and confirm the values match your XAMPP setup (defaults below work for a fresh XAMPP install):

```php
const DB_SERVER = "localhost";
const DB_NAME = "student_db";
const DB_USERNAME = "root";
const DB_PASSWORD = "";
```

- `DB_USERNAME` is `root` and `DB_PASSWORD` is empty by default in XAMPP.
- Only change these if you set a custom MySQL username/password.

---

## 7. Run the project in your browser

1. Make sure Apache and MySQL are still running (green) in the XAMPP Control Panel.
2. Open your browser and go to:
   ```
   http://localhost/student_crud/
   ```
3. You should see the **Student Management System** page with:
   - An "Add Student" form at the top.
   - A "Student List" table below it (empty at first).

---

## 8. Try it out

1. Fill in the "Add Student" form (Name, Semester, Email, Password, Confirm Password, City, and optionally a JPEG, PNG, GIF, or WebP profile picture up to 5 MB) and click **Add Student**.
2. The new student should now appear in the "Student List" table.
3. Click **Edit** next to a student to update their details or replace their profile picture. Leaving the picture upload empty keeps the current picture.
4. Click **Delete** next to a student to remove them (you'll be asked to confirm).

---

## Troubleshooting

| Problem | Likely cause / fix |
|---|---|
| Page shows "Unable to connect" or blank page | Apache is not running — start it in the XAMPP Control Panel. |
| Error mentions `SQLSTATE` or "could not find driver" / "Unknown database" | MySQL is not running, or `student_db` was not created — repeat steps 2 and 4. |
| Error mentions "Table 'student_db.students' doesn't exist" | The table wasn't created — repeat step 5. |
| Port 80 or 3306 won't start (shown in red) | Another program is using that port. Close Skype/IIS/other MySQL instances, then click Start again. |
| Changes to code don't show up | Make sure the project folder is inside `C:\xampp\htdocs\` and you're browsing `http://localhost/student_crud/` (not opening the `.php` file directly from disk). |

---

## Project structure

```
student_crud/
├── db.php            # Database connection settings
├── index.php         # Home page: add student form + student list
├── edit.php          # Edit an existing student
├── delete.php         # Delete a student
├── Classes/
│   └── Student.php   # Student class with create/read/update/delete (CRUD) logic
└── style/
    └── style.css      # Page styling
```
