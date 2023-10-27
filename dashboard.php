<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$users = json_decode(file_get_contents('users_data/users.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 'admin' && isset($_POST['delete'])) {
    // Handle user deletion if submitted
    $usernameToDelete = $_POST['delete'];

    foreach ($users as $key => $u) {
        if ($u['email'] === $usernameToDelete) {
            unset($users[$key]);
            break;
        }
    }
    $users = array_values($users);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Welcome, <?= $user['username'] ?></h2>
            <p class="text-center">Role: <?= $user['role'] ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php if ($user['role'] === 'admin' ) : ?>
                <a href="role_management.php" class="btn btn-secondary">Add Role</a>
            <?php endif; ?>
        </div>
    </div>

    
        <div class="container mt-5">
            <h2 class="text-center">User List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <?php if ($user['role'] === 'admin' || $user['role'] === 'manager') : ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($users as $userData) : ?>
                        
                        <tr>
                            <td><?= $userData['username'] ?></td>
                            <td><?= $userData['email'] ?></td>
                            <td><?= $userData['role'] ?></td>
                            <?php if ($user['role'] === 'admin' || $user['role'] === 'manager') : ?>
                                <td>
                                    <a href="edit_user_role.php" class="btn btn-info btn-sm">Edit</a>
                                    <?php if ($user['role'] === 'admin' ) : ?>
                                    <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete" value="<?= $userData['email'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
