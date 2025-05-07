document.querySelectorAll('.input-file input[type=file]').forEach(input => {
    input.addEventListener('change', function() {
    const fileName = this.files.length 
        ? this.files[0].name 
        : 'Файл не выбран';
    this.closest('.input-file')
        .querySelector('.input-file-text')
        .textContent = fileName;
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const uploadTrackBTN = document.getElementById('uploadTrackBTN');
    const editButton = document.getElementById('editButton');
    
    const playlist_title = document.getElementById('playlist_title'); 
    const currentTitle = playlist_title.textContent;
    const uploadCont = document.getElementById('uploadCont');

    uploadTrackBTN.addEventListener('click',function (event) {
        uploadCont.classList.remove("d-none");
        uploadCont.classList.add("d-flex");
    });
});

document.getElementById('playlist_title').addEventListener('dblclick', function(event) {
    const titleElement = this;
    const input = document.createElement('input');
    
    input.type = 'text';
    input.placeholder = titleElement.textContent
    input.className = 'title-edit-input';
    
    titleElement.innerHTML = '';
    titleElement.appendChild(input);
    input.focus();
    
    input.addEventListener('blur', function() {
        saveTitle(titleElement, input.value);
    });
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.blur();
        }
    });
});

function saveTitle(element, newName) {
    if (newName.trim() === '') {
        newName = element.dataset.original;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        if (!csrfToken) throw new Error('CSRF token not found');
        
        console.log('Sending request with token:', csrfToken);

        fetch(element.dataset.url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ playlistName: newName }),
            credentials: 'include' // Важно для сессий и cookies
        })
        
        .then(response => response.json())
        .then(data => {
            element.textContent = data.playlistName;
        })
        .catch(() => {
            element.textContent = element.dataset.original;
        });
        console.log('Success!', csrfToken);
    } catch (error) {
        console.error('Full error:', error);
        alert('Ошибка: ' + error.message);
    }
}