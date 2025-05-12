<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");
if ($conn->connect_error) {
    die("Connexion error: " . $conn->connect_error);
}
















$conn->close();
?>
