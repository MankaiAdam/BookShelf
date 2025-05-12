<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$books = [];
$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : "";
$sql = "SELECT * FROM book WHERE title like '%$search%'";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $books[] = $row;
}
$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1.0">
    <title>Books</title>
</head>
<body>
    <div class="view-container">
        <h1>Books</h1>
        <form action="getBooks.php" method="GET">
            <input type="text" class="searchbar" name="search" placeholder="Search...">
            <input type="submit" class="searchbtn" value="Rechercher">
        </form>
        <div class="list-container">

        <div class="add-btn" >
                <p>+</p>
        </div>
            <div class="header-container">
                <div class="header" style="width: 350px">Title</div>
                <div class="header" style="width: 200px">Author</div>
                <div class="header" style="width: 100px">Year</div>
                <div class="header" style="width: 100px">Genre</div>
                <div class="header" style="width: 60px">Actions</div>
            </div>
            <?php if (!empty($books)): ?>
                <ul class="list-view">
                    <?php foreach ($books as $book): ?>
                        <li data-id="<?= $book['id'] ?>" class="list-item">
                            <div class= "title" style="width: 350px"> <?= htmlspecialchars($book['title']) ?> </div>
                            <div class= "author" style="width: 200px"> <?= htmlspecialchars($book['author']) ?> </div>
                            <div class= "year" style="width: 100px"> <?= htmlspecialchars($book['year']) ?> </div>
                            <div class= "genre" style="width: 100px"> <?= htmlspecialchars($book['genre']) ?> </div>
                            <div class= "actions" style="width: 60px">
                                <div style="width: 30px">
                                    <button class="edit-btn" style="color:blue">E</button>
                                </div>
                                <div style="width: 30px">
                                    <form action="deleteBook.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                        <button type="submit" style="color:red">D</button>
                                    </form>
                                </div>
                            </div>
                        </li>   
                    <?php endforeach; ?>
                </ul>
            <?php elseif (isset($_GET['search'])): ?>
                <p>No book was found.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("hey");
            
            const li = this.closest('.list-item');
            const actionsdiv = this.closest('.actions');

            const titleDiv = li.querySelector('.title');
            const authorDiv = li.querySelector('.author');
            const yearDiv = li.querySelector('.year');
            const genreDiv = li.querySelector('.genre');

            const title = titleDiv.textContent.trim();
            const author = authorDiv.textContent.trim();
            const year = yearDiv.textContent.trim();
            const genre = genreDiv.textContent.trim();

            titleDiv.innerHTML = `<input type="text" style="width: 345px" value="${title}" class="edit-title">`;
            authorDiv.innerHTML = `<input type="text" style="width: 195px" value="${author}" class="edit-author">`;
            yearDiv.innerHTML = `<input type="number" style="width: 95px" value="${year}" class="edit-year">`;
            genreDiv.innerHTML = `<input type="text" style="width: 95px" value="${genre}" class="edit-genre">`;

            actionsdiv.innerHTML = `<button class="save-btn" style="width: 30px">S</button><button class="cancel-btn" style="width: 30px">C</button>`;

            li.querySelector('.save-btn').addEventListener('click', function() {
                const updatedTitle = li.querySelector('.edit-title').value;
                const updatedAuthor = li.querySelector('.edit-author').value;
                const updatedYear = li.querySelector('.edit-year').value;
                const updatedGenre = li.querySelector('.edit-genre').value;
                const id = li.dataset.id;

                // Send the update with AJAX
                fetch('editbook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&title=${encodeURIComponent(updatedTitle)}&author=${encodeURIComponent(updatedAuthor)}&year=${updatedYear}&genre=${encodeURIComponent(updatedGenre)}`
                })
                .then(response => response.text())
                .then(data => {
                    // Reload the page or just update the fields
                    location.reload();
                });
            });

            li.querySelector('.cancel-btn').addEventListener('click', function() {
                location.reload();
            });
        });
    });

    document.querySelector('.add-btn').addEventListener('click', function() {
        window.location.href = 'addBook.php';
            




    });





    </script>
</body>
