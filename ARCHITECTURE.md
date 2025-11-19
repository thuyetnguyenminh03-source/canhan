# 🏗️ Kiến Trúc Myntex.io.vn

## 📐 Sơ Đồ Hệ Thống

```
┌─────────────────────────────────────────────────────────────────┐
│                     MYNTEX.IO.VN                                │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────┐           ┌──────────────────────────┐
│   WEB FRONTEND          │           │   ADMIN PANEL            │
│   (Public)              │           │   (Private)              │
├─────────────────────────┤           ├──────────────────────────┤
│ index.html              │           │ admin/login.php          │
│ assets/css/style.css    │           │ admin/dashboard.php      │
│ assets/js/main.js       │           │ admin/projects.php       │
│ assets/js/load-projects.js          │ admin/services.php       │
│ assets/js/dark-mode.js  │           │ admin/testimonials.php   │
│ assets/js/image-modal.js            │ admin/timeline.php       │
└─────────────────────────┘           │ admin/skills.php         │
           ↓                          │ admin/contact.php        │
        ┌──────────┐                  └──────────────────────────┘
        │   APIS   │                           ↓
        ├──────────┤                  ┌──────────────────────────┐
        │ /api/    │                  │  SESSION/AUTH            │
        │ site.php │─────────────────→│ bootstrap.php            │
        │          │                  │ login.php (verify)       │
        │project.php                  │ CSRF tokens              │
        │          │                  └──────────────────────────┘
        └──────────┘                           ↓
           ↓                          ┌──────────────────────────┐
        ┌──────────────────────────┐  │  PASSWORD MANAGEMENT     │
        │   DATABASE               │  │  password-manager.php    │
        │   MYSQL                  │  │  change-admin-password   │
        ├──────────────────────────┤  │  forgot-password.php     │
        │ acegiove_portfolio       │  │  check-token.php         │
        │                          │  └──────────────────────────┘
        │ Tables:                  │
        │ ├─ hero                  │
        │ ├─ timeline              │
        │ ├─ services              │
        │ ├─ skills                │
        │ ├─ contact_info          │
        │ ├─ footer_links          │
        │ ├─ projects              │
        │ ├─ project_media         │
        │ ├─ testimonials          │
        │ └─ admins ✅ (Fixed)     │
        └──────────────────────────┘
```

---

## 🔄 Request Flow

### **Frontend Request Flow**

```
User Browser
    ↓
index.html (Static)
    ↓
assets/js/main.js
    ↓
fetch(/api/site.php) ← AJAX
    ↓
┌──────────────────┐
│ api/site.php     │
├──────────────────┤
│ - Config load    │
│ - DB connect ✅  │
│ - Query data:    │
│   • hero         │
│   • timeline     │
│   • projects     │
│   • testimonials │
└──────────────────┘
    ↓
JSON Response
    ↓
JavaScript render
    ↓
User sees website 🎉
```

### **Admin Request Flow**

```
Admin Browser
    ↓
admin/login.php (Form)
    ↓
POST /admin/login.php
    ↓
┌──────────────────────┐
│ bootstrap.php        │
├──────────────────────┤
│ - Config load        │
│ - DB connect ✅      │
│ - Verify CSRF        │
└──────────────────────┘
    ↓
┌──────────────────────┐
│ login.php validate   │
├──────────────────────┤
│ Query: SELECT FROM   │
│ admins WHERE ...✅   │
│ password_verify()    │
└──────────────────────┘
    ↓
Session create
    ↓
admin/dashboard.php
    ↓
Admin sees dashboard 🎉
```

---

## 🔧 Component Breakdown

### **1. Frontend (Public)**
```
index.html (Entry point)
    ├─ Header (Navigation)
    ├─ Hero Section (Image + Text)
    ├─ About Section (Timeline)
    ├─ Projects Section (Grid + Modal)
    ├─ Services Section (Cards)
    ├─ Skills Section (Logos)
    ├─ Testimonials Section (Carousel)
    ├─ Contact Section (Form)
    └─ Footer

Data Source: /api/site.php → JSON
```

### **2. API Layer**
```
/api/
├─ site.php
│  └─ Returns: hero, timeline, services, skills, 
│              contact_info, footer, projects, testimonials
│
└─ project.php
   ├─ Parameter: ?slug=project-slug
   └─ Returns: project details, media, testimonials
```

### **3. Admin Panel**
```
/admin/
├─ bootstrap.php (Config + DB + Auth)
├─ login.php (Authentication)
├─ dashboard.php (Main page)
├─ projects.php (CRUD projects)
├─ project-media.php (Upload images)
├─ services.php (CRUD services)
├─ skills.php (CRUD skills)
├─ timeline.php (CRUD experience)
├─ testimonials.php (CRUD testimonials)
├─ contact.php (Manage contact messages)
├─ hero-edit.php (Edit hero section)
├─ password-manager.php (Password management)
├─ forgot-password.php (Password reset)
├─ check-token.php (Verify reset token)
├─ change-admin-password.php (Change password)
└─ security-dashboard.php (Security info)
```

### **4. Database (MySQL)**
```
acegiove_portfolio
├─ hero (1 row)
│  └─ greeting, headline, subhead, typewriter_text, links
│
├─ timeline (Multiple rows)
│  └─ time_range, company, description_vi/en, sort_order
│
├─ services (Multiple rows)
│  └─ title_vi/en, description_vi/en, sort_order
│
├─ skills (Multiple rows)
│  └─ name, logo_url, sort_order
│
├─ contact_info (1 row)
│  └─ phone, email, address_vi/en, social links
│
├─ projects (Multiple rows)
│  └─ slug, title_vi/en, description, meta, KPIs, sort_order
│
├─ project_media (Multiple rows)
│  └─ project_id, section, url, title, sort_order
│
├─ testimonials (Multiple rows)
│  └─ project_id, author_name, author_title, content_vi/en
│
└─ admins ✅ (Fixed)
   └─ username, password_hash, created_at
```

