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


 $sql = "INSERT INTO user (name, surname, age, email) VALUES ('$name', '$surname', $age, '$email')";


if ($conn->query($sql)) {
    echo "$name . $surname . $age . $email . $id . Updated successfully";
} else {
    echo "Error: " . $conn->error;
}










$conn->close();
?>