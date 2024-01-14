<?php
require __DIR__ . '/../repository/NoteRepo.php';
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
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
<script src="../src/js/addNote.js" defer></script>
<script src="../src/js/panel.js" defer></script>
</head>
<body>

    <?php
        include 'topbar.php';
    ?>

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

    <!-- Share Note Modal -->
    <div id="share-note-modal" class="modal">
        <div class="modal-content">
            <span id="close-share-modal-btn" class="close-btn">&times;</span>
            <div class="share-form">
                <input type="hidden" id="share-note-id">
                <label for="user-to-share">Share note with:</label>
                <input type="text" id="user-to-share" class="user-to-share" placeholder="Username">
                <label for="share-note-role">Role:</label>
                <select id="share-note-role" class="share-note-role">
                    <!-- List of roles will be populated here -->
                </select>
                <button id="share-note-btn" class="submit-btn">Share Note</button>
            </div>
            <div id="current-shared-users" class="current-shared-users">
                <!-- List of current shared users will be populated here -->
            </div>
        </div>
    </div>

<div class="container">
    <main class="content">
        <!-- Full note content -->
        <div class="note-full">
            <?php
            printNotes($userId);
            ?>
        </div>
    </main>
</div>
</body>
</html>