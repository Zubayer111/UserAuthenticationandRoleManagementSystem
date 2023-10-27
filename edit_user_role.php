<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Assuming $users is an array of users with their details
$users = json_decode(file_get_contents('users_data/users.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 'admin') {
    // Handle role updates if submitted
    $username = $_POST['username'];
    $newRole = $_POST['newRole'];

    foreach ($users as &$u) {
        if ($u['username'] === $username) {
            $u['role'] = $newRole;
            break;
        }
    }

    // Save updated roles back to file
    file_put_contents('users_data/users.json', json_encode($users));
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
            <?php if ($user['role'] === 'admin') : ?>
                <a href="role_management.php" class="btn btn-secondary">Role Management</a>
            <?php endif; ?>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <?php if ($user['role'] === 'admin') : ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td><?= $u['username'] ?></td>
                            <td><?= $u['email'] ?></td>
                            <td><?= $u['role'] ?></td>
                            <?php if ($user['role'] === 'admin') : ?>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="username" value="<?= $u['username'] ?>">
                                        <div class="form-group">
                                            <select class="form-control" name="newRole">
                                                <option value="admin" <?= ($u['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                                <option value="manager" <?= ($u['role'] === 'manager') ? 'selected' : '' ?>>Manager</option>
                                                <option value="user" <?= ($u['role'] === 'user') ? 'selected' : '' ?>>User</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Role</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
