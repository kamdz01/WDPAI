<?php

function printNotes($userId) {
    global $NoteRepo;
    global $userId;
    $notes = $NoteRepo->getNotesByUserId($userId);
    foreach ($notes as $note) {
        echo '<div id="show-edit-form-btn" class="text-box" onclick="openEditModal(' . htmlspecialchars(json_encode($note)) . ')">';
        if ($note['note_role_id'] == "1") {
            echo '<button type="button" class="delete-button" data-note-id="' . htmlspecialchars($note['note_id']) . '">Delete</button>';
        }
        echo '<h2>' . htmlspecialchars($note['note_title']) . '</h2>';
        echo '<p>' . nl2br(htmlspecialchars($note['note_content'])) . '</p>';
        echo '<p>' . nl2br(htmlspecialchars($note['note_id'])) . '</p>';
        echo '</div>';
    }
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
        $noteTitle = $_POST['note_title'] ?? '';
        $noteContent = $_POST['note_content'] ?? '';

        $newNoteId = $NoteRepo->addNewNoteWithRole($_SESSION["userId"], $noteTitle, $noteContent, "1");

        if ($newNoteId) {
            echo json_encode(['success' => true, 'message' => 'Note added successfully!', 'note_id' => $newNoteId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add note.']);
        }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'modify') {
    $noteId = $_POST['note_id'] ?? '';
    $newTitle = $_POST['note_title'] ?? '';
    $newContent = $_POST['note_content'] ?? '';

    if ($NoteRepo->modifyNote($noteId, $newTitle, $newContent)) {
        echo json_encode(['success' => true, 'message' => 'Note updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update note.']);
    }
    exit;
}

?>