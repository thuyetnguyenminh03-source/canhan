// Load project detail from API dynamically
async function loadProjectDetail() {
  try {
    // Get slug from URL
    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get('slug');

    if (!slug) {
      showError('Không tìm thấy slug dự án');
      return;
    }

    // Fetch project data from API
    const response = await fetch(`../api/project.php?slug=${encodeURIComponent(slug)}`);
    const data = await response.json();

    if (!response.ok || data.error) {
      showError(data.error || 'Không thể tải dự án');
      return;
    }

    const { project, media } = data;

    // Update page title
    document.title = `${project.title_vi} – Case Study`;

    // Update body data-case
    document.body.setAttribute('data-case', project.slug);

    // Render project content
    renderProjectContent(project, media);

    console.log(`Loaded project: ${project.title_vi}`);
  } catch (error) {
    console.error('Failed to load project detail:', error);
    showError('Lỗi khi tải dự án');
  }
}

function renderProjectContent(project, media) {
  const mainContent = document.getElementById('main-content');

  // Hero section
  const heroHTML = `
    <section class="case-hero reveal-up">
      <div class="container">
        <div class="case-hero-copy">
          <nav class="case-breadcrumb">
            <a data-i18n="case.breadcrumb.home" href="../index.html#hero">Trang chủ</a>
            <span>›</span>
            <a data-i18n="case.breadcrumb.projects" href="../index.html#projects">Dự án</a>
            <span>›</span>
            <span data-case-key="title">${project.title_vi}</span>
          </nav>
          <h1 data-case-key="title">${project.title_vi}</h1>
          <p class="case-summary" data-case-key="summary">${project.summary_vi || project.description_vi}</p>
          <div class="case-meta-grid">
            <article class="case-meta-card">
              <p data-i18n="case.meta.role">Vai trò</p>
              <strong data-case-key="metaRole">${project.meta_role || 'Art Director · Designer'}</strong>
            </article>
            <article class="case-meta-card">
              <p data-i18n="case.meta.time">Thời gian</p>
              <strong data-case-key="metaTime">${project.meta_time || '2024–2025'}</strong>
            </article>
            <article class="case-meta-card">
              <p data-i18n="case.meta.tools">Công cụ</p>
              <strong data-case-key="metaTools">${project.meta_tools || 'AI, PS, ID, Figma'}</strong>
            </article>
          </div>
          <div class="case-cta">
            <a class="btn" data-i18n="case.cta.consult" href="../index.html#contact">Đặt lịch tư vấn</a>
            <a class="btn btn-ghost" data-i18n="case.cta.back" href="../index.html#projects">Xem dự án khác</a>
          </div>
        </div>
        <div class="case-cover">
          <img alt="${project.title_vi} cover" src="${getCoverImage(media) || '../assets/img/placeholder-4.svg'}"/>
        </div>
      </div>
    </section>
  `;

  // Objectives section
  const objectivesHTML = `
    <section class="case-section">
      <div class="container">
        <h2 data-i18n="case.objectivesTitle">Mục tiêu & Thách thức</h2>
        <div class="case-grid">
          <div>
            <p data-case-key="objective">${project.objective_vi || 'Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.'}</p>
            <p data-case-key="challenge">${project.challenge_vi || 'Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.'}</p>
          </div>
          <div>
            <p data-case-key="strategy">${project.strategy_vi || 'Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.'}</p>
            <p data-case-key="workflow">${project.workflow_vi || 'Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.'}</p>
          </div>
        </div>
      </div>
    </section>
  `;

  // Gallery sections
  let galleryHTML = '';

  // Gallery section
  if (media.gallery && media.gallery.length > 0) {
    galleryHTML += `
      <section class="case-section">
        <div class="container">
          <h2 data-i18n="case.galleryTitle">Hình ảnh triển khai</h2>
          <div class="case-gallery" data-brand="MynTex" data-gallery="gallery" data-tag="Project">
            ${media.gallery.map(item => `
              <article class="gallery-card">
                <img alt="${item.title || project.title_vi + ' visual'}" src="${item.url}"/>
                <footer>
                  <span>MynTex</span>
                  <span>Project</span>
                </footer>
              </article>
            `).join('')}
          </div>
        </div>
      </section>
    `;
  }

  // Policy section
  if (media.policy && media.policy.length > 0) {
    galleryHTML += `
      <section class="case-section">
        <div class="container">
          <h2 data-i18n="case.policyTitle">Chính sách</h2>
          <div class="case-gallery" data-brand="MynTex" data-gallery="policy" data-tag="Chính sách" data-tag-i18n="case.policyLabel">
            ${media.policy.map(item => `
              <article class="gallery-card">
                <img alt="${item.title || 'Chính sách - tài liệu'}" src="${item.url}"/>
                <footer>
                  <span>MynTex</span>
                  <span data-i18n="case.policyLabel">Chính sách</span>
                </footer>
              </article>
            `).join('')}
          </div>
        </div>
      </section>
    `;
  }

  // Floor section
  if (media.floor && media.floor.length > 0) {
    galleryHTML += `
      <section class="case-section">
        <div class="container">
          <h2 data-i18n="case.floorTitle">Vẽ Căn</h2>
          <div class="case-gallery" data-brand="MynTex" data-gallery="floor" data-tag="Vẽ căn" data-tag-i18n="case.floorLabel">
            ${media.floor.map(item => `
              <article class="gallery-card">
                <img alt="${item.title || project.title_vi + ' - Vẽ căn'}" src="${item.url}"/>
                <footer>
                  <span>MynTex</span>
                  <span data-i18n="case.floorLabel">Vẽ căn</span>
                </footer>
              </article>
            `).join('')}
          </div>
        </div>
      </section>
    `;
  }

  // Recruitment section
  if (media.recruitment && media.recruitment.length > 0) {
    galleryHTML += `
      <section class="case-section">
        <div class="container">
          <h2>Tuyển dụng</h2>
          <div class="case-gallery" data-brand="MynTex" data-gallery="recruitment" data-tag="Tuyển dụng">
            ${media.recruitment.map(item => `
              <article class="gallery-card">
                <img alt="${item.title || 'Tuyển dụng - tài liệu'}" src="${item.url}"/>
                <footer>
                  <span>MynTex</span>
                  <span>Tuyển dụng</span>
                </footer>
              </article>
            `).join('')}
          </div>
        </div>
      </section>
    `;
  }

  // Results section
  const resultsHTML = `
    <section class="case-section">
      <div class="container">
        <div class="case-notice" data-i18n="case.notice">
          Hình ảnh thuộc bản quyền MynTex. Vui lòng không sao chép hoặc sử dụng khi chưa được phép.
        </div>
        <h2 data-i18n="case.resultsTitle">Kết quả chính</h2>
        <div class="kpis">
          <div class="kpi"><div class="num">${project.kpi1 || '+45%'}</div><div data-i18n="case.kpi1">CTR chiến dịch</div></div>
          <div class="kpi"><div class="num">${project.kpi2 || '-30%'}</div><div data-i18n="case.kpi2">Thời gian sản xuất</div></div>
          <div class="kpi"><div class="num">${project.kpi3 || '+120%'}</div><div data-i18n="case.kpi3">Reach tự nhiên</div></div>
          <div class="kpi"><div class="num">${project.kpi4 || '98%'}</div><div data-i18n="case.kpi4">Độ nhất quán brand</div></div>
        </div>
      </div>
    </section>
  `;

  // Related projects section
  const relatedHTML = `
    <section class="case-section case-related">
      <div class="container">
        <div class="case-related-head">
          <h2 data-i18n="case.related.title">Dự án khác</h2>
          <p data-i18n="case.related.desc">Khám phá thêm case study phù hợp với mục tiêu của bạn.</p>
        </div>
        <div class="case-related-grid" data-related-grid>
        </div>
      </div>
    </section>
  `;

  // Combine all sections
  mainContent.innerHTML = heroHTML + objectivesHTML + galleryHTML + resultsHTML + relatedHTML;

  // Load related projects
  loadRelatedProjects();
}