---

## 🔐 Security Architecture

### **Authentication Flow**
```
User Input (username/password)
    ↓
POST /admin/login.php
    ├─ Verify CSRF token ✅
    ├─ Query: SELECT * FROM admins WHERE username = ?
    ├─ password_verify($input, hash) ✅
    └─ Session start ✅
        ↓
    $_SESSION['admin_user'] = username
        ↓
    Redirect to dashboard
```

### **Password Reset Flow**
```
/admin/forgot-password.php
    ├─ Generate reset_token (random 32 bytes) ✅
    ├─ Set reset_expires (+1 hour) ✅
    ├─ UPDATE admins SET reset_token, reset_expires
    ├─ Send email with reset link
    │  └─ If SMTP fails → Save to reset_password_link.txt
    └─ User clicks link
        ├─ Verify token is valid & not expired
        ├─ User enters new password
        ├─ Password hash with bcrypt ✅
        └─ UPDATE admins SET password_hash
```

### **CSRF Protection**
```
Each form:
├─ Generate csrf_token (random 32 bytes) ✅
├─ Store in $_SESSION['csrf_token']
├─ Include <input hidden> in form ✅
└─ On submit: verify_csrf_or_die() ✅
```

---

## 📊 Data Flow

### **Hero Section**
```
Database (hero table)
    ↓
GET /api/site.php
    ↓
SELECT * FROM hero LIMIT 1
    ↓
JSON response
    ↓
index.html → JavaScript render
    ↓
User sees hero section with greeting, headline, typewriter effect
```

### **Projects Section**
```
Database (projects + project_media tables)
    ↓
GET /api/site.php
    ↓
SELECT p.id, p.slug, p.title FROM projects
SELECT url FROM project_media WHERE section='cover'
    ↓
JSON response with projects array
    ↓
JavaScript:
  • Loop projects
  • Load project.html via fetch(/api/project.php?slug=xxx)
  • Display in modal
    ↓
User can click, see details, view gallery
```

---

## 🐛 Bug Fixes Applied

### **Bug #1: Database Connection**
```
BEFORE:
config.php → 'user' => 'root', 'pass' => ''
    ↓
MySQL error: Connection failed
    ↓
Website broken 💥

AFTER:
config.php → 'user' => 'acegiove_portfolio', 'pass' => 'thuyet164'
    ↓
MySQL connected ✅
    ↓
Website works 🎉
```

### **Bug #2: Admin Queries**
```
BEFORE:
admin/login.php → SELECT * FROM admin_users WHERE ...
    ↓
Table not found error 💥
    ↓
Admin panel broken

AFTER:
admin/login.php → SELECT * FROM admins WHERE ...
    ↓
Query succeeds ✅
    ↓
Admin panel works 🎉
```

---

## 🚀 Deployment Architecture

### **File Structure on Server**
```
/home/acegiove/public_html/
├─ index.html (Frontend entry)
├─ config.php ✅ (DB credentials)
├─ CHECK_STATUS.php (Health check)
│
├─ admin/
│  ├─ bootstrap.php (Auth)
│  ├─ login.php ✅
│  ├─ dashboard.php
│  └─ ... (other admin files)
│
├─ api/
│  ├─ site.php (Main API)
│  └─ project.php (Project API)
│
├─ assets/
│  ├─ css/
│  │  └─ style.css
│  ├─ js/
│  │  ├─ main.js
│  │  ├─ load-projects.js
│  │  └─ dark-mode.js
│  └─ img/
│      └─ ... (images)
│
├─ uploads/ (Writable)
│  └─ ... (uploaded media)
│
├─ database/
│  └─ migrate.sql (Init script)
│
└─ sql/
   └─ schema.sql ✅
```

---

## 🔗 Environment Config

### **Local Development**
```
config.php:
  host: localhost
  user: root
  pass: (empty)
  
→ Works with XAMPP/MAMP
```

### **cPanel Production** ✅ (Current)
```
config.php:
  host: localhost
  user: acegiove_portfolio
  pass: thuyet164
  
→ Production ready
```

---

## 📈 Performance Considerations

### **Optimization Done**
- ✅ Database indexes on common queries (slug, username)
- ✅ JSON response caching (can be added)
- ✅ CSS/JS minification (assets folder)
- ✅ Image optimization (via postimg.cc CDN)

### **Optimization Pending** (Optional)
- ⏳ Add Redis caching for API responses
- ⏳ Implement database query result caching
- ⏳ CDN for static assets
- ⏳ Lazy loading for project images
- ⏳ Compression middleware

---

## 📊 Status Summary

| Component | Status | Notes |
|-----------|--------|-------|
| Frontend | ✅ OK | HTML + CSS + JS |
| APIs | ✅ OK | /api/site.php & /api/project.php |
| Admin Panel | ✅ OK | Login, CRUD, Password reset |
| Database | ✅ OK | MySQL with 9 tables |
| Security | ✅ OK | CSRF, bcrypt, sessions |
| Deployment | ✅ OK | cPanel ready |

---

**Architecture Version:** 1.0  
**Last Updated:** 18/11/2025  
**Status:** Production Ready 🚀
