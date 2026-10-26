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

        return $stmt->execute([
            $name, $semester, $email, $password, $city
        ]);
    }

    // Read
    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM students");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read one student
    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
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

        return $stmt->execute([
            $name, $semester, $email, $password, $city, $id
        ]);
    }

    // Delete
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }
}
?>