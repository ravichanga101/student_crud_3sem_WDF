<?php
const DB_SERVER = "localhost";
const DB_NAME = "student_db";
const DB_USERNAME = "root";
const DB_PASSWORD = "";

$conn = new PDO(
    "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    DB_USERNAME,
    DB_PASSWORD
);
?>