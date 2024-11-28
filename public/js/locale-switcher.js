document.addEventListener('DOMContentLoaded', () => {
    const languageLinks = document.querySelectorAll('[data-locale]');
    const supportedLocales = ['en', 'fr'];
    const currentLocale = localStorage.getItem('locale');
    
    if (!currentLocale) {
        const browserLang = (navigator.language || navigator.userLanguage).split('-')[0].toLowerCase();
        supportedLocales.includes(browserLang) && switchLocale(browserLang);
    }

    languageLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            switchLocale(link.dataset.locale);
        });
    });

    async function switchLocale(locale) {
        try {
            const response = await fetch(`/switch-locale/${locale}`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            localStorage.setItem('locale', locale);
            document.querySelectorAll('.language-flag').forEach(flag => flag.style.display = 'none');
            document.querySelector(`.language-flag-${locale}`).style.display = 'inline';
            window.location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    }
});
