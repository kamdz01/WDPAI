<?php
require __DIR__ . '/../repository/NoteRepo.php';
session_start();
global $NoteRepo;
$NoteRepo = new NoteRepo();
global $userId;
$userId = $_SESSION["userId"];
function printNotes($userId) {
    global $NoteRepo;
    global $userId;
    $notes = $NoteRepo->getNotesByUserId($userId);
    foreach ($notes as $note) {
        echo '<div class="text-box">';
        echo '<h2>' . htmlspecialchars($note['note_title']) . '</h2>';
        echo '<p>' . nl2br(htmlspecialchars($note['note_content'])) . '</p>';
        if ($note['note_role_id'] == "1") {
            echo '<button type="button" class="delete-button" data-note-id="' . htmlspecialchars($note['note_id']) . '">Delete</button>';
        }
        echo '</div>';
    }
}

function test() {
    global $NoteRepo;
    $NoteRepo->addNewNoteWithRole("1", "test_auto", "content_auto", "1");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $noteId = $_POST['note_id'] ?? null;
    if ($noteId && $NoteRepo->deleteNote($noteId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $noteTitle = $_POST['note_title'] ?? '';
        $noteContent = $_POST['note_content'] ?? '';

        $newNoteId = $NoteRepo->addNewNoteWithRole($_SESSION["userId"], $noteTitle, $noteContent, "1");

        if ($newNoteId) {
            echo json_encode(['success' => true, 'message' => 'Note added successfully!', 'note_id' => $newNoteId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add note.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
    exit;
}

function test_del() {
    global $NoteRepo;
    return $NoteRepo->deleteNote("2");
}
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
    <div id="note-form-modal" class="modal">
        <div class="modal-content">
            <span id="close-modal-btn" class="close-btn">&times;</span>
            <form id="new-note-form">
                <input type="text" id="note-title" name="note_title" placeholder="Note Title" required>
                <textarea id="note-content" name="note_content" placeholder="Note Content" required></textarea>
                <input type="hidden" id="user-id" name="user_id" value="1"> 
                <input type="hidden" id="note-role-id" name="note_role_id" value="1"> 
                <input type="hidden" name="action" value="add"> 
                <button type="submit">Add Note</button>
            </form>
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
            <a href = "logout"><div>Logout</div></a>
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