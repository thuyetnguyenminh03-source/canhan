// Image Modal Functionality
class ImageModal {
  constructor() {
    this.modal = null;
    this.currentImage = null;
    this.images = [];
    this.currentIndex = 0;
    this.init();
  }

  init() {
    this.createModal();
    this.bindEvents();
  }

  createModal() {
    // Create modal HTML
    const modalHTML = `
      <div class="image-modal" id="imageModal">
        <div class="image-modal-backdrop" data-close="true"></div>
        <div class="image-modal-content">
          <button class="image-modal-close" data-close="true" aria-label="Đóng">
            <svg viewBox="0 0 24 24" width="24" height="24">
              <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
          <button class="image-modal-nav image-modal-prev" aria-label="Ảnh trước">
            <svg viewBox="0 0 24 24" width="24" height="24">
              <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button class="image-modal-nav image-modal-next" aria-label="Ảnh sau">
            <svg viewBox="0 0 24 24" width="24" height="24">
              <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="image-modal-image-container">
            <img class="image-modal-image" src="" alt="">
          </div>
          <div class="image-modal-info">
            <div class="image-modal-title"></div>
            <div class="image-modal-counter"></div>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    this.modal = document.getElementById('imageModal');
  }

  bindEvents() {
    // Close modal events
    this.modal.addEventListener('click', (e) => {
      if (e.target.hasAttribute('data-close')) {
        this.close();
      }
    });

    // Navigation events
    this.modal.querySelector('.image-modal-prev').addEventListener('click', () => this.prevImage());
    this.modal.querySelector('.image-modal-next').addEventListener('click', () => this.nextImage());

    // Keyboard events
    document.addEventListener('keydown', (e) => {
      if (!this.modal.classList.contains('active')) return;
      
      switch(e.key) {
        case 'Escape':
          this.close();
          break;
        case 'ArrowLeft':
          this.prevImage();
          break;
        case 'ArrowRight':
          this.nextImage();
          break;
      }
    });

    // Make project images clickable
    this.makeImagesClickable();
  }

  makeImagesClickable() {
    // Wait for projects to be loaded
    const checkProjects = () => {
      const projectImages = document.querySelectorAll('.project-card .p-media img');
      
      if (projectImages.length > 0) {
        projectImages.forEach((img, index) => {
          // Add cursor pointer and hover effect
          img.style.cursor = 'pointer';
          img.addEventListener('click', () => this.open(img, index, projectImages));
        });
        
        // Remove the code that was blocking "Xem full" links
        // Only make images clickable, not the links
      } else {
        // Check again in 1 second if no projects found
        setTimeout(checkProjects, 1000);
      }
    };

    // Start checking when DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', checkProjects);
    } else {
      checkProjects();
    }
  }

  open(img, index, allImages) {
    this.currentImage = img;
    this.currentIndex = index;
    this.images = Array.from(allImages);
    
    const modalImg = this.modal.querySelector('.image-modal-image');
    const modalTitle = this.modal.querySelector('.image-modal-title');
    const modalCounter = this.modal.querySelector('.image-modal-counter');
    
    // Get project title from the card
    const projectCard = img.closest('.project-card');
    const projectTitle = projectCard.querySelector('h3').textContent;
    
    modalImg.src = img.src;
    modalImg.alt = img.alt;
    modalTitle.textContent = projectTitle;
    modalCounter.textContent = `${index + 1} / ${this.images.length}`;
    
    // Show/hide navigation based on position
    this.updateNavigation();
    
    // Open modal
    this.modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Focus on modal for keyboard navigation
    this.modal.focus();
  }

  close() {
    this.modal.classList.remove('active');
    document.body.style.overflow = '';
  }

  prevImage() {
    if (this.currentIndex > 0) {
      this.currentIndex--;
      this.updateImage();
    }
  }

  nextImage() {
    if (this.currentIndex < this.images.length - 1) {
      this.currentIndex++;
      this.updateImage();
    }
  }

  updateImage() {
    const modalImg = this.modal.querySelector('.image-modal-image');
    const modalTitle = this.modal.querySelector('.image-modal-title');
    const modalCounter = this.modal.querySelector('.image-modal-counter');
    
    const newImg = this.images[this.currentIndex];
    const projectCard = newImg.closest('.project-card');
    const projectTitle = projectCard.querySelector('h3').textContent;
    
    modalImg.src = newImg.src;
    modalImg.alt = newImg.alt;
    modalTitle.textContent = projectTitle;
    modalCounter.textContent = `${this.currentIndex + 1} / ${this.images.length}`;
    
    this.updateNavigation();
  }

  updateNavigation() {
    const prevBtn = this.modal.querySelector('.image-modal-prev');
    const nextBtn = this.modal.querySelector('.image-modal-next');
    
    prevBtn.style.display = this.currentIndex === 0 ? 'none' : 'block';
    nextBtn.style.display = this.currentIndex === this.images.length - 1 ? 'none' : 'block';
  }
}

// Initialize the image modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  window.imageModal = new ImageModal();
});