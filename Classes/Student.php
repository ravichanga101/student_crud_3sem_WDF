<?php
class Student
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create
    public function create($name, $semester, $email, $password, $city, $profilePicture = null)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO students
                (name, semester, email, password, city, profile_picture)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $semester, $email, $password, $city, $profilePicture);

        return $stmt->execute();
    }

    public function storeProfilePicture($file)
    {
        if (!isset($file["error"]) || $file["error"] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file["error"] !== UPLOAD_ERR_OK) {
            throw new RuntimeException("The profile picture could not be uploaded.");
        }

        if ($file["size"] > 5 * 1024 * 1024) {
            throw new RuntimeException("The profile picture must be 5 MB or smaller.");
        }

        if (!is_uploaded_file($file["tmp_name"])) {
            throw new RuntimeException("The uploaded profile picture is invalid.");
        }

        $allowedTypes = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/gif" => "gif",
            "image/webp" => "webp",
        ];
        $imageInfo = getimagesize($file["tmp_name"]);
        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);

        if ($imageInfo === false || !isset($allowedTypes[$mimeType]) || $imageInfo["mime"] !== $mimeType) {
            throw new RuntimeException("Choose a valid JPEG, PNG, GIF, or WebP image.");
        }

        $fileName = bin2hex(random_bytes(16)) . "." . $allowedTypes[$mimeType];
        $uploadPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
        $destination = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir($uploadPath) || !is_writable($uploadPath)) {
            throw new RuntimeException("The uploads folder is not writable.");
        }

        if (!move_uploaded_file($file["tmp_name"], $destination)) {
            throw new RuntimeException("The profile picture could not be saved.");
        }

        return $fileName;
    }

    public function removeProfilePicture($fileName)
    {
        if (!preg_match('/\A[a-f0-9]{32}\.(jpg|png|gif|webp)\z/i', (string) $fileName)) {
            return false;
        }

        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $fileName;

        return !file_exists($path) || unlink($path);
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
    public function update($id, $name, $semester, $email, $password, $city, $profilePicture = null)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE students
                SET name = ?, semester = ?, email = ?,
                    password = ?, city = ?, profile_picture = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $semester, $email, $password, $city, $profilePicture, $id);

        return $stmt->execute();
    }

    // Delete
    public function delete($id)
    {
        $student = $this->getById($id);
        $stmt = $this->conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);

        $deleted = $stmt->execute();

        if ($deleted && $student && !empty($student["profile_picture"])) {
            $this->removeProfilePicture($student["profile_picture"]);
        }

        return $deleted;
    }
}
?>