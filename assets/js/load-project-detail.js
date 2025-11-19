document.addEventListener('DOMContentLoaded', function() {
  const mainContent = document.getElementById('main-content');
  const urlParams = new URLSearchParams(window.location.search);
  const projectSlug = urlParams.get('slug');

  if (!projectSlug) {
    mainContent.innerHTML = '<div class="container"><p>Không tìm thấy slug dự án.</p></div>';
    return;
  }

  fetch(`/api/project-mock.php?slug=${projectSlug}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.error) {
        throw new Error(data.error);
      }
      renderProject(data.project, data.media, data.testimonials);
    })
    .catch(error => {
      console.error('Error fetching project details:', error);
      mainContent.innerHTML = `<div class="container" style="padding: 4rem 0; text-align: center;"><p>Lỗi: Không thể tải dữ liệu dự án. Vui lòng thử lại.</p><p style="color: #888; font-size: 0.9em;">Chi tiết: ${error.message}</p></div>`;
    });
});

function escapeHTML(str) {
  if (str === null || str === undefined) {
    return '';
  }
  return str.toString().replace(/[&<>"']/g, function(match) {
    return {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    }[match];
  });
}

function renderProject(project, media, testimonials) {
  const mainContent = document.getElementById('main-content');
  
  // Set page title and case dataset
  document.title = project.title_vi || 'Chi tiết dự án';
  document.body.dataset.case = project.slug;

  // Helper to generate gallery HTML
  const createGallery = (items, sectionTitle) => {
    if (!items || items.length === 0) return '';
    return `
      <section class="case-section">
        <div class="container">
          <h2>${escapeHTML(sectionTitle)}</h2>
          <div class="case-gallery">
            ${items.map(item => `
              <article class="gallery-card">
                <img alt="${escapeHTML(item.title || project.title_vi)}" src="../${escapeHTML(item.url)}"/>
                <footer><span>${escapeHTML(item.title || '')}</span></footer>
              </article>
            `).join('')}
          </div>
        </div>
      </section>
    `;
  };

  const coverImage = media.cover && media.cover.length > 0 ? `../${media.cover[0].url}` : '../assets/img/placeholder-4.svg';

  const projectHTML = `
    <section class="case-hero reveal-up">
      <div class="container">
        <div class="case-hero-copy">
          <nav class="case-breadcrumb">
            <a href="../index.html#hero">Trang chủ</a>
            <span>›</span>
            <a href="../index.html#projects">Dự án</a>
            <span>›</span>
            <span>${escapeHTML(project.title_vi)}</span>
          </nav>
          <h1>${escapeHTML(project.title_vi)}</h1>
          <p class="case-summary">${escapeHTML(project.summary_vi || '')}</p>
          <div class="case-meta-grid">
            <article class="case-meta-card"><p>Vai trò</p><strong>${escapeHTML(project.meta_role || 'N/A')}</strong></article>
            <article class="case-meta-card"><p>Thời gian</p><strong>${escapeHTML(project.meta_time || 'N/A')}</strong></article>
            <article class="case-meta-card"><p>Công cụ</p><strong>${escapeHTML(project.meta_tools || 'N/A')}</strong></article>
          </div>
          <div class="case-cta">
            <a class="btn" href="../index.html#contact">Đặt lịch tư vấn</a>
            <a class="btn btn-ghost" href="../index.html#projects">Xem dự án khác</a>
          </div>
        </div>
        <div class="case-cover">
          <img alt="${escapeHTML(project.title_vi)} cover" src="${escapeHTML(coverImage)}"/>
        </div>
      </div>
    </section>

    <section class="case-section">
      <div class="container">
        <h2>Mục tiêu & Thách thức</h2>
        <div class="case-grid">
          <div>
            <p><strong>Mục tiêu:</strong> ${escapeHTML(project.objective_vi || '')}</p>
            <p><strong>Thách thức:</strong> ${escapeHTML(project.challenge_vi || '')}</p>
          </div>
          <div>
            <p><strong>Chiến lược:</strong> ${escapeHTML(project.strategy_vi || '')}</p>
            <p><strong>Workflow:</strong> ${escapeHTML(project.workflow_vi || '')}</p>
          </div>
        </div>
      </div>
    </section>

    ${createGallery(media.gallery, 'Hình ảnh triển khai')}
    ${createGallery(media.policy, 'Chính sách bán hàng')}
    ${createGallery(media.floor, 'Mặt bằng & thiết kế')}
    ${createGallery(media.recruitment, 'Tuyển dụng')}

    ${testimonials && testimonials.length > 0 ? `
    <section class="case-section">
        <div class="container">
            <h2>Khách hàng nói gì</h2>
            <div class="testimonials-grid">
                ${testimonials.map(t => `
                    <blockquote class="testimonial-item">
                        <p>"${escapeHTML(t.quote_vi)}"</p>
                        <footer>— ${escapeHTML(t.author)}, ${escapeHTML(t.role_title)}</footer>
                    </blockquote>
                `).join('')}
            </div>
        </div>
    </section>
    ` : ''}

    <section class="case-section">
      <div class="container">
        <div class="case-notice">
          Hình ảnh thuộc bản quyền. Vui lòng không sao chép hoặc sử dụng khi chưa được phép.
        </div>
        <h2>Kết quả chính</h2>
        <div class="kpis">
          ${project.kpi1 ? `<div class="kpi"><div class="num">${escapeHTML(project.kpi1)}</div></div>` : ''}
          ${project.kpi2 ? `<div class="kpi"><div class="num">${escapeHTML(project.kpi2)}</div></div>` : ''}
          ${project.kpi3 ? `<div class="kpi"><div class="num">${escapeHTML(project.kpi3)}</div></div>` : ''}
          ${project.kpi4 ? `<div class="kpi"><div class="num">${escapeHTML(project.kpi4)}</div></div>` : ''}
        </div>
      </div>
    </section>
  `;

  mainContent.innerHTML = projectHTML;

  // Re-run reveal animations if available
  if (typeof revealObserver !== 'undefined' && typeof revealObserver.observe === 'function') {
    mainContent.querySelectorAll('.reveal-up').forEach((el) => revealObserver.observe(el));
  }
}