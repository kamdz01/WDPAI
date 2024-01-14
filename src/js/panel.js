// MODALS UI -------------------------------------------------------------------------

const edit_note_content = document.getElementById('edit-note-content');

edit_note_content.addEventListener('input', () => {
    const numberOfRows = edit_note_content.value.split('\n').length;
    edit_note_content.rows = Math.min(numberOfRows + 3, 30)
});

document.getElementById('close-edit-modal-btn').onclick = function () {
    document.getElementById('edit-note-modal').classList.remove('show-modal');
}

document.getElementById('close-share-modal-btn').onclick = function () {
    document.getElementById('share-note-modal').classList.remove('show-modal');
    document.getElementById('current-shared-users').innerHTML = '<h4>Current Shared Users:</h4>';
}

window.openEditModal = function (note) {
    document.getElementById('edit-note-id').value = note.note_id;
    document.getElementById('edit-note-title').value = note.note_title;
    document.getElementById('edit-note-content').value = note.note_content;
    document.getElementById('edit-note-modal').classList.add('show-modal');
    const numberOfRows = edit_note_content.value.split('\n').length;
    edit_note_content.rows = Math.min(numberOfRows + 3, 30)
};

window.openContextMenu = function (note) {
    const noteId = note.note_id;
    const contextMenu = document.getElementById('context-menu-' + noteId);
    if (contextMenu.style.display == 'flex') {
        contextMenu.style.display = 'none';
    }
    else {
        contextMenu.style.display = 'flex';
    }
};

window.onclick = function (event) {
    const addModal = document.getElementById('note-form-modal');
    const editModal = document.getElementById('edit-note-modal');
    const shareModal = document.getElementById('share-note-modal');

    if (event.target === addModal) {
        document.getElementById('note-form-modal').classList.remove('show-modal');
    }
    if (event.target === editModal) {
        document.getElementById('edit-note-modal').classList.remove('show-modal');
    }
    if (event.target === shareModal) {
        document.getElementById('share-note-modal').classList.remove('show-modal');
        document.getElementById('current-shared-users').innerHTML = '<h4>Current Shared Users:</h4>';
    }

    document.getElementById('person-context').classList.remove('show-modal');

    document.querySelectorAll('.context-menu').forEach(menu => {
        menu.style.display = 'none';
    });
};

// MODALS UI END ---------------------------------------------------------------------


// AJAX API --------------------------------------------------------------------------

document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation();
        const noteId = this.getAttribute('data-note-id');
        if (confirm('Are you sure you want to delete this note?')) {
            fetch('panel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=delete&note_id=' + noteId
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.parentElement.parentElement.remove();
                    } else {
                        alert('There was an error deleting the note.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
});

document.querySelectorAll('.leave-button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation();
        const noteId = this.getAttribute('data-note-id');
        if (confirm('Are you sure you want to leave this note?')) {
            fetch('panel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=leave&note_id=' + noteId
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.parentElement.parentElement.remove();
                    } else {
                        alert('There was an error leaving the note.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
});

document.getElementById('update-note-btn').addEventListener('click', () => {
    const noteId = document.getElementById('edit-note-id').value;
    const noteTitle = document.getElementById('edit-note-title').value;
    const noteContent = document.getElementById('edit-note-content').value;

    const data = new URLSearchParams();
    data.append('action', 'modify');
    data.append('note_id', noteId);
    data.append('note_title', noteTitle);
    data.append('note_content', noteContent);

    fetch('panel', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                console.error('Failed to update note');
            }
        })
        .catch(error => console.error('Error:', error));
});

document.getElementById('share-note-btn').addEventListener('click', () => {
    const noteId = document.getElementById('share-note-id').value;
    const userToShare = document.getElementById('user-to-share').value;
    const role = document.getElementById('share-note-role').value;

    fetch('panel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=addUserToNote&noteId=${noteId}&userToShare=${userToShare}&role=${role}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('User added to note successfully');
                // Update the UI
                const sharedUsersContainer = document.getElementById('current-shared-users');
                fetch('panel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=getSharedUsers&noteId=' + noteId
                })
                    .then(response => response.json())
                    .then(users => {
                        sharedUsersContainer.innerHTML = '<h4>Current Shared Users:</h4>';
                        users.forEach(user => {
                            sharedUsersContainer.innerHTML += `<div><h7>User ID: ${user.user_id}, Username: ${user.login}, Role: ${user.note_role_name}</h7> <button type="button" class="delete-user-button" data-note-id="${noteId}" data-username="${user.login}">Delete</button></div>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                console.error('Failed to add user to note');
            }
        })
        .catch(error => console.error('Error:', error));
});

document.querySelectorAll('.share-button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation();
        document.getElementById('share-note-modal').classList.add('show-modal');
        document.getElementById('share-note-id').value = this.getAttribute('data-note-id');
        const ownRole = this.getAttribute('data-role-id');
        fetch('panel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=fetch_roles'
        })
            .then(response => response.json())
            .then(roles => {
                roles = roles.filter(function (item) {
                    return item.note_role_id >= ownRole;
                })
                const rolesDropdown = document.getElementById('share-note-role');
                rolesDropdown.innerHTML = ''; // Clear existing options
                roles.forEach(role => {
                    rolesDropdown.innerHTML += `<option value="${role.note_role_id}">${role.note_role_name}</option>`;
                });
            })
            .catch(error => console.error('Error:', error));

        const sharedUsersContainer = document.getElementById('current-shared-users');
        const noteId = this.getAttribute('data-note-id');
        fetch('panel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getSharedUsers&noteId=' + noteId
        })
            .then(response => response.json())
            .then(users => {
                sharedUsersContainer.innerHTML = '<h4>Current Shared Users:</h4>';
                users.forEach(user => {
                    sharedUsersContainer.innerHTML += `<div><h7>User ID: ${user.user_id}, Username: ${user.login}, Role: ${user.note_role_name}</h7> <button type="button" class="delete-user-button" data-note-id="${noteId}" data-username="${user.login}">Delete</button></div>`;
                });
            })
            .catch(error => console.error('Error:', error));
    });
});


const sharedUsersContainer = document.getElementById('current-shared-users');

sharedUsersContainer.addEventListener('click', function (event) {
    if (event.target && event.target.classList.contains('delete-user-button')) {
        const noteId = event.target.getAttribute('data-note-id');
        const username = event.target.getAttribute('data-username');
        const userElement = event.target.parentElement;

        // Confirm before deleting
        if (confirm(`Are you sure you want to remove ${username} from this note?`)) {
            fetch('panel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=deleteUserFromNote&noteId=' + noteId + '&userToDelete=' + username
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        sharedUsersContainer.removeChild(userElement);
                    } else {
                        alert('There was an error deleting user from note.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    }
});
// AJAX API END ----------------------------------------------------------------------