const rootEl = document.documentElement;
const prefersDarkMq = window.matchMedia('(prefers-color-scheme: dark)');
const prefersReducedMotionMq = window.matchMedia('(prefers-reduced-motion: reduce)');

const motionBehavior = () => (prefersReducedMotionMq.matches ? 'auto' : 'smooth');
const addMqListener = (mq, cb) => {
  if (typeof mq.addEventListener === 'function') mq.addEventListener('change', cb);
  else if (typeof mq.addListener === 'function') mq.addListener(cb);
};
const throttle = (fn, wait = 100) => {
  let last = 0;
  let timer;
  return (...args) => {
    const now = performance.now();
    if (now - last >= wait) {
      last = now;
      fn(...args);
    } else if (!timer) {
      timer = window.setTimeout(() => {
        last = performance.now();
        timer = null;
        fn(...args);
      }, wait - (now - last));
    }
  };
};

// Mobile nav
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!expanded));
    navMenu.classList.toggle('open');
  });
  navMenu.addEventListener('click', (event) => {
    if (event.target.closest('a')) {
      navMenu.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    }
  });
}

// Theme toggle
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
  rootEl.setAttribute('data-theme', savedTheme);
} else if (!prefersDarkMq.matches) {
  rootEl.setAttribute('data-theme', 'light');
}
document.getElementById('themeToggle')?.addEventListener('click', () => {
  const current = rootEl.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
  rootEl.setAttribute('data-theme', current);
  localStorage.setItem('theme', current);
});

// Accent palette
const accentSaved = localStorage.getItem('accent') || 'violet';
rootEl.setAttribute('data-accent', accentSaved);
document.querySelectorAll('.swatch').forEach((btn) => {
  btn.addEventListener('click', () => {
    const value = btn.dataset.accent;
    rootEl.setAttribute('data-accent', value);
    localStorage.setItem('accent', value);
  });
});

// Typewriter setup
const typewriter = document.querySelector('.typewriter');
const typewriterOutput = typewriter?.querySelector('.typewriter-text');
const typewriterCaret = typewriter?.querySelector('.typewriter-caret');
const heroPrimaryCta = document.getElementById('downloadCV');
const brandLogo = document.querySelector('.brand-logo.logo-shine');
const footerLogo = document.querySelector('.footer-logo.logo-shine');
let typewriterTimer;
let typewriterWords = [];
let typewriterWordIndex = 0;
let typewriterCharIndex = 0;
let typewriterTyping = true;
const TYPEWRITER_SPEED = 40;
const TYPEWRITER_HOLD = 2000;
const TYPEWRITER_ERASE = 30;
const playShine = (target) => {
  if (!target) return;
  target.classList.remove('shine-active');
  void target.offsetWidth;
  target.classList.add('shine-active');
};

const triggerCtaShine = () => {
  playShine(heroPrimaryCta);
  playShine(brandLogo);
};

const setTypewriterCaretVisible = (visible) => {
  if (!typewriterCaret) return;
  typewriterCaret.style.visibility = visible ? 'visible' : 'hidden';
  typewriterCaret.classList.toggle('blink', visible);
};

const runTypewriter = () => {
  if (!typewriter || !typewriterOutput || !typewriterWords.length) return;
  const currentWord = typewriterWords[typewriterWordIndex % typewriterWords.length] || '';
  if (typewriterTyping) {
    typewriterOutput.textContent = currentWord.slice(0, typewriterCharIndex);
    typewriterCharIndex += 1;
    if (typewriterCharIndex > currentWord.length) {
      typewriterTyping = false;
      setTypewriterCaretVisible(false);
      triggerCtaShine();
      typewriterTimer = setTimeout(runTypewriter, TYPEWRITER_HOLD);
      return;
    }
    typewriterTimer = setTimeout(runTypewriter, TYPEWRITER_SPEED);
  } else {
    typewriterCharIndex -= 1;
    typewriterOutput.textContent = currentWord.slice(0, Math.max(typewriterCharIndex, 0));
    if (typewriterCharIndex <= 0) {
      typewriterTyping = true;
      typewriterWordIndex += 1;
      setTypewriterCaretVisible(true);
      typewriterTimer = setTimeout(runTypewriter, 400);
    } else {
      typewriterTimer = setTimeout(runTypewriter, TYPEWRITER_ERASE);
    }
  }
};

const initTypewriter = () => {
  if (!typewriter) return;
  clearTimeout(typewriterTimer);
  const text = typewriter.dataset.text || '';
  typewriterWords = text
    .split(/(?<=\.)\s+/)
    .map((str) => str.trim())
    .filter(Boolean);
  if (!typewriterWords.length && text) typewriterWords = [text];
  typewriterWordIndex = 0;
  typewriterCharIndex = 0;
  typewriterTyping = true;
  setTypewriterCaretVisible(true);
  runTypewriter();
};

