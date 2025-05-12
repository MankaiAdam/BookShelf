<?php
$conn = new mysqli("localhost", "root", "", "bookshelf");

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$users = [];
$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : "";
$sql = "SELECT * FROM user WHERE name LIKE '%$search%'"; // Fixed column name for the SQL query
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $users[] = $row;
}
$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1.0">
    <title>Users</title>
</head>
<body>
    <div class="view-container">
        <h1>Users</h1>
        <form action="getUsers.php" method="GET">
            <input type="text" class="searchbar" name="search" placeholder="Search...">
            <input type="submit" class="searchbtn" value="Rechercher">
        </form>
        <div class="list-container">
            <div class="header-container">
                <!-- Displaying headers -->
                <div class="header" style="width: 200px">Name</div>
                <div class="header" style="width: 200px">Surname</div>
                <div class="header" style="width: 350px">Email</div>
                <div class="header" style="width: 50px">Age</div>
                <div class="header" style="width: 60px">Actions</div>
            </div>
            <?php if (!empty($users)): ?>
                <ul class="list-view">
                    <?php foreach ($users as $user): ?>
                        <li data-id="<?= $user['id'] ?>" class="list-item">
                            <div class="name" style="width: 200px"><?= htmlspecialchars($user['name']) ?></div>
                            <div class="surname" style="width: 200px"><?= htmlspecialchars($user['surname']) ?></div>
                            <div class="email" style="width: 350px"><?= htmlspecialchars($user['email']) ?></div>
                            <div class="age" style="width: 50px"><?= htmlspecialchars($user['age']) ?></div>
                            <div class="actions" style="width: 60px">
                                <div style="width: 30px">
                                    <button class="edit-btn" style="color:blue">E</button>
                                </div>
                                <div style="width: 30px">
                                    <form action="deleteuser.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" style="color:red">D</button>
                                    </form>
                                </div>
                            </div>
                        </li>   
                    <?php endforeach; ?>
                </ul>
            <?php elseif (isset($_GET['search'])): ?>
                <p>No User was found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const li = this.closest('.list-item');
            const actionsdiv = this.closest('.actions');

            const nameDiv = li.querySelector('.name');
            const surnameDiv = li.querySelector('.surname');
            const emailDiv = li.querySelector('.email');
            const ageDiv = li.querySelector('.age');

            const name = nameDiv.textContent.trim();
            const surname = surnameDiv.textContent.trim();
            const age = ageDiv.textContent.trim();
            const email = emailDiv.textContent.trim();

            // Convert divs to input fields
            nameDiv.innerHTML = `<input type="text" style="width: 195px" value="${name}" class="edit-name">`;
            surnameDiv.innerHTML = `<input type="text" style="width: 195px" value="${surname}" class="edit-surname">`;
            emailDiv.innerHTML = `<input type="text" style="width: 345px" value="${email}" class="edit-email">`;
            ageDiv.innerHTML = `<input type="number" style="width: 45px" value="${age}" class="edit-age">`;
            

            // Update actions with Save and Cancel buttons
            actionsdiv.innerHTML = `<button class="save-btn" style="width: 30px">S</button><button class="cancel-btn" style="width: 30px">C</button>`;

            // Save the changes when Save button is clicked
            li.querySelector('.save-btn').addEventListener('click', function() {
                const updatedName = li.querySelector('.edit-name').value;
                const updatedSurname = li.querySelector('.edit-surname').value;
                const updatedAge = li.querySelector('.edit-age').value;
                const updatedEmail = li.querySelector('.edit-email').value;
                const id = li.dataset.id;

                // Send the update with AJAX
                fetch('edituser.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&name=${encodeURIComponent(updatedName)}&surname=${encodeURIComponent(updatedSurname)}&age=${updatedAge}&email=${encodeURIComponent(updatedEmail)}`
                })
                .then(response => response.text())
                .then(data => {
                    location.reload(); // Reload the page to reflect the changes
                });
            });

            // Cancel the edit and reload the page
            li.querySelector('.cancel-btn').addEventListener('click', function() {
                location.reload();
            });
        });
    });
    </script>
</body>
