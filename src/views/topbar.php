<?php
require_once __DIR__ . '/../repository/NoteRepo.php';
require_once __DIR__ . '/../repository/UserRepo.php';
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION['userId'])) {
    header('Location: /');
}
global $userId;
$userId = $_SESSION["userId"];

global $UserRepo;
$UserRepo = new UserRepo();
if ($UserRepo->getUserRoleByUserId($userId) > 1 || $UserRepo->getUserRoleByUserId($userId) === null) {
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
<link rel = "stylesheet" href = "../public/css/addNote.css"/>
<link rel = "stylesheet" href = "../public/css/topbar.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>
        <!-- Add Note Modal -->
        <div id="note-form-modal" class="modal">
        <div class="modal-content">
            <form id="new-note-form" class="new-note-form">
            <button type="submit" class="submit-btn">Add Note</button>
            <span id="close-modal-btn" class="close-btn">&times;</span>
                <input type="text" id="note-title" class="note-title" name="note_title" placeholder="Note Title" required>
                <textarea rows="30" id="note-content" class="note-content" name="note_content" placeholder="Note Content" required></textarea>
                <input type="hidden" id="user-id" name="user_id" value="1"> 
                <input type="hidden" id="note-role-id" name="note_role_id" value="1"> 
                <input type="hidden" name="action" value="add">   
            </form>
        </div>
    </div>
    <div class="topbar">
        <div class="search-box">
            <input type="text" placeholder="Search...">
        </div>
        <div class="topbar-icons">
            <a href="panel"><span class="material-symbols-outlined">home</span></a>
            <a><span id="show-form-btn" class="material-symbols-outlined">add_circle</span></a>
            <!-- <button id="show-form-btn">➕</button> -->
            <a><span class="material-symbols-outlined">favorite</span></a>
            <a><span class="material-symbols-outlined">settings</span></a>
        </div>
        <div class="topbar-icons-right">
            <?php
            if ($UserRepo->getUserRoleByUserId($userId) === 1) {
                echo '<a href = "admin"><div class="logout-btn">Admin</div></a>';
            }
            ?>
            <a href = "logout"><div class="logout-btn">Logout</div></a>
        </div>
    </div>
</body>
</html>