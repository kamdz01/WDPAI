<?php
require_once __DIR__ . '/../repository/NoteRepo.php';
require_once __DIR__ . '/../repository/UserRepo.php';
session_start();
if(!isset($_SESSION['userId'])) {
    header('Location: /');
}
global $userId;
$userId = $_SESSION["userId"];

global $UserRepo;
$UserRepo = new UserRepo();
if ($UserRepo->getUserRoleByUserId($userId) > 1 || $UserRepo->getUserRoleByUserId($userId) === null) {
    header('Location: /panel');
}

global $NoteRepo;
$NoteRepo = new NoteRepo();

require __DIR__ . '/../utilities/admin_utilities.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Note-taking Application</title>
<link rel = "stylesheet" href = "../public/css/admin.css"/>
<script src="../src/js/admin.js"></script>
</head>
<body>
    <div class="topbar">
        <div class="search-box">
            <input type="text" placeholder="Search...">
        </div>
        <div class="topbar-icons">
            <a href="panel"><button>🏠</button></a>
            <button id="show-form-btn">➕</button>
            <button>❤️</button>
            <button>⚙️</button>
        </div>
        <div class="topbar-icons-right">
        <a href = "admin"><div class="logout-btn">Admin</div></a>
            <a href = "logout"><div class="logout-btn">Logout</div></a>
        </div>
    </div>

<div class="container">
<div id="admin-panel" class="admin-panel">
    <h2>Update User Role</h2>

    <!-- User Search Section -->
    <div id="user-search-section">
        <input type="text" id="search-input" class="search-input" placeholder="Username or email">
        <button id="search-btn" class="search-btn">Search</button>
    </div>

    <!-- User Role Update Section -->
    <div id="user-update-section" class="user-update-section" style="display:none;">
        <p>Username: <span id="user-username"></span></p>
        <p>Email: <span id="user-email"></span></p>
        <p>Current Role: <span id="current-role"></span></p>
        <div>
            <label for="role-select">New Role:</label>
            <select id="role-select" class="role-select">
            </select>
            <button id="update-role-btn" class="update-role-btn">Update Role</button>
        </div>
        <div>
            <button id="delete-user-btn" class="delete-user-btn">Delete User</button>
        </div>
    </div>
</div>
</div>
    </main>
</div>
</body>
</html>