// Translations
const translations = {
  vi: {
    'nav.about': 'Giới thiệu',
    'nav.projects': 'Dự án',
    'nav.services': 'Dịch vụ',
    'nav.skills': 'Kỹ năng',
    'nav.testimonials': 'Khách hàng',
    'nav.contact': 'Liên hệ',
    'nav.themeToggle': 'Đổi chế độ sáng/tối',
    'hero.greeting': 'Xin chào, tôi là',
    'hero.subhead': 'Thiết kế đồ hoạ BĐS • Brand Identity • Social Ads',
    'hero.ctaPrimary': 'Tải CV',
    'hero.ctaSecondary': 'Xem Dự Án',
    'hero.typewriter':
      'Tôi biến insight thành thiết kế có hiệu quả kinh doanh — tập trung lĩnh vực bất động sản, từ nhận diện thương hiệu, bộ ấn phẩm đến chiến dịch social đồng bộ.',
    'hero.scroll': 'Cuộn xuống',
    'about.title': 'Kinh nghiệm',
    'about.lead':
      'Hơn 3 năm thiết kế trong lĩnh vực BĐS & thương hiệu. Kết hợp tư duy chiến lược, gu thẩm mỹ hiện đại, sang trọng và workflow tối ưu để tạo ra sản phẩm hoàn thiện, đúng hạn, đúng mục tiêu.',
    'timeline.1.title': '11/2024 – 2025 · Công ty CP Tư vấn & Đầu tư BĐS An Khang',
    'timeline.1.item1': 'Design xây dựng bộ nhận diện, KV chiến dịch cho danh mục dự án cao cấp.',
    'timeline.1.item2': 'Điều phối sản phẩm digital & POSM đồng bộ giữa team in-house và agency đối tác.',
    'timeline.2.title': '05/2024 – 10/2024 · Công ty TNHH Địa ốc Nhà Đại Phát',
    'timeline.2.item1': 'Thiết kế bộ sản phẩm truyền thông cho dự án BĐS hạng sang, chuẩn hoá guideline visual.',
    'timeline.2.item2': 'Thực hiện social media post, motion short-form và chỉnh sửa video giới thiệu dự án.',
    'timeline.3.title': '02/2024 – 05/2024 · Freelance Designer',
    'timeline.3.item1': 'Thiết kế social media post theo KPI chiến dịch.',
    'timeline.3.item2': 'Phát triển brochure, banner, poster & các ấn phẩm in ấn cho đối tác BĐS.',
    'timeline.4.title': '10/2022 – 01/2023 · Intern Designer — Toppion Coaching & Consulting Group',
    'timeline.4.item1': 'Thiết kế cover cho sách/ấn phẩm đào tạo, social post và tài liệu nội bộ.',
    'timeline.4.item2': 'Hỗ trợ sản xuất brochure, standee, vật phẩm workshop.',
    'timeline.5.title': '02/2022 – 09/2022 · Part-time Designer — Công ty TNHH Hoàng Đức Hải',
    'timeline.5.item1': 'Thực hiện social media post theo chiến dịch tuần.',
    'timeline.5.item2': 'Thiết kế hình ảnh website, banner landing, tối ưu trải nghiệm trực tuyến.',
    'metrics.projects': 'Dự án hoàn thành',
    'metrics.brands': 'Thương hiệu hợp tác',
    'metrics.industries': 'Ngành hàng',
    'metrics.solutions': 'Giải pháp dịch vụ',
    'projects.title': 'Dự án nổi bật',
    'projects.filter.all': 'Tất cả',
    'projects.filter.brand': 'Branding',
    'projects.filter.social': 'Social',
    'projects.filter.print': 'Print',
    'projects.prev': 'Dự án trước',
    'projects.next': 'Dự án tiếp theo',
    'projects.view': 'Xem full',
    'projects.modal.title': 'Case Study',
    'projects.modal.desc': 'Mô tả ngắn gọn về mục tiêu, insight, giải pháp và kết quả.',
    'projects.modal.cta': 'Xem chi tiết',
    'footer.about.title': 'Về chúng tôi',
    'footer.about.link1': 'Giới thiệu',
    'footer.about.link2': 'Dịch vụ',
    'footer.about.link3': 'Liên hệ',
    'footer.projects.title': 'Dự án',
    'footer.projects.link1': 'Portfolio chính',
    'footer.projects.link2': 'Case study chi tiết',
    'footer.projects.link3': 'Lắng nghe ý kiến',
    'footer.docs.title': 'Tài liệu',
    'footer.docs.link1': 'Hướng dẫn',
    'footer.docs.link2': 'Điều khoản sử dụng',
    'footer.docs.link3': 'Bản quyền hình ảnh',
    'footer.socials.title': 'Mạng xã hội',
    'services.title': 'Dịch vụ',
    'services.card1.title': 'Brand Identity',
    'services.card1.desc': 'Logo, hệ màu, typography, guideline, stationery, ứng dụng nhận diện.',
    'services.card1.item1': 'Nghiên cứu & định vị',
    'services.card1.item2': 'Key visual & ứng dụng',
    'services.card1.item3': 'Brandbook (PDF)',
    'services.card2.title': 'Social & Campaign',
    'services.card2.desc': 'KV, bộ banner, video short, landing, kế hoạch nội dung & A/B test.',
    'services.card2.item1': 'Content & Visual system',
    'services.card2.item2': 'Media Kit',
    'services.card2.item3': 'Performance creative',
    'services.card3.title': 'Print & POSM',
    'services.card3.desc': 'Brochure, flyer, standee, OOH, 2D booth, in ấn và bàn giao file chuẩn.',
    'services.card3.item1': 'Kỹ thuật in & vật liệu',
    'services.card3.item2': 'Dàn trang chuyên nghiệp',
    'services.card3.item3': 'File in chuẩn',
    'skills.title': 'Kỹ năng',
    'testimonials.eyebrow': 'Lắng nghe ý kiến',
    'testimonials.title': 'Khách hàng nói về <span class=\"highlight\">Minh Thuyết</span>',
    'testimonials.lead': 'Creative partner được tin tưởng bởi đội ngũ Sales & Marketing tại các dự án BĐS cao cấp.',
    'testimonials.quote1': '“Thiết kế đẹp và đúng deadline. Chiến dịch social tăng tương tác 3×.”',
    'testimonials.meta1': 'Trưởng phòng Marketing, BĐS cao cấp',
    'testimonials.quote2': '“Brandbook chi tiết, triển khai đồng bộ rất mượt.”',
    'testimonials.meta2': 'CEO Thương hiệu nội thất',
    'testimonials.quote3': '“Tư duy hình ảnh hiện đại, phối hợp tốt với team media.”',
    'testimonials.meta3': 'Account Director, Agency',
    'testimonials.prev': 'Trước',
    'testimonials.next': 'Sau',
    'contact.title': 'Liên hệ',
    'contact.lead': 'Sẵn sàng cho dự án mới? Hãy nói về mục tiêu, ngân sách và timeline của bạn.',
    'contact.cta': 'Liên hệ',
    'contact.form.name': 'Họ tên',
    'contact.form.email': 'Email',
    'contact.form.subject': 'Chủ đề',
    'contact.form.message': 'Tin nhắn',
    'contact.form.submit': 'Gửi',
    'contact.form.copy': 'Sao chép email',
    'contact.form.zalo': 'Chat Zalo',
    'contact.form.status.sending': 'Đang gửi...',
    'contact.form.status.success': 'Đã gửi! Mình sẽ phản hồi sớm nhất có thể.',
    'contact.form.status.error': 'Gửi không thành công. Vui lòng thử lại hoặc email contact@anhthuyet.design.',
    'contact.form.copySuccess': 'Đã sao chép: {email}',
    'contact.form.copyManual': 'Không thể sao chép tự động. Copy email thủ công:',
    'contact.info.title': 'Thông tin liên lạc',
    'contact.info.addressLabel': 'Địa chỉ',
    'contact.info.addressValue': 'Thủ Đức - Hồ Chí Minh, Việt Nam',
    'contact.info.phoneLabel': 'Điện thoại',
    'contact.info.phoneValue': '(+84) 0912.275.643',
    'contact.info.emailLabel': 'Email',
    'contact.info.emailPrimary': 'hi@myntex.io.vn',
    'contact.info.emailSecondary': 'Thuyet.nguyenminh03@gmail.com',
    'contact.popup.title': 'Kết nối Zalo',
    'contact.popup.desc': 'Quét QR hoặc bấm nút dưới để chat nhanh qua Zalo.',
    'contact.popup.qr': 'Zalo QR',
    'contact.popup.cta': 'Mở Zalo',
    'case.breadcrumb.home': 'Trang chủ',
    'case.breadcrumb.projects': 'Dự án',
    'case.meta.role': 'Vai trò',
    'case.meta.time': 'Thời gian',
    'case.meta.tools': 'Công cụ',
    'case.cta.consult': 'Liên hệ',
    'case.cta.back': 'Xem dự án khác',
    'case.related.title': 'Dự án liên quan',
    'case.related.desc': 'Chọn thêm case study phù hợp với mục tiêu của bạn.',
    'case.related.cta': 'Xem chi tiết',
    'case.brief.title': 'Tóm tắt dự án',
    'case.brief.challenge': 'Thách thức',
    'case.brief.challengeText': 'Đảm bảo hệ nhận diện và asset vận hành luôn đồng bộ trên mọi kênh trong thời gian gấp.',
    'case.brief.deliverables': 'Deliverables',
    'case.brief.item1': 'Brand guideline & template đa kênh',
    'case.brief.item2': 'Bộ ấn phẩm social, brochure, POSM',
    'case.brief.item3': 'Kit tài liệu vận hành & đào tạo',
    'case.final.eyebrow': 'Sẵn sàng hợp tác',
    'case.final.title': 'Cùng phát triển dự án tiếp theo',
    'case.final.desc': 'Tôi sẵn sàng đồng hành từ chiến lược đến triển khai chi tiết cho thương hiệu và dự án bất động sản của bạn.',
    'case.objectivesTitle': 'Mục tiêu & Thách thức',
    'case.galleryTitle': 'Hình ảnh triển khai',
    'case.policyTitle': 'Chính sách',
    'case.policyLabel': 'Chính sách',
    'case.floorTitle': 'Vẽ Căn',
    'case.floorLabel': 'Vẽ căn',
    'case.resultsTitle': 'Kết quả chính',
    'case.kpi1': 'CTR chiến dịch',
    'case.kpi2': 'Thời gian sản xuất',
    'case.kpi3': 'Reach tự nhiên',
    'case.kpi4': 'Độ nhất quán brand',
    'case.placeholder': 'Đang cập nhật',
    'case.notice': 'Hình ảnh thuộc bản quyền MynTex. Vui lòng không sao chép hoặc sử dụng khi chưa được phép.',
    'case.back': '← Quay lại danh sách',
    'footer.copy': '© 2025 Minh Thuyết. Mọi quyền được bảo lưu.',
    'footer.backToTop': 'Lên đầu trang',
    'modal.close': 'Đóng'
  },
  en: {
    'nav.about': 'About',
    'nav.projects': 'Projects',
    'nav.services': 'Services',
    'nav.skills': 'Skills',
    'nav.testimonials': 'Testimonials',
    'nav.contact': 'Contact',
    'nav.themeToggle': 'Toggle light/dark mode',
    'hero.greeting': 'Hi, I’m',
    'hero.subhead': 'Real-estate creative • Brand Identity • Social Ads',
    'hero.ctaPrimary': 'Download CV',
    'hero.ctaSecondary': 'View Projects',
    'hero.typewriter':
      'I transform insight into business-effective design—focusing on real-estate branding, print assets, and cohesive social campaigns.',
    'hero.scroll': 'Scroll down',
    'about.title': 'Experience',
    'about.lead':
      'Over 3 years designing for real-estate and brand identity. Blending strategic thinking, modern aesthetics, and efficient workflow to deliver on-brief, on-time results.',
    'timeline.1.title': '11/2024 – 2025 · An Khang Real Estate Consulting & Investment',
    'timeline.1.item1': 'Built identity and key visuals for premium project clusters.',
    'timeline.1.item2': 'Coordinated digital/POSM assets between in-house teams and partner agencies.',
    'timeline.2.title': '05/2024 – 10/2024 · Nha Dai Phat Real Estate',
    'timeline.2.item1': 'Designed luxury project communication packs and standardized the visual guideline.',
    'timeline.2.item2': 'Produced social content, motion shorts, and edited campaign videos.',
    'timeline.3.title': '02/2024 – 05/2024 · Freelance Designer',
    'timeline.3.item1': 'Created social media posts aligned with campaign KPIs.',
    'timeline.3.item2': 'Developed brochures, banners, posters, and print assets for real-estate partners.',
    'timeline.4.title': '10/2022 – 01/2023 · Intern Designer — Toppion Coaching & Consulting Group',
    'timeline.4.item1': 'Designed covers for training materials, social posts, and internal documents.',
    'timeline.4.item2': 'Supported brochure, standee, and workshop collateral production.',
    'timeline.5.title': '02/2022 – 09/2022 · Part-time Designer — Hoang Duc Hai Co., Ltd.',
    'timeline.5.item1': 'Executed weekly social media campaigns.',
    'timeline.5.item2': 'Designed website visuals, landing banners, and optimized online experiences.',
    'metrics.projects': 'Projects delivered',
    'metrics.brands': 'Partner brands',
    'metrics.industries': 'Industries served',
    'metrics.solutions': 'Service solutions',
    'projects.title': 'Featured Projects',
    'projects.filter.all': 'All',
    'projects.filter.brand': 'Branding',
    'projects.filter.social': 'Social',
    'projects.filter.print': 'Print',
    'projects.prev': 'Previous projects',
    'projects.next': 'Next projects',
    'projects.view': 'View full case',
    'projects.modal.title': 'Case Study',
    'projects.modal.desc': 'A short summary covering objectives, insight, solution, and results.',
    'projects.modal.cta': 'View details',
    'footer.about.title': 'About us',
    'footer.about.link1': 'About',
    'footer.about.link2': 'Services',
    'footer.about.link3': 'Contact',
    'footer.projects.title': 'Projects',
    'footer.projects.link1': 'Main portfolio',
    'footer.projects.link2': 'Detailed case studies',
    'footer.projects.link3': 'Hear feedback',
    'footer.docs.title': 'Documents',
    'footer.docs.link1': 'Guidelines',
    'footer.docs.link2': 'Terms of use',
    'footer.docs.link3': 'Image rights',
    'footer.socials.title': 'Social media',
    'services.title': 'Services',
    'services.card1.title': 'Brand Identity',
    'services.card1.desc': 'Logo, palette, typography, guidelines, stationery, and identity applications.',
    'services.card1.item1': 'Research & positioning',
    'services.card1.item2': 'Key visual & applications',
    'services.card1.item3': 'Brandbook (PDF)',
    'services.card2.title': 'Social & Campaign',
    'services.card2.desc': 'Key visuals, banner kits, short videos, landing pages, content & A/B testing.',
    'services.card2.item1': 'Content & visual system',
    'services.card2.item2': 'Media kit',
    'services.card2.item3': 'Performance creative',
    'services.card3.title': 'Print & POSM',
    'services.card3.desc': 'Brochures, flyers, standees, OOH, 2D booths, production-ready deliverables.',
    'services.card3.item1': 'Print technique & materials',
    'services.card3.item2': 'Professional layout',
    'services.card3.item3': 'Press-ready files',
    'skills.title': 'Skills',
    'testimonials.eyebrow': 'Clients say',
    'testimonials.title': 'Clients talk about <span class=\"highlight\">Minh Thuyết</span>',
    'testimonials.lead': 'Trusted creative partner for Sales & Marketing teams across luxury real-estate projects.',
    'testimonials.quote1': '“Designs were polished and always on deadline. Social campaigns tripled engagement.”',
    'testimonials.meta1': 'Head of Marketing, Luxury Real Estate',
    'testimonials.quote2': '“Detailed brandbook, super smooth deployment across channels.”',
    'testimonials.meta2': 'CEO, Interior Brand',
    'testimonials.quote3': '“Modern visual thinking and great collaboration with our media team.”',
    'testimonials.meta3': 'Account Director, Agency',
    'testimonials.prev': 'Previous',
    'testimonials.next': 'Next',
    'contact.title': 'Contact',
    'contact.lead': 'Ready for the next project? Let’s align on goals, budget, and timeline.',
    'contact.cta': 'Contact',
    'contact.form.name': 'Full name',
    'contact.form.email': 'Email',
    'contact.form.subject': 'Subject',
    'contact.form.message': 'Message',
    'contact.form.submit': 'Send',
    'contact.form.copy': 'Copy email',
    'contact.form.zalo': 'Chat on Zalo',
    'contact.form.status.sending': 'Sending...',
    'contact.form.status.success': 'Sent! I’ll get back to you as soon as possible.',
    'contact.form.status.error': 'Submission failed. Please try again or email contact@anhthuyet.design.',
    'contact.form.copySuccess': 'Copied: {email}',
    'contact.form.copyManual': 'Auto-copy not available. Copy the email manually:',
    'contact.info.title': 'Contact info',
    'contact.info.addressLabel': 'Address',
    'contact.info.addressValue': 'Thu Duc – Ho Chi Minh City, Vietnam',
    'contact.info.phoneLabel': 'Phone',
    'contact.info.phoneValue': '(+84) 0912.275.643',
    'contact.info.emailLabel': 'Email',
    'contact.info.emailPrimary': 'hi@myntex.io.vn',
    'contact.info.emailSecondary': 'Thuyet.nguyenminh03@gmail.com',
    'contact.popup.title': 'Connect on Zalo',
    'contact.popup.desc': 'Scan the QR or tap the button below to chat on Zalo.',
    'contact.popup.qr': 'Zalo QR',
    'contact.popup.cta': 'Open Zalo',
    'case.breadcrumb.home': 'Home',
    'case.breadcrumb.projects': 'Projects',
    'case.meta.role': 'Role',
    'case.meta.time': 'Timeline',
    'case.meta.tools': 'Tools',
    'case.cta.consult': 'Share your thoughts',
    'case.cta.back': 'Back to portfolio',
    'case.related.title': 'Related projects',
    'case.related.desc': 'Discover more case studies tailored to your objectives.',
    'case.related.cta': 'View details',
    'case.brief.title': 'Project brief',
    'case.brief.challenge': 'Challenges',
    'case.brief.challengeText': 'Keep every touchpoint consistent across internal docs, digital channels, and sales decks on a tight timeline.',
    'case.brief.deliverables': 'Deliverables',
    'case.brief.item1': 'Omni-channel brand guideline & templates',
    'case.brief.item2': 'Social, brochure & POSM toolkit',
    'case.brief.item3': 'Operational & training kit',
    'case.final.eyebrow': 'Ready to collaborate',
    'case.final.title': 'Let’s build your next launch',
    'case.final.desc': 'I partner from strategy to execution to craft cohesive real-estate and brand experiences.',
    'case.objectivesTitle': 'Objectives & Challenges',
    'case.galleryTitle': 'Implementation Gallery',
    'case.policyTitle': 'Policies',
    'case.policyLabel': 'Policies',
    'case.floorTitle': 'Unit Layouts',
    'case.floorLabel': 'Unit layout',
    'case.resultsTitle': 'Key Results',
    'case.kpi1': 'Campaign CTR',
    'case.kpi2': 'Production time',
    'case.kpi3': 'Organic reach',
    'case.kpi4': 'Brand consistency',
    'case.placeholder': 'Updating soon',
    'case.notice': 'All visuals are copyrighted by MynTex. Do not reproduce or use without permission.',
    'case.back': '← Back to list',
    'footer.copy': '© 2025 Minh Thuyết. All rights reserved.',
    'footer.backToTop': 'Back to top',
    'modal.close': 'Close'
  }
};

