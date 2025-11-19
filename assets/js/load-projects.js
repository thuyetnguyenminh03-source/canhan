// Load projects from API dynamically
async function loadProjectsFromAPI() {
  try {
    // Debug: Log current page info
    console.log('=== DEBUG PROJECTS API ===');
    console.log('Current URL:', window.location.href);
    console.log('Current pathname:', window.location.pathname);
    console.log('Current hostname:', window.location.hostname);
    console.log('Current port:', window.location.port);
    
    const apiUrl = '/api/site.php';
    console.log('Final API URL:', apiUrl);
    
    // Test if URL is accessible
    console.log('Testing URL accessibility...');
    const response = await fetch(apiUrl);
    console.log('API Response Status:', response.status);
    console.log('API Response URL:', response.url);
    console.log('API Response Headers:', [...response.headers.entries()]);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    const projects = data.projects || [];
    const projectGrid = document.querySelector('[data-project-track]');
    
    if (!projectGrid) {
      console.log('Project grid not found');
      return;
    }
    
    if (!projects.length) {
      projectGrid.innerHTML = '<p style="text-align: center; width: 100%;">Chưa có dự án nào.</p>';
      return;
    }
    
    // Clear existing projects
    projectGrid.innerHTML = '';
    
    // Add each project to the grid
    projects.forEach(project => {
      const card = document.createElement('article');
      // Assuming a 'category' column exists, otherwise default to 'brand'
      const category = project.category || 'brand';
      card.className = 'project-card';
      card.setAttribute('data-cat', category);
      
      // Construct cover image URL, with a fallback
      const coverImage = project.cover_url && project.cover_url.length ? project.cover_url : `assets/img/projects/${project.slug}/${project.slug}-cover.jpg`;
      const fallbackImage = 'assets/img/placeholder-4.svg';

      card.innerHTML = `
        <div class="p-media">
          <img alt="${project.title_vi}" data-src="${coverImage}" src="${coverImage}" loading="lazy" onerror="this.onerror=null;this.src='${fallbackImage}';" class="lazy-image"/>
          <a class="p-view" data-case="${project.slug}" href="projects/template.html?slug=${project.slug}">Xem full</a>
        </div>
        <div class="p-body">
          <h3>${project.title_vi}</h3>
          <p class="p-tags">${(project.tags || `#${category} · #realestate`).split(',').join(' · ')}</p>
        </div>
      `;
      
      projectGrid.appendChild(card);
    });
    
    // Reinitialize slider and reveal animations if they exist
    if (typeof rebuildProjectTrack === 'function') {
      rebuildProjectTrack();
    }
    if (typeof revealObserver !== 'undefined' && typeof revealObserver.observe === 'function') {
      document.querySelectorAll('.project-card').forEach((el) => revealObserver.observe(el));
    }
    
    console.log(`Loaded ${projects.length} projects from API`);
    
    // Debug: Check if Xem full links exist
    setTimeout(() => {
      const xemFullLinks = document.querySelectorAll('.p-view');
      console.log(`Found ${xemFullLinks.length} Xem full links`);
      xemFullLinks.forEach((link, index) => {
        console.log(`Link ${index + 1}:`, link.href, link.textContent, link.target);
        // Test if link is truly clickable
        link.style.border = '2px solid red'; // Make it visible for testing
      });
    }, 1000);
    
    // Initialize lazy loading for images
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.add('loaded');
            img.classList.remove('lazy-image');
            
            // Make image clickable for modal
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
              if (window.imageModal) {
                const allProjectImages = document.querySelectorAll('.project-card .p-media img');
                const index = Array.from(allProjectImages).indexOf(this);
                window.imageModal.open(this, index, allProjectImages);
              }
            });
            
            observer.unobserve(img);
          }
        });
      });
      
      document.querySelectorAll('.lazy-image').forEach(img => imageObserver.observe(img));
    } else {
      // Fallback for browsers without IntersectionObserver
      document.querySelectorAll('.lazy-image').forEach(img => {
        img.src = img.dataset.src;
        img.classList.add('loaded');
        img.classList.remove('lazy-image');
        
        // Make image clickable for modal
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
          if (window.imageModal) {
            const allProjectImages = document.querySelectorAll('.project-card .p-media img');
            const index = Array.from(allProjectImages).indexOf(this);
            window.imageModal.open(this, index, allProjectImages);
          }
        });
      });
    }
    
  } catch (error) {
    console.error('Failed to load projects from API:', error);
    console.error('Error details:', error.message);
    const projectGrid = document.querySelector('[data-project-track]');
    if(projectGrid) {
      projectGrid.innerHTML = '<p style="text-align: center; width: 100%;">Không thể tải danh sách dự án.</p>';
    }
  }
}

// Auto-load projects when page loads
document.addEventListener('DOMContentLoaded', function() {
  const projectGrid = document.querySelector('[data-project-track]');
  if (projectGrid) {
    loadProjectsFromAPI();
  }
});
