document.addEventListener('DOMContentLoaded', (event) => {

    // MODALS UI -------------------------------------------------------------------------

    const note_content = document.getElementById('note-content');
    const edit_note_content = document.getElementById('edit-note-content');

    note_content.addEventListener('input', () => {
        const numberOfRows = note_content.value.split('\n').length;
        note_content.rows = Math.min(numberOfRows+10, 30)
    });
    edit_note_content.addEventListener('input', () => {
        const numberOfRows = edit_note_content.value.split('\n').length;
        edit_note_content.rows = Math.min(numberOfRows+3, 30)
    });


    document.getElementById('show-form-btn').onclick = function () {
        //document.getElementById('note-form-modal').style.display = "block";
        document.getElementById('note-form-modal').classList.add('show-modal');
        const numberOfRows = note_content.value.split('\n').length;
        note_content.rows = Math.min(numberOfRows+10, 30)
    }

    document.getElementById('close-modal-btn').onclick = function () {
        document.getElementById('note-form-modal').classList.remove('show-modal');
        //document.getElementById('note-form-modal').style.display = "none";
    }

    document.getElementById('close-edit-modal-btn').onclick = function () {
        document.getElementById('edit-note-modal').classList.remove('show-modal');
        //document.getElementById('edit-note-modal').style.display = "none";
    }

    window.openEditModal = function (note) {
        document.getElementById('edit-note-id').value = note.note_id;
        document.getElementById('edit-note-title').value = note.note_title;
        document.getElementById('edit-note-content').value = note.note_content; 
        document.getElementById('edit-note-modal').classList.add('show-modal');
        //document.getElementById('edit-note-modal').style.display = 'block';
        const numberOfRows = edit_note_content.value.split('\n').length;
        edit_note_content.rows = Math.min(numberOfRows+3, 30)
    };

    window.onclick = function(event) {
        const addModal = document.getElementById('note-form-modal');
        const editModal = document.getElementById('edit-note-modal');

        if (event.target === addModal) {
            //addModal.style.display = 'none';
            document.getElementById('note-form-modal').classList.remove('show-modal');
        }
        if (event.target === editModal) {
            //editModal.style.display = 'none';
            document.getElementById('edit-note-modal').classList.remove('show-modal');
        }
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
                            this.parentElement.remove();
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

    document.getElementById('new-note-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('panel', {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
                else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
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
    // AJAX API END ----------------------------------------------------------------------
});