const casePages = {
  internal: {
    vi: {
      title: "Thiết kế Ấn phẩm Nội bộ & Truyền thông",
      breadcrumbCurrent: "Thiết kế Ấn phẩm Nội bộ & Truyền thông",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Hệ ấn phẩm nội bộ và truyền thông giúp đồng bộ tiếng nói thương hiệu, từ handbook, newsletter đến tài liệu đào tạo.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Internal Collaterals & Communications",
      breadcrumbCurrent: "Internal Collaterals & Communications",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Internal collateral system covering handbooks, newsletters, and training decks for a unified brand voice.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  blanca: {
    vi: {
      title: "Blanca City",
      breadcrumbCurrent: "Blanca City",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Bộ nhận diện Blanca City với lockup, palette và grid sang trọng.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Blanca City",
      breadcrumbCurrent: "Blanca City",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Identity kit for Blanca City with a refined lockup, palette, and grid.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  latien: {
    vi: {
      title: "La Tiên Villa",
      breadcrumbCurrent: "La Tiên Villa",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Brochure La Tiên Villa phong cách Wabi-Sabi với layout tinh gọn, tập trung cảm xúc không gian.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "La Tiên Villa",
      breadcrumbCurrent: "La Tiên Villa",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Wabi-Sabi inspired brochure for La Tiên Villa with minimal layouts and emotive storytelling.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  ecopark: {
    vi: {
      title: "Ecopark",
      breadcrumbCurrent: "Ecopark",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Hệ nhận diện mang cảm hứng resort cao cấp cho Ecopark gồm guideline, POSM và social kit.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Ecopark",
      breadcrumbCurrent: "Ecopark",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Resort-inspired identity refresh for Ecopark covering guidelines, POSM, and social kits.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  lapura: {
    vi: {
      title: "Lapura",
      breadcrumbCurrent: "Lapura",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Trọn bộ nhận diện Lapura cho phân khúc an cư cao cấp: logo set, sales kit và brandbook.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Lapura",
      breadcrumbCurrent: "Lapura",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Complete identity for Lapura’s upscale residential line including logo set, sales kit, and brandbook.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  caraworld: {
    vi: {
      title: "Caraworld",
      breadcrumbCurrent: "Caraworld",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Visual system kể câu chuyện lifestyle Caraworld: social content, brochure và landing page.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Caraworld",
      breadcrumbCurrent: "Caraworld",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Lifestyle visual system for Caraworld spanning social content, brochure, and landing page.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  globalcity: {
    vi: {
      title: "The Global City",
      breadcrumbCurrent: "The Global City",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Launch assets đa nền tảng cho The Global City: KV chuyển động, social ads, sales deck và POSM.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "The Global City",
      breadcrumbCurrent: "The Global City",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Multi-channel launch assets for The Global City: animated KV, social ads, sales deck, and POSM.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  celadon: {
    vi: {
      title: "Celadon City",
      breadcrumbCurrent: "Celadon City",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Thiết kế trải nghiệm Celadon City gồm microsite, bản đồ tiện ích và brochure tương tác.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Celadon City",
      breadcrumbCurrent: "Celadon City",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Experience suite for Celadon City including microsite, amenity map, and interactive brochure.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  mteastmark: {
    vi: {
      title: "MT Eastmark City",
      breadcrumbCurrent: "MT Eastmark City",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2024–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Sales kit và performance ad set cho MT Eastmark City, tối ưu quy trình bán hàng.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "MT Eastmark City",
      breadcrumbCurrent: "MT Eastmark City",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2024–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Sales kit and performance ad set for MT Eastmark City to streamline the sales workflow.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  },
  miscprojects: {
    vi: {
      title: "Tổng Hợp Dự Án Nhỏ & Ấn Phẩm Hỗ Trợ",
      breadcrumbCurrent: "Tổng Hợp Dự Án Nhỏ & Ấn Phẩm Hỗ Trợ",
      metaRole: "Vai trò: Art Director · Designer",
      metaTime: "Thời gian: 2022–2025",
      metaTools: "Công cụ: AI, PS, ID, Figma",
      summary: "Tập hợp mini campaign, tài liệu đào tạo, POSM phụ trợ phục vụ vận hành thương hiệu.",
      objective: "Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.",
      challenge: "Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.",
      strategy: "Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.",
      workflow: "Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B."
    },
    en: {
      title: "Supporting Works & Collaterals",
      breadcrumbCurrent: "Supporting Works & Collaterals",
      metaRole: "Role: Art Director · Designer",
      metaTime: "Timeline: 2022–2025",
      metaTools: "Tools: AI, PS, ID, Figma",
      summary: "Collection of mini campaigns, training materials, and supporting POSM for day-to-day operations.",
      objective: "Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.",
      challenge: "Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.",
      strategy: "Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.",
      workflow: "Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing."
    }
  }
};

const caseShowcase = [
  {
    id: 'internal',
    link: 'p1.html',
    cover: '../assets/img/projects/ttnb/ttnb-cover.jpg',
    title: {
      vi: 'Thiết kế Ấn phẩm Nội bộ & Truyền thông',
      en: 'Internal Collaterals & Communications'
    },
    category: {
      vi: 'Ấn phẩm nội bộ',
      en: 'Internal collateral'
    }
  },
  {
    id: 'blanca',
    link: 'p2.html',
    cover: '../assets/img/projects/blanca/blanca-cover.jpg',
    title: { vi: 'Blanca City', en: 'Blanca City' },
    category: { vi: 'Brand identity', en: 'Brand identity' }
  },
  {
    id: 'latien',
    link: 'p3.html',
    cover: '../assets/img/projects/latien/latien-cover.jpg',
    title: { vi: 'La Tiên Villa', en: 'La Tiên Villa' },
    category: { vi: 'Brochure', en: 'Brochure' }
  },
  {
    id: 'ecopark',
    link: 'p4.html',
    cover: '../assets/img/projects/eco/eco-cover.jpg',
    title: { vi: 'Ecopark', en: 'Ecopark' },
    category: { vi: 'Social & Print', en: 'Social & Print' }
  },
  {
    id: 'lapura',
    link: 'p5.html',
    cover: '../assets/img/projects/lapura/lapura-cover.jpg',
    title: { vi: 'Lapura', en: 'Lapura' },
    category: { vi: 'Masterplan kit', en: 'Masterplan kit' }
  },
  {
    id: 'caraworld',
    link: 'p6.html',
    cover: '../assets/img/projects/caraworld/caraworld-cover.jpg',
    title: { vi: 'Caraworld', en: 'Caraworld' },
    category: { vi: 'Lifestyle content', en: 'Lifestyle content' }
  },
  {
    id: 'globalcity',
    link: 'p7.html',
    cover: '../assets/img/projects/tgc/tgc-cover.jpg',
    title: { vi: 'The Global City', en: 'The Global City' },
    category: { vi: 'Launch assets', en: 'Launch assets' }
  },
  {
    id: 'celadon',
    link: 'p8.html',
    cover: '../assets/img/projects/celadon/celadon-cover.jpg',
    title: { vi: 'Celadon City', en: 'Celadon City' },
    category: { vi: 'Community campaign', en: 'Community campaign' }
  },
  {
    id: 'mteastmark',
    link: 'p9.html',
    cover: '../assets/img/projects/mt/mt-cover.jpg',
    title: { vi: 'MT Eastmark City', en: 'MT Eastmark City' },
    category: { vi: 'Sales kit', en: 'Sales kit' }
  },
  {
    id: 'miscprojects',
    link: 'p10.html',
    cover: '../assets/img/projects/eaton/eaton-cover.jpg',
    title: {
      vi: 'Tổng Hợp Dự Án Nhỏ & Ấn Phẩm Hỗ Trợ',
      en: 'Supporting Collateral Collection'
    },
    category: { vi: 'Mixed projects', en: 'Mixed projects' }
  }
];

const caseMediaCache = new Map();
const langButtons = document.querySelectorAll('.lang-btn');
let currentLang = localStorage.getItem('lang') || 'vi';

const formatText = (text, vars = {}) => {
  if (!text) return '';
  let output = text;
  Object.entries(vars).forEach(([key, value]) => {
    output = output.replace(new RegExp(`\\{${key}\\}`, 'g'), value);
  });
  return output;
};

const getTextValue = (lang, key) => translations[lang]?.[key];

const translate = (key, vars = {}) => {
  const value = getTextValue(currentLang, key) ?? getTextValue('vi', key) ?? '';
  return formatText(value, vars);
};

const relatedGrid = document.querySelector('[data-related-grid]');
let updateBackToTopLabel = () => {};

const buildRelatedList = () => {
  if (!document.body.classList.contains('case-page')) return [];
  const current = document.body.dataset.case;
  if (!current) return [];
  const currentIndex = caseShowcase.findIndex((item) => item.id === current);
  const ordered =
    currentIndex >= 0
      ? [...caseShowcase.slice(currentIndex + 1), ...caseShowcase.slice(0, currentIndex + 1)]
      : [...caseShowcase];
  return ordered.filter((item) => item.id !== current).slice(0, 6);
};

const renderRelatedProjects = () => {
  if (!relatedGrid) return;
  const suggestions = buildRelatedList();
  if (!suggestions.length) {
    relatedGrid.innerHTML = '';
    return;
  }
  const ctaText = translate('case.related.cta') || 'Xem chi tiết';
  relatedGrid.innerHTML = suggestions
    .map((item) => {
      const title = item.title[currentLang] ?? item.title.vi ?? '';
      const category = item.category?.[currentLang] ?? item.category?.vi ?? '';
      return `
      <article class="case-related-card">
        <div class="case-related-cover">
          <img src="${item.cover}" alt="${title}" loading="lazy">
        </div>
        <div class="case-related-body">
          <p class="case-related-tag">${category}</p>
          <h3>${title}</h3>
          <a class="btn" href="${item.link}">${ctaText}</a>
        </div>
      </article>`;
    })
    .join('');
};

const fetchCaseMedia = async (caseId) => {
  if (caseMediaCache.has(caseId)) return caseMediaCache.get(caseId);
  try {
    const response = await fetch(`../api/project.php?slug=${encodeURIComponent(caseId)}`);
    if (!response.ok) throw new Error('fetch media failed');
    const payload = await response.json();
    caseMediaCache.set(caseId, payload.media || {});
    return payload.media || {};
  } catch (error) {
    console.error('Unable to load project media', error);
    caseMediaCache.set(caseId, {});
    return {};
  }
};

const renderCaseMedia = async () => {
  if (!document.body.classList.contains('case-page')) return;
  const caseId = document.body.dataset.case;
  if (!caseId) return;
  const media = await fetchCaseMedia(caseId);
  if (!media) return;
  const titleLookup =
    casePages[caseId]?.[currentLang]?.title || casePages[caseId]?.vi?.title || document.querySelector('.case-hero h1')?.textContent || '';
  document.querySelectorAll('[data-gallery]').forEach((container) => {
    const key = container.dataset.gallery;
    const files = media[key];
    if (!Array.isArray(files) || files.length === 0) return;
    const brand = container.dataset.brand || 'MynTex';
    const tagText = container.dataset.tagI18n ? translate(container.dataset.tagI18n) : container.dataset.tag || 'Project';
    container.innerHTML = '';
    files.forEach((file, index) => {
      const card = document.createElement('article');
      card.className = 'gallery-card';
      const img = document.createElement('img');
      const src = file.startsWith('http') ? file : `../assets/img/projects/${caseId}/${file}`;
      img.src = src;
      img.alt = `${titleLookup} - ${tagText} ${index + 1}`;
      card.appendChild(img);
      const footer = document.createElement('footer');
      const brandSpan = document.createElement('span');
      brandSpan.textContent = brand;
      const tagSpan = document.createElement('span');
      tagSpan.textContent = tagText;
      footer.appendChild(brandSpan);
      footer.appendChild(tagSpan);
      card.appendChild(footer);
      container.appendChild(card);
    });
  });
};

const applyLanguage = (lang) => {
  if (!translations[lang]) lang = 'vi';
  currentLang = lang;
  document.documentElement.setAttribute('lang', lang);
  langButtons.forEach((btn) => btn.classList.toggle('active', btn.dataset.lang === lang));
  localStorage.setItem('lang', lang);
  document.querySelectorAll('[data-i18n]').forEach((el) => {
    const value = getTextValue(lang, el.dataset.i18n);
    if (value !== undefined) el.textContent = formatText(value);
  });
  document.querySelectorAll('[data-i18n-html]').forEach((el) => {
    const value = getTextValue(lang, el.dataset.i18nHtml);
    if (value !== undefined) el.innerHTML = value;
  });
  document.querySelectorAll('[data-i18n-attr]').forEach((el) => {
    el.dataset.i18nAttr.split(',').forEach((mapping) => {
      const [attr, key] = mapping.split(':').map((part) => part.trim());
      if (!attr || !key) return;
      const value = getTextValue(lang, key);
      if (value !== undefined) el.setAttribute(attr, formatText(value));
    });
  });
  document.querySelectorAll('[data-i18n-typewriter]').forEach((el) => {
    const value = getTextValue(lang, el.dataset.i18nTypewriter);
    if (value !== undefined) {
      el.dataset.text = value;
      initTypewriter();
    }
  });
  applyCaseContent();
  renderRelatedProjects();
  renderCaseMedia();
  updateBackToTopLabel();
};

langButtons.forEach((btn) => {
  btn.addEventListener('click', () => {
    if (btn.dataset.lang && btn.dataset.lang !== currentLang) {
      applyLanguage(btn.dataset.lang);
    }
  });
});

applyLanguage(currentLang);

function applyCaseContent() {
  const caseId = document.body.dataset.case;
  if (!caseId) return;
  const data = casePages[caseId]?.[currentLang] || casePages[caseId]?.vi;
  if (!data) return;
  if (data.title) {
    document.title = `${data.title} – Case Study`;
  }
  document.querySelectorAll('[data-case-key]').forEach((el) => {
    const key = el.dataset.caseKey;
    const value = data[key];
    if (value !== undefined) el.textContent = value;
  });
}

// Case contact modal
if (document.body.classList.contains('case-page')) {
  const contactModal = document.getElementById('projectContactModal');
  const contactForm = document.getElementById('projectContactForm');
  const contactStatus = document.getElementById('projectContactStatus');
  const openButtons = document.querySelectorAll('[data-open-contact]');
  if (contactModal && contactForm && contactStatus && openButtons.length) {
    const closeCaseModal = () => {
      contactModal.classList.remove('open');
      document.body.classList.remove('modal-open');
      contactStatus.textContent = '';
      contactStatus.classList.remove('error');
    };
    openButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        contactModal.classList.add('open');
        document.body.classList.add('modal-open');
      });
    });
    contactModal.addEventListener('click', (event) => {
      if (event.target.dataset.close === 'true') {
        closeCaseModal();
      }
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && contactModal.classList.contains('open')) {
        closeCaseModal();
      }
    });
    contactForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      contactStatus.textContent = translate('contact.form.status.sending');
      contactStatus.classList.remove('error');
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      submitBtn?.setAttribute('disabled', 'true');
      try {
        const response = await fetch(contactForm.action, {
          method: 'POST',
          headers: { Accept: 'application/json' },
          body: new FormData(contactForm)
        });
        if (!response.ok) throw new Error('submit fail');
        contactForm.reset();
        contactStatus.textContent = translate('contact.form.status.success');
      } catch (error) {
        contactStatus.textContent = translate('contact.form.status.error');
        contactStatus.classList.add('error');
      } finally {
        submitBtn?.removeAttribute('disabled');
      }
    });
  }
}
// Scroll progress
const ensureScrollProgress = () => {
  let bar = document.querySelector('.scroll-progress');
  if (!bar) {
    bar = document.createElement('div');
    bar.className = 'scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.prepend(bar);
  }
  const update = () => {
    const doc = document.documentElement;
    const height = doc.scrollHeight - doc.clientHeight;
    const value = height > 0 ? (doc.scrollTop / height) * 100 : 0;
    bar.style.setProperty('--progress', `${value}%`);
    rootEl.style.setProperty('--scroll-progress', (value / 100).toFixed(4));
  };
  let ticking = false;
  document.addEventListener(
    'scroll',
    () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        update();
        ticking = false;
      });
    },
    { passive: true }
  );
  update();
};
ensureScrollProgress();
const ensureBackToTop = () => {
  let button = document.querySelector('.back-to-top');
  if (!button) {
    button = document.createElement('button');
    button.type = 'button';
    button.className = 'back-to-top';
    button.innerHTML = `
      <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
        <path d="M12 5.5l6.2 6.3-1.4 1.4L13 8.4V19h-2V8.4l-3.8 4.8-1.4-1.4z" fill="currentColor"/>
      </svg>
      <span class="sr-only"></span>
    `;
    document.body.append(button);
  }
  const sr = button.querySelector('.sr-only');
  updateBackToTopLabel = () => {
    const label = translate('footer.backToTop') || 'Lên đầu trang';
    button.setAttribute('aria-label', label);
    if (sr) sr.textContent = label;
  };
  updateBackToTopLabel();
  const toggle = () => {
    const shouldShow = window.scrollY > 280;
    button.classList.toggle('show', shouldShow);
  };
  let ticking = false;
  document.addEventListener(
    'scroll',
    () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        toggle();
        ticking = false;
      });
    },
    { passive: true }
  );
  toggle();
  button.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: motionBehavior() });
  });
};
ensureBackToTop();
const initFooterLogoShine = () => {
  if (!footerLogo) return;
  let footerInterval = null;
  const start = () => {
    if (footerInterval) return;
    playShine(footerLogo);
    footerInterval = window.setInterval(() => playShine(footerLogo), 5200);
  };
  const stop = () => {
    if (!footerInterval) return;
    clearInterval(footerInterval);
    footerInterval = null;
  };
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) start();
        else stop();
      });
    },
    { threshold: 0.4 }
  );
  observer.observe(footerLogo);
};
initFooterLogoShine();

