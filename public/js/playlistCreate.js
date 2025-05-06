document.getElementById('createPlaylistBTN').addEventListener('click', async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        if (!csrfToken) throw new Error('CSRF token not found');
        
        console.log('Sending request with token:', csrfToken);
        
        const response = await fetch('/playlistPage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name: 'Новый плейлист',
            }),
            credentials: 'include' // Важно для сессий и cookies
        });

        console.log('Response status:', response.status);
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Server error');
        }

        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            window.location.href = `/playlistPage/${data.playlistId}`;
        }
    } catch (error) {
        console.error('Full error:', error);
        alert('Ошибка: ' + error.message);
    }
});