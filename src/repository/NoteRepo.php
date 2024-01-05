<?php
require_once 'Repository.php';

class NoteRepo extends Repository
{
    public function getNotesByUserId($userId)
    {
        $result = [];
        $stmt = $this->database->connect()->prepare(
            '
            SELECT n.note_id, n.note_title, n.note_content, n.creation_date, n.last_modified , un.note_role_id
                FROM notes n 
                JOIN user_notes un ON n.note_id = un.note_id 
                WHERE un.user_id = :userId;
            '
        );

        $stmt->execute(['userId' => $userId]);

        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $notes;
    }

    public function addNewNoteWithRole($userId, $noteTitle, $noteContent, $noteRoleId) {
        $sqlNote = "INSERT INTO notes (note_title, note_content) VALUES (:noteTitle, :noteContent) RETURNING note_id";
        $stmtNote = $this->database->connect()->prepare($sqlNote);
        $stmtNote->bindParam(':noteTitle', $noteTitle);
        $stmtNote->bindParam(':noteContent', $noteContent);
        $stmtNote->execute();

        $noteId = $stmtNote->fetch(PDO::FETCH_ASSOC)['note_id'];

        $sqlUserNote = "INSERT INTO user_notes (user_id, note_id, note_role_id) VALUES (:userId, :noteId, :noteRoleId)";
        $stmtUserNote = $this->database->connect()->prepare($sqlUserNote);
        $stmtUserNote->bindParam(':userId', $userId);
        $stmtUserNote->bindParam(':noteId', $noteId);
        $stmtUserNote->bindParam(':noteRoleId', $noteRoleId);
        $stmtUserNote->execute();

        return $noteId;
    }

    function deleteNote($noteId) {

        $sqlUserNotes = "DELETE FROM user_notes WHERE note_id = :noteId";
        $stmtUserNotes = $this->database->connect()->prepare($sqlUserNotes);
        $stmtUserNotes->bindParam(':noteId', $noteId);
        $stmtUserNotes->execute();

        $sqlNote = "DELETE FROM notes WHERE note_id = :noteId";
        $stmtNote = $this->database->connect()->prepare($sqlNote);
        $stmtNote->bindParam(':noteId', $noteId);
        $stmtNote->execute();

        return true;
    }
}