// Reveal on scroll
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.12 }
);
document.querySelectorAll('.reveal-up').forEach((el) => revealObserver.observe(el));

// Section ambient glow on scroll
const sectionObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      entry.target.classList.toggle('in-view', entry.isIntersecting);
    });
  },
  { threshold: 0.35 }
);
document.querySelectorAll('.section').forEach((section) => sectionObserver.observe(section));

// Counters
const counters = document.querySelectorAll('.m-num');
const counterObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = Number(el.dataset.count) || 0;
      const start = performance.now();
      const duration = 1200;
      const step = (time) => {
        const progress = Math.min((time - start) / duration, 1);
        el.textContent = Math.round(target * progress).toLocaleString('vi-VN');
        if (progress < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
      counterObserver.unobserve(el);
    });
  },
  { threshold: 0.5 }
);
counters.forEach((el) => counterObserver.observe(el));

// Hero parallax
const heroCard = document.querySelector('.hero-art .card');
const heroCopy = document.querySelector('.hero-copy');
if (heroCard && heroCopy) {
  heroCard.style.setProperty('--hero-card-x', '0px');
  heroCard.style.setProperty('--hero-card-y', '0px');
  heroCopy.style.setProperty('--hero-copy-x', '0px');
  heroCopy.style.setProperty('--hero-copy-y', '0px');
}

