document.addEventListener('DOMContentLoaded', (event) => {

    const note_content = document.getElementById('note-content');;

    note_content.addEventListener('input', () => {
        const numberOfRows = note_content.value.split('\n').length;
        note_content.rows = Math.min(numberOfRows+10, 30)
    });


    document.getElementById('show-form-btn').onclick = function () {
        document.getElementById('note-form-modal').classList.add('show-modal');
        const numberOfRows = note_content.value.split('\n').length;
        note_content.rows = Math.min(numberOfRows+10, 30)
    }

    document.getElementById('close-modal-btn').onclick = function () {
        document.getElementById('note-form-modal').classList.remove('show-modal');
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('note-form-modal');

        if (event.target === addModal) {
            document.getElementById('note-form-modal').classList.remove('show-modal');
        }
    };

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
                    // window.location.reload();
                    location.href = 'panel';
                }
                else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});