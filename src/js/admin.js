document.getElementById('search-btn').addEventListener('click', function () {
    const input = document.getElementById('search-input').value;

    fetch('admin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=searchUser&input=' + encodeURIComponent(input)
    })
        .then(response => response.json())
        .then(user => {
            if (user) {
                document.getElementById('user-username').textContent = user.login;
                document.getElementById('user-email').textContent = user.email;
                document.getElementById('current-role').textContent = user.role_name;
                document.getElementById('user-update-section').style.display = 'block';
            } else {
                alert('User not found');
                document.getElementById('user-update-section').style.display = 'none';
            }
        })
        .catch(error => console.error('Error:', error));
});

document.getElementById('update-role-btn').addEventListener('click', function () {
    const username = document.getElementById('user-username').textContent;
    var sel = document.getElementById('role-select');
    const newRoleId = sel.value;
    const newRole = sel.options[sel.selectedIndex].text;
    fetch('admin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=updateRole&username=${username}&newRoleId=${newRoleId}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('current-role').textContent = newRole;
            } else {
                alert('Failed to update user role');
            }
        })
        .catch(error => console.error('Error:', error));
});

document.getElementById('delete-user-btn').addEventListener('click', function () {
    const username = document.getElementById('user-username').textContent;
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch('admin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=deleteUser&username=${encodeURIComponent(username)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('search-input').value = '';
                    document.getElementById('user-update-section').style.display = 'none';
                } else {
                    alert('Failed to delete user');
                }
            })
            .catch(error => console.error('Error:', error));
    }
});

function loadRoles() {
    fetch('admin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=fetchRoles'
    })
        .then(response => response.json())
        .then(roles => {
            const roleSelect = document.getElementById('role-select');
            roleSelect.innerHTML = '';
            roles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.role_id;
                option.textContent = role.role_name;
                roleSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

loadRoles();