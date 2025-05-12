<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");
if ($conn->connect_error) {
    die("Connexion error: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$title = $conn->real_escape_string($_POST['title']);
$author = $conn->real_escape_string($_POST['author']);
$year = intval($_POST['year']);
$genre = $conn->real_escape_string($_POST['genre']);

$sql = "UPDATE book SET title='$title', author='$author', `year`= $year, genre='$genre' WHERE id=$id";

if ($conn->query($sql)) {
    echo "$title . $author . $year . $genre . $id . Updated successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>