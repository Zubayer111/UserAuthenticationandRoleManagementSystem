<?php
session_start();

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roles = json_decode(file_get_contents('roles.json'), true);
    $roles[] = $_POST['role'];
    file_put_contents('roles.json', json_encode($roles));
    header('Location: role_management.php');
    exit();
}

$roles = json_decode(file_get_contents('roles.json'), true);

if (isset($_GET['delete'])) {
    $roleToDelete = $_GET['delete'];
    $roles = array_diff($roles, [$roleToDelete]);
    file_put_contents('roles.json', json_encode(array_values($roles)));
    header('Location: role_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Role Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Role Management</h2>
        <ul class="list-group">
            <?php foreach ($roles as $role) : ?>
                <li class="list-group-item"><?= $role ?> <a href="?delete=<?= $role ?>" class="btn btn-danger btn-sm float-right">Delete</a></li>
            <?php endforeach; ?>
        </ul>
        <form class="mt-3" method="post">
            <div class="form-group">
                <label for="newRole">New Role</label>
                <input type="text" class="form-control" id="newRole" name="role" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Role</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <!-- Add Bootstrap's JS scripts at the end of the <body> section -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

