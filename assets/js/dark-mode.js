// Dark mode functionality
(function() {
  'use strict';

  const STORAGE_KEY = 'theme-preference';
  const THEME_ATTRIBUTE = 'data-theme';
  
  // Get stored theme or default to light
  function getStoredTheme() {
    return localStorage.getItem(STORAGE_KEY) || 'light';
  }

  // Store theme preference
  function storeTheme(theme) {
    localStorage.setItem(STORAGE_KEY, theme);
  }

  // Apply theme to document
  function applyTheme(theme) {
    document.documentElement.setAttribute(THEME_ATTRIBUTE, theme);
    updateToggleButton(theme);
  }

  // Update toggle button appearance
  function updateToggleButton(theme) {
    const toggleButton = document.getElementById('themeToggle');
    if (toggleButton) {
      toggleButton.textContent = theme === 'dark' ? '☀️' : '🌓';
      toggleButton.setAttribute('aria-label', 
        theme === 'dark' ? 'Chuyển sang chế độ sáng' : 'Chuyển sang chế độ tối'
      );
    }
  }

  // Toggle theme
  function toggleTheme() {
    const currentTheme = getStoredTheme();
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    applyTheme(newTheme);
    storeTheme(newTheme);
  }

  // Initialize theme on page load
  function initializeTheme() {
    const storedTheme = getStoredTheme();
    
    // Check if user has system preference
    if (!localStorage.getItem(STORAGE_KEY)) {
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const systemTheme = prefersDark ? 'dark' : 'light';
      applyTheme(systemTheme);
    } else {
      applyTheme(storedTheme);
    }
  }

  // Listen for system theme changes
  function watchSystemTheme() {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Modern browsers
    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', (e) => {
        // Only apply system theme if user hasn't set a preference
        if (!localStorage.getItem(STORAGE_KEY)) {
          const systemTheme = e.matches ? 'dark' : 'light';
          applyTheme(systemTheme);
        }
      });
    }
  }

  // Initialize when DOM is ready
  function init() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
      return;
    }

    initializeTheme();
    watchSystemTheme();

    // Add click listener to theme toggle button
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', toggleTheme);
    }
  }

  // Start initialization
  init();
})();