<?php
class Student
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create
    public function create($name, $semester, $email, $password, $city)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO students
                (name, semester, email, password, city)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $semester, $email, $password, $city);

        return $stmt->execute();
    }

    // Read
    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM students");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Read one student
    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Update
    public function update($id, $name, $semester, $email, $password, $city)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE students
                SET name = ?, semester = ?, email = ?,
                    password = ?, city = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $semester, $email, $password, $city, $id);

        return $stmt->execute();
    }

    // Delete
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>