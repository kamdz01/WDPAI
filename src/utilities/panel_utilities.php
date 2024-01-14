<?php

function printNotes($userId) {
    global $NoteRepo;
    global $userId;
    $notes = $NoteRepo->getNotesByUserId($userId);
    foreach ($notes as $note) {
        echo '<div id="show-edit-form-btn" class="text-box" onclick="openEditModal(' . htmlspecialchars(json_encode($note)) . ')">';
        echo '<p id="show-edit-form-btn" class="show-context" onclick="event.stopPropagation(); openContextMenu(' . htmlspecialchars(json_encode($note)) . ')">⁝</p>';
        echo '<div class="context-menu" id="context-menu-' . htmlspecialchars($note['note_id']) . '">';
        if ($note['note_role_id'] == "1") {
            echo '<button type="button" class="delete-button" data-note-id="' . htmlspecialchars($note['note_id']) . '">Delete</button>';
        }
        else {
            echo '<button type="button" class="leave-button" data-note-id="' . htmlspecialchars($note['note_id']) . '">Leave</button>';
        }
        echo '<button type="button" class="share-button" data-role-id="' . htmlspecialchars($note['note_role_id']) . '" data-note-id="' . htmlspecialchars($note['note_id']) . '">Share</button>';
        echo '</div>';
        echo '<h2>' . htmlspecialchars($note['note_title']) . '</h2>';
        echo '<p class="note-content-p">' . nl2br(htmlspecialchars($note['note_content'])) . '</p>';
        echo '</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'delete':
            $noteId = $_POST['note_id'] ?? null;
            echo json_encode(['success' => $noteId && $NoteRepo->deleteNote($noteId)]);
            break;

        case 'leave':
            global $userId;
            $noteId = $_POST['note_id'] ?? null;
            echo json_encode(['success' => $noteId && $NoteRepo->deleteUserFromNote($userId, $noteId)]);
            break;

        case 'add':
            $noteTitle = $_POST['note_title'] ?? '';
            $noteContent = $_POST['note_content'] ?? '';
            $newNoteId = $NoteRepo->addNewNoteWithRole($_SESSION["userId"], $noteTitle, $noteContent, "1");
            echo $newNoteId ? json_encode(['success' => true, 'message' => 'Note added successfully!', 'note_id' => $newNoteId])
                           : json_encode(['success' => false, 'message' => 'Failed to add note.']);
            break;

        case 'fetch_roles':
            $roles = $NoteRepo->getNoteRolesFromDatabase();
            echo json_encode($roles);
            break;

        case 'modify':
            $noteId = $_POST['note_id'] ?? '';
            $newTitle = $_POST['note_title'] ?? '';
            $newContent = $_POST['note_content'] ?? '';
            echo $NoteRepo->modifyNote($noteId, $newTitle, $newContent) ? json_encode(['success' => true, 'message' => 'Note updated successfully!'])
                                                                       : json_encode(['success' => false, 'message' => 'Failed to update note.']);
            break;

        case 'getSharedUsers':
            $noteId = $_POST['noteId'] ?? '';
            $users = $NoteRepo->getUsersAssignedToNote($noteId);
            echo json_encode($users);
            break;

        case 'addUserToNote':
            $noteId = $_POST['noteId'] ?? '';
            $userToShare = $_POST['userToShare'] ?? '';
            $userIdToShare = $NoteRepo->getUserIdByUsername($userToShare);
            $role = $_POST['role'] ?? '';
            echo $NoteRepo->addOrUpdateUserNote($userIdToShare, $noteId, $role) ? json_encode(['success' => true, 'message' => 'Note updated successfully!'])
                                                                             : json_encode(['success' => false, 'message' => 'Failed to update note.']);
            break;

        case 'deleteUserFromNote':
            $noteId = $_POST['noteId'] ?? null;
            $userToDelete = $_POST['userToDelete'] ?? null;
            $userIdToDelete = $NoteRepo->getUserIdByUsername($userToDelete);
            echo json_encode(['success' => $noteId && $NoteRepo->deleteUserFromNote($userIdToDelete, $noteId)]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    exit;
}


?>