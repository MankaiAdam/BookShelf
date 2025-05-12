<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");
if ($conn->connect_error) {
    die("Connexion error: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$name = $conn->real_escape_string($_POST['name']);
$surname = $conn->real_escape_string($_POST['surname']);
$age = intval($_POST['age']);
$email = $conn->real_escape_string($_POST['email']);

$sql = "UPDATE user SET name='$name', surname='$surname', `age`= $age, email='$email' WHERE id=$id";

if ($conn->query($sql)) {
    echo "$name . $surname . $age . $email . $id . Updated successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