// Chip glow animation
const chipRow = document.querySelector('.chip-row');
if (chipRow) {
  const chips = [...chipRow.querySelectorAll('.chip')];
  if (chips.length) {
    const updateGlow = (index) => {
      const target = chips[index % chips.length];
      const glowWidth = target.offsetWidth + 20;
      chipRow.style.setProperty('--chip-glow-width', `${glowWidth}px`);
      const offset = target.offsetLeft + (target.offsetWidth - glowWidth) / 2;
      chipRow.style.setProperty('--chip-glow-offset', `${offset}px`);
    };
    let chipIndex = 0;
    const highlight = () => {
      updateGlow(chipIndex);
      chipIndex += 1;
    };
    highlight();
    setInterval(highlight, 2200);
    window.addEventListener('resize', () => updateGlow(chipIndex));
  }
}

const projectCases = {
  internal: {
    vi: {
      title: "Thiết kế Ấn phẩm Nội bộ & Truyền thông",
      desc: "Hệ ấn phẩm nội bộ và truyền thông giúp đồng bộ tiếng nói thương hiệu, từ handbook, newsletter đến tài liệu đào tạo."
    },
    en: {
      title: "Internal Collaterals & Communications",
      desc: "Internal collateral system covering handbooks, newsletters, and training decks for a unified brand voice."
    }
  },
  blanca: {
    vi: {
      title: "Blanca City",
      desc: "Bộ nhận diện Blanca City với lockup, palette và grid sang trọng."
    },
    en: {
      title: "Blanca City",
      desc: "Identity kit for Blanca City with a refined lockup, palette, and grid."
    }
  },
  latien: {
    vi: {
      title: "La Tiên Villa",
      desc: "Brochure La Tiên Villa phong cách Wabi-Sabi với layout tinh gọn, tập trung cảm xúc không gian."
    },
    en: {
      title: "La Tiên Villa",
      desc: "Wabi-Sabi inspired brochure for La Tiên Villa with minimal layouts and emotive storytelling."
    }
  },
  ecopark: {
    vi: {
      title: "Ecopark",
      desc: "Hệ nhận diện mang cảm hứng resort cao cấp cho Ecopark gồm guideline, POSM và social kit."
    },
    en: {
      title: "Ecopark",
      desc: "Resort-inspired identity refresh for Ecopark covering guidelines, POSM, and social kits."
    }
  },
  lapura: {
    vi: {
      title: "Lapura",
      desc: "Trọn bộ nhận diện Lapura cho phân khúc an cư cao cấp: logo set, sales kit và brandbook."
    },
    en: {
      title: "Lapura",
      desc: "Complete identity for Lapura’s upscale residential line including logo set, sales kit, and brandbook."
    }
  },
  caraworld: {
    vi: {
      title: "Caraworld",
      desc: "Visual system kể câu chuyện lifestyle Caraworld: social content, brochure và landing page."
    },
    en: {
      title: "Caraworld",
      desc: "Lifestyle visual system for Caraworld spanning social content, brochure, and landing page."
    }
  },
  globalcity: {
    vi: {
      title: "The Global City",
      desc: "Launch assets đa nền tảng cho The Global City: KV chuyển động, social ads, sales deck và POSM."
    },
    en: {
      title: "The Global City",
      desc: "Multi-channel launch assets for The Global City: animated KV, social ads, sales deck, and POSM."
    }
  },
  celadon: {
    vi: {
      title: "Celadon City",
      desc: "Thiết kế trải nghiệm Celadon City gồm microsite, bản đồ tiện ích và brochure tương tác."
    },
    en: {
      title: "Celadon City",
      desc: "Experience suite for Celadon City including microsite, amenity map, and interactive brochure."
    }
  },
  mteastmark: {
    vi: {
      title: "MT Eastmark City",
      desc: "Sales kit và performance ad set cho MT Eastmark City, tối ưu quy trình bán hàng."
    },
    en: {
      title: "MT Eastmark City",
      desc: "Sales kit and performance ad set for MT Eastmark City to streamline the sales workflow."
    }
  },
  miscprojects: {
    vi: {
      title: "Tổng Hợp Dự Án Nhỏ & Ấn Phẩm Hỗ Trợ",
      desc: "Tập hợp mini campaign, tài liệu đào tạo, POSM phụ trợ phục vụ vận hành thương hiệu."
    },
    en: {
      title: "Supporting Works & Collaterals",
      desc: "Collection of mini campaigns, training materials, and supporting POSM for day-to-day operations."
    }
  }
};

