<?php
include_once 'database.php';
include_once 'person.php';

$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM persons WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $person = $result->fetch_assoc();
    $stmt->close();
    echo json_encode($person);
}

$conn->close();
?>