// theme.js - Global theme management
class ThemeManager {
    constructor() {
        this.init();
    }

    init() {
        this.applySavedTheme();
        this.bindEvents();
    }

    applySavedTheme() {
        const savedTheme = localStorage.getItem('theme') === 'dark';
        const body = document.body;
        const html = document.documentElement;

        body.classList.toggle('dark-mode', savedTheme);
        html.setAttribute('data-theme', savedTheme ? 'dark' : 'light');
        
        this.updateIcons(savedTheme);
    }

    updateIcons(isDark) {
        const toggles = document.querySelectorAll('.theme-toggle');
        const icon = isDark ? 'moon' : 'sun';

        toggles.forEach(btn => {
            btn.innerHTML = `<i data-lucide="${icon}"></i>`;
        });

        // Re-initialize Lucide icons if available
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    toggleTheme() {
        const body = document.body;
        const html = document.documentElement;
        const isDark = !body.classList.contains('dark-mode');

        body.classList.toggle('dark-mode', isDark);
        html.setAttribute('data-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        this.updateIcons(isDark);
    }

    bindEvents() {
        // Bind to all theme toggle buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.theme-toggle')) {
                this.toggleTheme();
            }
        });

        // Also bind to any dynamically added toggles
        document.addEventListener('DOMContentLoaded', () => {
            const toggles = document.querySelectorAll('.theme-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', () => this.toggleTheme());
            });
        });
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
});

// Also make it available globally for manual initialization
window.ThemeManager = ThemeManager;