const filterButtons = document.querySelectorAll('.filters .chip');
const pointerFine = window.matchMedia('(pointer: fine)').matches;
const projectSlider = document.querySelector('[data-project-slider]');
const projectGrid = document.querySelector('.project-grid');
const originalProjectCards = projectGrid
  ? [...projectGrid.children].map((card) => ({
      template: card.outerHTML,
      cat: card.dataset.cat || 'all'
    }))
  : [];
let activeProjectFilter = 'all';
let currentProjectCards = [];
let sliderOffset = 0;
let sliderGap = 0;
let sliderPaused = false;
const sliderSpeed = 0.85;

const applyCardTilt = () => {
  if (!pointerFine) return;
  currentProjectCards.forEach((card) => {
    const resetTilt = () => {
      card.style.setProperty('--tiltX', '0deg');
      card.style.setProperty('--tiltY', '0deg');
    };
    card.addEventListener('pointermove', (event) => {
      const rect = card.getBoundingClientRect();
      const x = ((event.clientX - rect.left) / rect.width - 0.5) * 10;
      const y = ((event.clientY - rect.top) / rect.height - 0.5) * -10;
      card.style.setProperty('--tiltX', `${x.toFixed(2)}deg`);
      card.style.setProperty('--tiltY', `${y.toFixed(2)}deg`);
    });
    card.addEventListener('pointerleave', resetTilt);
    card.addEventListener('pointerup', resetTilt);
  });
};

