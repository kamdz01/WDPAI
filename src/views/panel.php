<?php
require __DIR__ . '/../repository/NoteRepo.php';
session_start();
if(!isset($_SESSION['userId'])) {
    header('Location: /');
}
global $userId;
$userId = $_SESSION["userId"];
global $NoteRepo;
$NoteRepo = new NoteRepo();

require __DIR__ . '/../utilities/panel_utilities.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Note-taking Application</title>
<link rel = "stylesheet" href = "../public/css/panel.css"/>
<script src="../src/js/panel.js"></script>
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

    <!-- Edit Note Modal -->
    <div id="edit-note-modal" class="modal">
        <div class="modal-content">
        <button id="update-note-btn" class="submit-btn">Update Note</button>
        <span id="close-edit-modal-btn" class="close-btn">&times;</span>
            <input type="hidden" id="edit-note-id">
            <input type="text" id="edit-note-title" class="note-title" placeholder="Note Title" required>
            <textarea rows="30" id="edit-note-content" class="note-content" placeholder="Note Content" required></textarea>
        </div>
    </div>


    <div class="topbar">
        <div class="search-box">
            <input type="text" placeholder="Search...">
        </div>
        <div class="topbar-icons">
            <button>🏠</button>
            <button id="show-form-btn">➕</button>
            <button>❤️</button>
            <button>⚙️</button>
        </div>
        <div class="topbar-icons-right">
            <button>🏠</button>
            <a href = "logout"><div class="logout-btn">Logout</div></a>
        </div>
    </div>
<div class="container">
    <main class="content">
        <!-- Full note content -->
        <div class="note-full">
            <?php
            printNotes($userId);
            //test();
            //test_del();
            ?>
        </div>
    </main>
</div>
</body>
</html>