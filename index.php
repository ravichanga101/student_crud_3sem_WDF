
<?php
require_once "db.php";
require_once "./Classes/Student.php";

$student = new Student($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $semester = $_POST["semester"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];
    $city = $_POST["city"];

    if ($password != $confirm) {
        $message = "Passwords do not match!";
    } else {
        $student->create(
            $name, $semester, $email, $password, $city
        );

        header("Location: index.php");
        exit;
    }
}

$students = $student->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    
    <head>
    <title>Student Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">

</head>
</head>
<body>

<h1>Student Management System</h1>

<h2>Add Student</h2>

<?php if ($message) { ?>
    <p class="message"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">
    Name:
    <input type="text" name="name" required><br><br>

    Semester:
    <select name="semester" required>
        <option value="">Select</option>
        <?php for ($i = 1; $i <= 8; $i++) { ?>
            <option value="<?php echo $i; ?>">
                <?php echo $i; ?>
            </option>
        <?php } ?>
    </select><br><br>

    Email:
    <input type="email" name="email" required><br><br>

    Password:
    <input type="password" name="password" required><br><br>

    Confirm Password:
    <input type="password" name="confirm" required><br><br>

    City:
    <input type="text" name="city" required><br><br>

    <button type="submit">Add Student</button>
</form>

<hr>

<h2>Student List</h2>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Semester</th>
    <th>Email</th>
    <th>City</th>
    <th>Actions</th>
</tr>

<?php foreach ($students as $s) { ?>
<tr>
    <td><?php echo $s["id"]; ?></td>
    <td><?php echo $s["name"]; ?></td>
    <td><?php echo $s["semester"]; ?></td>
    <td><?php echo $s["email"]; ?></td>
    <td><?php echo $s["city"]; ?></td>
    <td>
        <a href="edit.php?id=<?php echo $s["id"]; ?>">Edit</a>

        <form method="POST" action="delete.php"
              style="display:inline"
              onsubmit="return confirm('Delete student?');">
            <input type="hidden" name="id"
                   value="<?php echo $s["id"]; ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
