
<?php
require_once "db.php";
require_once "./Classes/Student.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student = new Student($conn);
    $student->delete($_POST["id"]);
}

header("Location: index.php");
exit;
?>