const rebuildProjectTrack = () => {
  if (!projectGrid) return;
  const dataset =
    originalProjectCards.length === 0
      ? []
      : originalProjectCards.filter((card) => activeProjectFilter === 'all' || card.cat === activeProjectFilter);
  const source = dataset.length ? dataset : originalProjectCards;
  projectGrid.innerHTML = source.map((item) => item.template).join('');
  if (projectGrid.children.length < 2) {
    projectGrid.innerHTML += source.map((item) => item.template).join('');
  }
  currentProjectCards = [...projectGrid.querySelectorAll('.project-card')];
  currentProjectCards.forEach((card) => card.classList.remove('reveal-up', 'visible'));
  sliderOffset = 0;
  projectGrid.style.transform = 'translateX(0)';
  sliderGap = parseFloat(getComputedStyle(projectGrid).columnGap || getComputedStyle(projectGrid).gap || 0) || 0;
  applyCardTilt();
};

filterButtons.forEach((btn) => {
  btn.addEventListener('click', () => {
    filterButtons.forEach((b) => b.classList.remove('active'));
    btn.classList.add('active');
    activeProjectFilter = btn.dataset.filter || 'all';
    rebuildProjectTrack();
  });
});

const runProjectSlider = () => {
  if (!projectGrid || !projectGrid.children.length) {
    requestAnimationFrame(runProjectSlider);
    return;
  }
  if (!sliderPaused) {
    sliderOffset -= sliderSpeed;
    projectGrid.style.transform = `translateX(${sliderOffset}px)`;
    const firstCard = projectGrid.children[0];
    if (firstCard) {
      const cardWidth = firstCard.getBoundingClientRect().width;
      if (-sliderOffset >= cardWidth + sliderGap) {
        projectGrid.appendChild(firstCard);
        sliderOffset += cardWidth + sliderGap;
      }
    }
  }
  requestAnimationFrame(runProjectSlider);
};

rebuildProjectTrack();
runProjectSlider();
window.addEventListener(
  'resize',
  throttle(() => {
    sliderGap = parseFloat(getComputedStyle(projectGrid).columnGap || getComputedStyle(projectGrid).gap || 0) || 0;
  }, 300)
);

projectSlider?.addEventListener('mouseenter', () => {
  sliderPaused = true;
});
projectSlider?.addEventListener('mouseleave', () => {
  sliderPaused = false;
});
projectSlider?.addEventListener(
  'touchstart',
  () => {
    sliderPaused = true;
  },
  { passive: true }
);
projectSlider?.addEventListener(
  'touchend',
  () => {
    sliderPaused = false;
  },
  { passive: true }
);

// Drag functionality for manual scrolling
let isDragging = false;
let startX = 0;
let currentX = 0;
let dragOffset = 0;

projectGrid.addEventListener('mousedown', (e) => {
  isDragging = true;
  startX = e.clientX;
  currentX = e.clientX;
  dragOffset = sliderOffset;
  sliderPaused = true;
  projectGrid.style.cursor = 'grabbing';
  e.preventDefault();
});

document.addEventListener('mousemove', (e) => {
  if (!isDragging) return;
  const deltaX = e.clientX - currentX;
  currentX = e.clientX;
  dragOffset += deltaX;
  projectGrid.style.transform = `translateX(${dragOffset}px)`;
});

document.addEventListener('mouseup', () => {
  if (!isDragging) return;
  isDragging = false;
  sliderOffset = dragOffset;
  sliderPaused = false;
  projectGrid.style.cursor = '';
});

projectGrid.addEventListener('touchstart', (e) => {
  isDragging = true;
  startX = e.touches[0].clientX;
  currentX = e.touches[0].clientX;
  dragOffset = sliderOffset;
  sliderPaused = true;
  e.preventDefault();
}, { passive: false });

document.addEventListener('touchmove', (e) => {
  if (!isDragging) return;
  const deltaX = e.touches[0].clientX - currentX;
  currentX = e.touches[0].clientX;
  dragOffset += deltaX;
  projectGrid.style.transform = `translateX(${dragOffset}px)`;
}, { passive: false });

document.addEventListener('touchend', () => {
  if (!isDragging) return;
  isDragging = false;
  sliderOffset = dragOffset;
  sliderPaused = false;
});

const modal = document.getElementById('modal');
const modalTitle = document.getElementById('modalTitle');
const modalDesc = document.getElementById('modalDesc');
const modalImg = document.getElementById('modalImg');
const modalBtn = document.getElementById('modalBtn');
const modalCloseBtn = modal?.querySelector('.modal-close');
let modalFocusableItems = [];
let lastFocusedElement = null;

const focusableSelectors = 'a[href], button:not([disabled])';

const openModal = (caseId, trigger) => {
  if (!modal || !modalTitle || !modalDesc || !modalImg || !modalBtn) return;
  const localizedData = projectCases[caseId]?.[currentLang] || projectCases[caseId]?.vi || {};
  const card = trigger.closest('.project-card');
  const cardTitle = card?.querySelector('h3')?.textContent?.trim();
  const cardTags = card?.querySelector('.p-tags')?.textContent?.trim();
  const coverImg = card?.querySelector('img');

  modalTitle.textContent = localizedData.title || cardTitle || translate('projects.modal.title');
  modalDesc.textContent =
    localizedData.desc || cardTags || translate('projects.modal.desc');
  if (coverImg) {
    modalImg.src = coverImg.src;
    modalImg.alt = coverImg.alt || modalTitle.textContent;
  }
  modalBtn.href = trigger.href;

  lastFocusedElement = document.activeElement;
  modal.classList.add('open');
  modal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('modal-open');

  modalFocusableItems = [...modal.querySelectorAll(focusableSelectors)];
  (modalFocusableItems[0] || modal).focus();
};

const closeModal = () => {
  if (!modal) return;
  modal.classList.remove('open');
  modal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('modal-open');
  modalFocusableItems = [];
  lastFocusedElement?.focus();
};

document.addEventListener('click', (event) => {
  const btn = event.target.closest('.p-view');
  if (!btn) return;
  if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
  if (!modal) return;
  const id = btn.dataset.case;
  if (!id) return;
  event.preventDefault();
  openModal(id, btn);
});

modal?.addEventListener('click', (event) => {
  if (event.target.dataset.close === 'true') {
    closeModal();
  }
});

modalCloseBtn?.addEventListener('click', closeModal);

modal?.addEventListener('keydown', (event) => {
  if (event.key !== 'Tab' || modalFocusableItems.length < 2) return;
  const first = modalFocusableItems[0];
  const last = modalFocusableItems[modalFocusableItems.length - 1];
  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault();
    last.focus();
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault();
    first.focus();
  }
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && modal?.classList.contains('open')) {
    closeModal();
  }
});

// Slider / testimonials
const sliderEl = document.getElementById('slider');
if (sliderEl) {
  const slides = [...sliderEl.querySelectorAll('.slide')];
  const nextBtn = sliderEl.querySelector('.slider-next');
  const prevBtn = sliderEl.querySelector('.slider-prev');
  const progressSpan = sliderEl.querySelector('.slider-progress span');
  const sliderThemes = ['violet', 'teal', 'rose'];
  let sliderIndex = slides.findIndex((slide) => slide.classList.contains('active'));
  if (sliderIndex < 0) sliderIndex = 0;
  let autoTimer = null;
  let pointerStart = null;
  const isControl = (el) => el?.closest?.('.slider-prev, .slider-next');

  const setSlide = (index) => {
    if (!slides.length) return;
    sliderIndex = (index + slides.length) % slides.length;
    slides.forEach((slide, idx) => {
      const active = idx === sliderIndex;
      slide.classList.toggle('active', active);
      slide.setAttribute('aria-hidden', String(!active));
    });
    if (progressSpan) {
      const progress = slides.length ? ((sliderIndex + 1) / slides.length) * 100 : 0;
      progressSpan.style.width = `${progress}%`;
    }
    sliderEl.dataset.theme = sliderThemes[sliderIndex % sliderThemes.length];
  };

  const nextSlide = () => {
    if (slides.length < 2) return;
    setSlide(sliderIndex + 1);
  };
  const prevSlide = () => {
    if (slides.length < 2) return;
    setSlide(sliderIndex - 1);
  };

  const stopAuto = () => {
    if (autoTimer) {
      clearInterval(autoTimer);
      autoTimer = null;
    }
  };

  const startAuto = () => {
    if (slides.length < 2) return;
    stopAuto();
    autoTimer = window.setInterval(nextSlide, 3200);
  };

  nextBtn?.addEventListener('click', () => {
    nextSlide();
    startAuto();
  });
  prevBtn?.addEventListener('click', () => {
    prevSlide();
    startAuto();
  });

  sliderEl.addEventListener('pointerdown', (event) => {
    if (slides.length < 2 || isControl(event.target)) return;
    pointerStart = event.clientX;
    sliderEl.setPointerCapture?.(event.pointerId);
    stopAuto();
  });

  sliderEl.addEventListener('pointerup', (event) => {
    if (isControl(event.target)) return;
    if (pointerStart === null) return;
    const delta = event.clientX - pointerStart;
    if (Math.abs(delta) > 40) {
      delta > 0 ? prevSlide() : nextSlide();
    }
    pointerStart = null;
    sliderEl.releasePointerCapture?.(event.pointerId);
    startAuto();
  });
  sliderEl.addEventListener('pointercancel', (event) => {
    if (isControl(event.target)) return;
    pointerStart = null;
    startAuto();
  });

  sliderEl.addEventListener('mouseenter', stopAuto);
  sliderEl.addEventListener('mouseleave', startAuto);
  sliderEl.addEventListener('focusin', stopAuto);
  sliderEl.addEventListener('focusout', () => {
    if (!sliderEl.contains(document.activeElement)) startAuto();
  });

  slides.forEach((slide, idx) => {
    slide.setAttribute('aria-hidden', String(idx !== sliderIndex));
  });
  setSlide(sliderIndex);
  startAuto();
}

