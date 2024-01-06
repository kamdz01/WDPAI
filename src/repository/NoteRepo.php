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
                WHERE un.user_id = :userId
                ORDER BY n.last_modified DESC;
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

    function modifyNote($noteId, $newTitle, $newContent) {

        $sql = "UPDATE notes SET note_title = :newTitle, note_content = :newContent WHERE note_id = :noteId";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':noteId', $noteId);

        $stmt->execute();

        return true;
    }

    function addOrUpdateUserNote($userId, $noteId, $noteRoleId) {

            // Check if the user-note relation already exists
            $checkSql = "SELECT * FROM user_notes WHERE user_id = :userId AND note_id = :noteId";
            $checkStmt = $this->database->connect()->prepare($checkSql);
            $checkStmt->bindParam(':userId', $userId);
            $checkStmt->bindParam(':noteId', $noteId);
            $checkStmt->execute();
    
            if ($checkStmt->fetch()) {
                // If the relation exists, update it
                $updateSql = "UPDATE user_notes SET note_role_id = :noteRoleId WHERE user_id = :userId AND note_id = :noteId";
                $updateStmt = $this->database->connect()->prepare($updateSql);
            } else {
                // If the relation does not exist, insert a new one
                $updateSql = "INSERT INTO user_notes (user_id, note_id, note_role_id) VALUES (:userId, :noteId, :noteRoleId)";
                $updateStmt = $this->database->connect()->prepare($updateSql);
            }
    
            $updateStmt->bindParam(':userId', $userId);
            $updateStmt->bindParam(':noteId', $noteId);
            $updateStmt->bindParam(':noteRoleId', $noteRoleId);
            $updateStmt->execute();
    
            return true;
    }
    
    function deleteUserFromNote($userId, $noteId) {

            $sql = "DELETE FROM user_notes WHERE user_id = :userId AND note_id = :noteId";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':noteId', $noteId);
    
            $stmt->execute();
    
            return true;
    }

    function getNoteRolesFromDatabase() {

            $stmt = $this->database->connect()->query("SELECT note_role_id, note_role_name FROM note_roles");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $roles;
    }

    function getUsersAssignedToNote($noteId) {
            $sql = "SELECT u.user_id, u.login, r.note_role_name
                    FROM users u
                    INNER JOIN user_notes un ON u.user_id = un.user_id
                    INNER JOIN note_roles r ON un.note_role_id = r.note_role_id
                    WHERE un.note_id = :noteId";
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':noteId', $noteId, PDO::PARAM_INT);
    
            $stmt->execute();
    
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $users; // Return the list of users and their roles
    }
    
    function getUserIdByUsername($username) {

        $sql = "SELECT user_id FROM users WHERE login = :username";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['user_id'] : null;
    }
    
}
