<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");
if ($conn->connect_error) {
    die("Connexion error: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); 
    $sql = "DELETE FROM book WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: getBooks.php");
        exit();
    } else {
        echo "Erreur while Deleting : " . $conn->error;
    }
}

$conn->close();
?>