// Smooth anchors
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  const href = anchor.getAttribute('href');
  if (!href || href.length <= 1) return;
  const targetId = href.slice(1);
  anchor.addEventListener('click', (event) => {
    const target = document.getElementById(targetId);
    if (!target) return;
    event.preventDefault();
    target.scrollIntoView({ behavior: motionBehavior(), block: 'start' });
    if (navMenu && navToggle) {
      navMenu.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    }
  });
});

// Download CV (print resume section)
document.getElementById('downloadCV')?.addEventListener('click', (event) => {
  event.preventDefault();
  window.print();
});

// Copy email
const copyEmailBtn = document.getElementById('copyEmail');
if (copyEmailBtn) {
  copyEmailBtn.addEventListener('click', () => {
    const email = 'hi@myntex.io.vn';
    if (navigator.clipboard?.writeText) {
      navigator.clipboard
        .writeText(email)
        .then(() => alert(translate('contact.form.copySuccess', { email })))
        .catch(() => window.prompt(translate('contact.form.copyManual'), email));
    } else {
      window.prompt(translate('contact.form.copyManual'), email);
    }
  });
}

// Contact form -> Formspree submit
const contactForm = document.getElementById('contactForm');
const formStatus = document.getElementById('formStatus');
let formToast = document.querySelector('.form-toast');
const showFormToast = (message, isError = false) => {
  if (!formToast) {
    formToast = document.createElement('div');
    formToast.className = 'form-toast';
    document.body.appendChild(formToast);
  }
  formToast.textContent = message;
  formToast.classList.toggle('error', isError);
  formToast.classList.add('show');
  window.setTimeout(() => formToast?.classList.remove('show'), 2500);
};
if (contactForm) {
  contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (formStatus) {
      formStatus.textContent = translate('contact.form.status.sending');
      formStatus.classList.remove('error');
    }
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    submitBtn?.setAttribute('disabled', 'true');
    try {
      const response = await fetch(contactForm.action, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(contactForm)
      });
      if (!response.ok) throw new Error('Form submit failed');
      contactForm.reset();
      const successMsg = translate('contact.form.status.success');
      if (formStatus) formStatus.textContent = successMsg;
      showFormToast(successMsg);
    } catch (error) {
      const errorMsg = translate('contact.form.status.error');
      if (formStatus) {
        formStatus.textContent = errorMsg;
        formStatus.classList.add('error');
      }
      showFormToast(errorMsg, true);
    } finally {
      submitBtn?.removeAttribute('disabled');
    }
  });
}

const heroSection = document.getElementById('hero');
if (heroSection) {
  const updateHeroParallax = () => {
    const rect = heroSection.getBoundingClientRect();
    const progress = Math.min(Math.max((window.innerHeight - rect.top) / (window.innerHeight + rect.height), 0), 1);
    rootEl.style.setProperty('--hero-parallax', progress.toFixed(3));
  };
  const throttledHero = throttle(updateHeroParallax, 60);
  window.addEventListener('scroll', throttledHero, { passive: true });
  updateHeroParallax();
}

// Back to top buttons
document.querySelectorAll('.back-to-top').forEach((btn) => {
  btn.addEventListener('click', (event) => {
    event.preventDefault();
    window.scrollTo({ top: 0, behavior: motionBehavior() });
  });
});

// Zalo popup
const zaloBtn = document.getElementById('zaloContact');
const zaloPopup = document.getElementById('zaloPopup');
if (zaloBtn && zaloPopup) {
  const zaloLink = zaloBtn.dataset.link || 'https://zalo.me/0912275643';
  const closeZalo = () => {
    zaloPopup.classList.remove('open');
    zaloPopup.setAttribute('aria-hidden', 'true');
  };
  zaloBtn.addEventListener('click', () => {
    window.open(zaloLink, '_blank', 'noopener');
  });
  zaloPopup.querySelector('.btn')?.setAttribute('href', zaloLink);
  zaloPopup.addEventListener('click', (event) => {
    if (event.target.dataset.close === 'true') {
      closeZalo();
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && zaloPopup.classList.contains('open')) {
      closeZalo();
    }
  });
}

// Typewriter effect for lede

// Case image lightbox
const caseGrids = document.querySelectorAll('.case-page .case-grid, .case-page .case-gallery');
if (caseGrids.length) {
  const lightbox = document.createElement('div');
  lightbox.className = 'case-lightbox';
  lightbox.innerHTML = `
    <div class="case-lightbox__backdrop" data-close="true"></div>
    <div class="case-lightbox__dialog">
      <button class="case-lightbox__nav case-lightbox__nav--prev" data-nav="prev">&#8249;</button>
      <button class="case-lightbox__nav case-lightbox__nav--next" data-nav="next">&#8250;</button>
      <button class="case-lightbox__close" data-close="true">&times;</button>
      <img alt="" />
      <p class="case-lightbox__caption"></p>
    </div>`;
  document.body.appendChild(lightbox);
  const lightboxImg = lightbox.querySelector('img');
  const lightboxCaption = lightbox.querySelector('.case-lightbox__caption');
  const navPrev = lightbox.querySelector('[data-nav="prev"]');
  const navNext = lightbox.querySelector('[data-nav="next"]');
  let currentList = [];
  let currentIndex = 0;
  const closeLightbox = () => {
    lightbox.classList.remove('open');
    document.body.classList.remove('modal-open');
  };
  lightbox.addEventListener('click', (event) => {
    if (event.target.dataset.close === 'true') {
      closeLightbox();
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && lightbox.classList.contains('open')) {
      closeLightbox();
    } else if (event.key === 'ArrowRight' && currentList.length) {
      currentIndex = (currentIndex + 1) % currentList.length;
      updateLightbox();
    } else if (event.key === 'ArrowLeft' && currentList.length) {
      currentIndex = (currentIndex - 1 + currentList.length) % currentList.length;
      updateLightbox();
    }
  });
  const updateLightbox = () => {
    const target = currentList[currentIndex];
    if (!target) return;
    lightboxImg.src = target.src;
    lightboxImg.alt = target.alt || '';
    lightboxCaption.textContent = target.alt || 'Hình ảnh triển khai';
  };
  navPrev?.addEventListener('click', () => {
    if (!currentList.length) return;
    currentIndex = (currentIndex - 1 + currentList.length) % currentList.length;
    updateLightbox();
  });
  navNext?.addEventListener('click', () => {
    if (!currentList.length) return;
    currentIndex = (currentIndex + 1) % currentList.length;
    updateLightbox();
  });
  caseGrids.forEach((grid) => {
    const imgs = [...grid.querySelectorAll('img')];
    imgs.forEach((img, idx) => {
      img.addEventListener('click', () => {
        currentList = imgs;
        currentIndex = idx;
        updateLightbox();
        lightbox.classList.add('open');
        document.body.classList.add('modal-open');
      });
    });
  });
}
