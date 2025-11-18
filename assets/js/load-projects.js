// Load projects from API dynamically
async function loadProjectsFromAPI() {
  try {
    const response = await fetch('api/site.php');
    const data = await response.json();
    const projects = data.projects || [];
    const projectGrid = document.querySelector('[data-project-track]');
    
    if (!projectGrid || !projects.length) {
      console.log('No projects found or grid not available');
      return;
    }
    
    // Clear existing projects
    projectGrid.innerHTML = '';
    
    // Add each project to the grid
    projects.forEach(project => {
      const card = document.createElement('article');
      card.className = 'project-card reveal-up';
      card.setAttribute('data-cat', 'brand');
      
      card.innerHTML = `
        <div class="p-media">
          <img alt="${project.title_vi}" src="assets/img/placeholder-4.svg"/>
          <a class="p-view" data-case="${project.slug}" href="projects/p${project.id}.html">Xem full</a>
        </div>
        <div class="p-body">
          <h3>${project.title_vi}</h3>
          <p class="p-tags">#branding · #realestate</p>
        </div>
      `;
      
      projectGrid.appendChild(card);
    });
    
    // Reinitialize slider if function exists
    if (typeof rebuildProjectTrack === 'function') {
      rebuildProjectTrack();
    }
    
    console.log(`Loaded ${projects.length} projects from API`);
  } catch (error) {
    console.error('Failed to load projects from API:', error);
  }
}

// Auto-load projects when page loads
document.addEventListener('DOMContentLoaded', function() {
  const projectGrid = document.querySelector('[data-project-track]');
  if (projectGrid) {
    console.log('Loading projects from API...');
    loadProjectsFromAPI();
  }
});
