<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $NoteRepo;
    switch ($_POST['action']) {
        case 'searchUser':
            $input = $_POST['input'] ?? '';
            $user = $UserRepo->searchUserByUsernameOrEmail($input);
            echo json_encode($user);
            break;

        case 'updateRole':
            $username = $_POST['username'] ?? '';
            $newRoleId = $_POST['newRoleId'] ?? '';
            $userId = $UserRepo->getUserIdByUsername($username);
            $result = $UserRepo->changeUserRole($userId, $newRoleId);
            echo json_encode(['success' => $result]);
            break;

        case 'fetchRoles':
            $roles = $UserRepo->getAllUserRoles();
            echo json_encode($roles);
            break;

        case 'deleteUser':
            $username = $_POST['username'] ?? '';
            $userId = $UserRepo->getUserIdByUsername($username);
            $result = $UserRepo->deleteUserById($userId);
            echo json_encode(['success' => $result]);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
    exit;
}
?>