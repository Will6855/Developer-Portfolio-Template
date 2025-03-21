document.addEventListener('DOMContentLoaded', () => {
    const languageLinks = document.querySelectorAll('[data-locale]');
    const supportedLocales = ['en', 'fr'];
    const currentLocale = localStorage.getItem('locale');
    
    // Handle initial locale setting
    if (!currentLocale) {
        const browserLang = (navigator.language || navigator.userLanguage).split('-')[0].toLowerCase();
        if (supportedLocales.includes(browserLang)) {
            switchLocale(browserLang);
        }
    } else if (currentLocale !== document.documentElement.lang) {
        switchLocale(currentLocale);
    }
    
    // Set up event listeners and close dropdown when clicking outside
    document.addEventListener('click', e => {
        const details = document.querySelector('details.dropdown');
        if (details && !details.contains(e.target)) {
            details.removeAttribute('open');
        }
    });
    
    languageLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            switchLocale(link.dataset.locale);
            const details = document.querySelector('details.dropdown');
            if (details) {
                details.removeAttribute('open');
            }
        });
    });
    
    // Function to handle locale switching
    async function switchLocale(locale) {
        try {
            // Collect all translatable elements before making the request
            const transElements = document.querySelectorAll('[data-trans], [data-trans-placeholder]');
            const translationKeys = [];
            
            // Create a map of elements with their translation keys and parameters
            const elementMap = new Map();
            
            transElements.forEach(element => {
                // Handle both regular translations and placeholder translations
                const transKey = element.getAttribute('data-trans') || element.getAttribute('data-trans-placeholder');
                const type = element.getAttribute('data-trans') ? 'content' : 'placeholder';
                translationKeys.push(transKey);
                
                // Store element with its key for later processing
                if (!elementMap.has(transKey)) {
                    elementMap.set(transKey, []);
                }
                elementMap.get(transKey).push({
                    element, 
                    type
                });
            });
            
            // Make the request with all the keys we need
            const response = await fetch(`/switch-locale/${locale}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Turbo-Visit': 'false'
                },
                body: JSON.stringify({
                    keys: translationKeys
                }),
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            if (data.success) {
                // Update localStorage and language display
                localStorage.setItem('locale', locale);
                document.documentElement.lang = locale;
                
                // Update flags visibility
                document.querySelectorAll('.language-flag').forEach(flag => {
                    flag.style.display = 'none';
                });
                const activeFlag = document.querySelector(`.language-flag-${locale}`);
                if (activeFlag) {
                    activeFlag.style.display = 'inline';
                }
                
                // Update active state of language links
                const activeLink = document.querySelector(`[data-locale="${locale}"]`);
                if (activeLink) {
                    const activeLinks = document.querySelectorAll('[data-locale].active');
                    activeLinks.forEach(link => link.classList.remove('active'));
                    activeLink.classList.add('active');
                }
                
                // Update translations if available
                if (data.translations) {
                    Object.entries(data.translations).forEach(([key, translation]) => {
                        elementMap.get(key)?.forEach(({ element, type }) => {
                            let finalText = translation;
                            const paramsAttr = element.getAttribute('data-trans-params');
                            
                            if (paramsAttr) {
                                try {
                                    const params = JSON.parse(paramsAttr);
                                    Object.entries(params).forEach(([k, v]) => {
                                        finalText = finalText.replace(new RegExp(k.replace(/%/g, '%'), 'g'), v);
                                    });
                                } catch (e) {
                                    console.error('Invalid JSON in data-trans-params:', e, paramsAttr);
                                }
                            }
                            
                            type === 'content' 
                                ? (element.hasAttribute('data-trans-raw') ? element.innerHTML = finalText : element.textContent = finalText)
                                : element.setAttribute('placeholder', finalText);
                        });
                    });
                }
            }
        } catch (error) {
            console.error('Error switching locale:', error);
            alert('Failed to switch language. Please try again later.');
        }
    }
});