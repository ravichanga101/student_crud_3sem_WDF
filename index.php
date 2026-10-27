
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
        $profilePicture = null;
        try {
            $profilePicture = $student->storeProfilePicture($_FILES["profile_picture"] ?? []);
        } catch (RuntimeException $error) {
            $message = $error->getMessage();
        }

        if ($message === "") {
            $created = false;
            try {
                $created = $student->create($name, $semester, $email, $password, $city, $profilePicture);
            } finally {
                if (!$created && $profilePicture !== null) {
                    $student->removeProfilePicture($profilePicture);
                }
            }

            if ($created) {
                header("Location: index.php");
                exit;
            }
            $message = "Could not save the student.";
        }
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
    <link rel="stylesheet" href="./style/style.css?v=<?php echo filemtime(__DIR__ . '/style/style.css'); ?>">

</head>
</head>
<body>

<h1>Student Management System</h1>

<h2>Add Student</h2>

<?php if ($message) { ?>
    <p class="message"><?php echo $message; ?></p>
<?php } ?>

<form method="POST" enctype="multipart/form-data">
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

    Profile Picture:
    <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp"><br><br>

    <button type="submit">Add Student</button>
</form>

<hr>

<h2>Student List</h2>

<table class="student-list" border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Semester</th>
    <th>Email</th>
    <th>City</th>
    <th>Profile Picture</th>
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
        <?php if (!empty($s["profile_picture"])) { ?>
            <img class="profile-picture" src="uploads/<?php echo htmlspecialchars($s["profile_picture"], ENT_QUOTES, "UTF-8"); ?>" alt="Profile picture of <?php echo htmlspecialchars($s["name"], ENT_QUOTES, "UTF-8"); ?>">
        <?php } else { ?>
            No picture
        <?php } ?>
    </td>
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
