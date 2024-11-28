document.addEventListener('DOMContentLoaded', function() {
    const languageLinks = document.querySelectorAll('[data-locale]');

    languageLinks.forEach(link => {
        link.addEventListener('click', async function(e) {
            e.preventDefault();
            const locale = this.dataset.locale;
            
            try {
                const response = await fetch(`/switch-locale/${locale}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Update language switcher UI
                document.querySelectorAll('.language-flag').forEach(flag => {
                    flag.style.display = 'none';
                });
                document.querySelector(`.language-flag-${locale}`).style.display = 'inline';
                
                // Reload the page to get new translations
                window.location.reload();
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});
