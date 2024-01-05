document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('show-form-btn').onclick = function() {
        document.getElementById('note-form-modal').style.display = "block";
    }

    document.getElementById('close-modal-btn').onclick = function() {
        document.getElementById('note-form-modal').style.display = "none";
    }
 
    window.onclick = function(event) {
        const modal = document.getElementById('note-form-modal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
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

    document.getElementById('new-note-form').addEventListener('submit', function(event) {
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
});