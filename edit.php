
<?php
require_once "db.php";
require_once "./Classes/Student.php";

$student = new Student($conn);
$id = $_GET["id"];

$data = $student->getById($id);

if (!$data) {
    die("Student not found.");
}

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
        $student->update(
            $id, $name, $semester, $email, $password, $city
        );

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    
    <head>
    <title>Edit Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
</head> 
</head> 
<body>

<h1>Edit Student</h1>

<?php if ($message) { ?>
    <p class="message"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">
    Name:
    <input type="text" name="name"
           value="<?php echo $data["name"]; ?>" required><br><br>

    Semester:
    <select name="semester" required>
        <?php for ($i = 1; $i <= 8; $i++) { ?>
            <option value="<?php echo $i; ?>"
                <?php if ($data["semester"] == $i) echo "selected"; ?>>
                <?php echo $i; ?>
            </option>
        <?php } ?>
    </select><br><br>

    Email:
    <input type="email" name="email"
           value="<?php echo $data["email"]; ?>" required><br><br>

    New Password:
    <input type="password" name="password" required><br><br>

    Confirm Password:
    <input type="password" name="confirm" required><br><br>

    City:
    <input type="text" name="city"
           value="<?php echo $data["city"]; ?>" required><br><br>

    <button type="submit">Update Student</button>
</form>

<br>
<a href="index.php">Back to Student List</a>

</body>
</html>