function getCoverImage(media) {
  // Try to find cover image, fallback to first gallery image
  if (media.cover && media.cover.length > 0) {
    return media.cover[0].url;
  }
  if (media.gallery && media.gallery.length > 0) {
    return media.gallery[0].url;
  }
  return null;
}

async function loadRelatedProjects() {
  try {
    const response = await fetch('../api/site.php');
    const data = await response.json();
    const projects = data.projects || [];

    const relatedGrid = document.querySelector('[data-related-grid]');
    if (!relatedGrid || !projects.length) return;

    // Get current project slug
    const currentSlug = document.body.getAttribute('data-case');

    // Filter out current project and take 3 random others
    const otherProjects = projects.filter(p => p.slug !== currentSlug);
    const relatedProjects = otherProjects.sort(() => 0.5 - Math.random()).slice(0, 3);

    relatedGrid.innerHTML = relatedProjects.map(project => `
      <article class="related-card">
        <div class="related-image">
          <img alt="${project.title_vi}" src="../assets/img/placeholder-4.svg"/>
        </div>
        <div class="related-content">
          <h3>${project.title_vi}</h3>
          <p>${project.description_vi ? project.description_vi.substring(0, 100) + '...' : ''}</p>
          <a href="?slug=${project.slug}" class="related-link">Xem chi tiết</a>
        </div>
      </article>
    `).join('');

  } catch (error) {
    console.error('Failed to load related projects:', error);
  }
}

function showError(message) {
  const mainContent = document.getElementById('main-content');
  mainContent.innerHTML = `
    <div class="container" style="padding: 4rem 0; text-align: center;">
      <div style="color: #e74c3c; font-size: 1.2rem; margin-bottom: 2rem;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
        ${message}
      </div>
      <a href="../index.html#projects" class="btn">Quay lại trang dự án</a>
    </div>
  `;
}

// Auto-load project detail when page loads
document.addEventListener('DOMContentLoaded', function() {
  loadProjectDetail();
});
