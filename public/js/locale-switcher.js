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
    
    // Set up event listeners
    languageLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            switchLocale(link.dataset.locale);
            closeDropdown();
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', e => {
        const details = document.querySelector('details.dropdown');
        if (details && !details.contains(e.target)) {
            details.removeAttribute('open');
        }
    });
    
    // Simple function to close dropdown
    function closeDropdown() {
        const details = document.querySelector('details.dropdown');
        if (details) {
            details.removeAttribute('open');
        }
    }
    
    // Function to handle locale switching
    async function switchLocale(locale) {
        // Update active state of language links
        const activeLink = document.querySelector(`[data-locale="${locale}"]`);
        if (activeLink) {
            const activeLinks = document.querySelectorAll('[data-locale].active');
            activeLinks.forEach(link => link.classList.remove('active'));
            activeLink.classList.add('active');
        }
        try {
            // Collect all translatable elements before making the request
            const transElements = document.querySelectorAll('[data-trans], [data-trans-placeholder]');
            const translationKeys = [];
            
            // Create a map of elements with their translation keys and parameters
            const elementMap = new Map();
            
            transElements.forEach(element => {
                // Handle both regular translations and placeholder translations
                if (element.hasAttribute('data-trans')) {
                    const transKey = element.getAttribute('data-trans');
                    translationKeys.push(transKey);
                    
                    // Store element with its key for later processing
                    if (!elementMap.has(transKey)) {
                        elementMap.set(transKey, []);
                    }
                    elementMap.get(transKey).push({
                        element, 
                        type: 'content'
                    });
                }
                
                // Handle placeholder translations separately
                if (element.hasAttribute('data-trans-placeholder')) {
                    const placeholderKey = element.getAttribute('data-trans-placeholder');
                    translationKeys.push(placeholderKey);
                    
                    // Store element with its placeholder key for later processing
                    if (!elementMap.has(placeholderKey)) {
                        elementMap.set(placeholderKey, []);
                    }
                    elementMap.get(placeholderKey).push({
                        element, 
                        type: 'placeholder'
                    });
                }
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
                
                // Update translations if available
                if (data.translations) {
                    // Process each translation key
                    Object.keys(data.translations).forEach(key => {
                        const translation = data.translations[key];
                        
                        // Process all elements that use this translation key
                        if (elementMap.has(key)) {
                            elementMap.get(key).forEach(({element, type}) => {
                                let finalText = translation;
                                
                                // Apply parameters if defined
                                const paramsAttr = element.getAttribute('data-trans-params');
                                if (paramsAttr) {
                                    try {
                                        let params = {};
                                        
                                        // Handle object notation with RegExp values: { '%key%': /value }
                                        if (paramsAttr.includes('/')) {
                                            const match = paramsAttr.match(/{\s*['"]?(%\w+%?)['"]?\s*:\s*\/([^\/]+).*}/);
                                            if (match) {
                                                params[match[1]] = match[2];
                                            }
                                        } else {
                                            // Handle JSON format
                                            const sanitizedParams = paramsAttr.trim()
                                                .replace(/'/g, '"')
                                                .replace(/([{,]\s*)(\w+):/g, '$1"$2":');
                                            params = JSON.parse(sanitizedParams);
                                        }
                                        
                                        // Apply replacements
                                        Object.keys(params).forEach(key => {
                                            const paramPattern = new RegExp(key.replace(/%/g, '%'), 'g');
                                            finalText = finalText.replace(paramPattern, params[key]);
                                        });
                                    } catch (e) {
                                        console.error('Invalid JSON in data-trans-params:', e, paramsAttr);
                                    }
                                }
                                
                                // Apply as content or placeholder based on type
                                if (type === 'content') {
                                    // Apply as raw HTML or text for content
                                    if (element.hasAttribute('data-trans-raw')) {
                                        element.innerHTML = finalText;
                                    } else {
                                        element.textContent = finalText;
                                    }
                                } else if (type === 'placeholder') {
                                    // Apply as placeholder attribute
                                    element.setAttribute('placeholder', finalText);
                                }
                            });
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error switching locale:', error);
            alert('Failed to switch language. Please try again later.');
        }
    }
});