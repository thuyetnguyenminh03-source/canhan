<?php
// Import sample data for admin panel
require __DIR__ . '/admin/bootstrap.php';

try {
    // Insert sample timeline data
    $timelineData = [
        [
            'time_range' => '11/2024 – 2025',
            'company' => 'Công ty CP Tư vấn & Đầu tư BĐS An Khang',
            'description_vi' => 'Design xây dựng bộ nhận diện, KV chiến dịch cho danh mục dự án cao cấp.',
            'description_en' => 'Built identity and key visuals for premium project clusters.',
            'sort_order' => 1
        ],
        [
            'time_range' => '05/2024 – 10/2024',
            'company' => 'Công ty TNHH Địa ốc Nhà Đại Phát',
            'description_vi' => 'Thiết kế bộ sản phẩm truyền thông cho dự án BĐS hạng sang, chuẩn hoá guideline visual.',
            'description_en' => 'Designed luxury project communication packs and standardized the visual guideline.',
            'sort_order' => 2
        ],
        [
            'time_range' => '02/2024 – 05/2024',
            'company' => 'Freelance Designer',
            'description_vi' => 'Thiết kế social media post theo KPI chiến dịch.',
            'description_en' => 'Created social media posts aligned with campaign KPIs.',
            'sort_order' => 3
        ],
        [
            'time_range' => '10/2022 – 01/2023',
            'company' => 'Intern Designer — Toppion Coaching & Consulting Group',
            'description_vi' => 'Thiết kế cover cho sách/ấn phẩm đào tạo, social post và tài liệu nội bộ.',
            'description_en' => 'Designed covers for training materials, social posts, and internal documents.',
            'sort_order' => 4
        ],
        [
            'time_range' => '02/2022 – 09/2022',
            'company' => 'Part-time Designer — Công ty TNHH Hoàng Đức Hải',
            'description_vi' => 'Thực hiện social media post theo chiến dịch tuần.',
            'description_en' => 'Executed weekly social media campaigns.',
            'sort_order' => 5
        ]
    ];

    foreach ($timelineData as $item) {
        $stmt = $pdo->prepare('INSERT INTO timeline (time_range, company, description_vi, description_en, sort_order) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE description_vi=VALUES(description_vi), description_en=VALUES(description_en)');
        $stmt->execute([$item['time_range'], $item['company'], $item['description_vi'], $item['description_en'], $item['sort_order']]);
    }

    // Insert sample services data
    $servicesData = [
        [
            'title_vi' => 'Brand Identity',
            'title_en' => 'Brand Identity',
            'description_vi' => 'Logo, hệ màu, typography, guideline, stationery, ứng dụng nhận diện.',
            'description_en' => 'Logo, palette, typography, guidelines, stationery, and identity applications.',
            'sort_order' => 1
        ],
        [
            'title_vi' => 'Social & Campaign',
            'title_en' => 'Social & Campaign',
            'description_vi' => 'KV, bộ banner, video short, landing, kế hoạch nội dung & A/B test.',
            'description_en' => 'Key visuals, banner kits, short videos, landing pages, content & A/B testing.',
            'sort_order' => 2
        ],
        [
            'title_vi' => 'Print & POSM',
            'title_en' => 'Print & POSM',
            'description_vi' => 'Brochure, flyer, standee, OOH, 2D booth, in ấn và bàn giao file chuẩn.',
            'description_en' => 'Brochures, flyers, standees, OOH, 2D booths, production-ready deliverables.',
            'sort_order' => 3
        ]
    ];

    foreach ($servicesData as $item) {
        $stmt = $pdo->prepare('INSERT INTO services (title_vi, title_en, description_vi, description_en, sort_order) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE description_vi=VALUES(description_vi), description_en=VALUES(description_en)');
        $stmt->execute([$item['title_vi'], $item['title_en'], $item['description_vi'], $item['description_en'], $item['sort_order']]);
    }

    // Insert sample skills data
    $skillsData = [
        ['name' => 'Adobe Photoshop', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Adobe_Photoshop_CC_icon.svg/500px-Adobe_Photoshop_CC_icon.svg.png', 'sort_order' => 1],
        ['name' => 'Adobe Illustrator', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/Adobe_Illustrator_CC_icon.svg/2048px-Adobe_Illustrator_CC_icon.svg.png', 'sort_order' => 2],
        ['name' => 'Adobe Premiere Pro', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/Adobe_Premiere_Pro_CC_icon.svg/512px-Adobe_Premiere_Pro_CC_icon.svg.png', 'sort_order' => 3],
        ['name' => 'Adobe After Effects', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Adobe_After_Effects_CC_icon.svg/512px-Adobe_After_Effects_CC_icon.svg.png', 'sort_order' => 4]
    ];

    foreach ($skillsData as $item) {
        $stmt = $pdo->prepare('INSERT INTO skills (name, logo_url, sort_order) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE logo_url=VALUES(logo_url)');
        $stmt->execute([$item['name'], $item['logo_url'], $item['sort_order']]);
    }

    // Insert sample testimonials data
    $testimonialsData = [
        [
            'author_name' => 'Trưởng phòng Marketing',
            'author_title' => 'BĐS cao cấp',
            'avatar_url' => '',
            'content_vi' => '"Thiết kế đẹp và đúng deadline. Chiến dịch social tăng tương tác 3×."',
            'content_en' => '"Designs were polished and always on deadline. Social campaigns tripled engagement."',
            'sort_order' => 1
        ],
        [
            'author_name' => 'CEO Thương hiệu nội thất',
            'author_title' => 'Thương hiệu nội thất',
            'avatar_url' => '',
            'content_vi' => '"Brandbook chi tiết, triển khai đồng bộ rất mượt."',
            'content_en' => '"Detailed brandbook, super smooth deployment across channels."',
            'sort_order' => 2
        ],
        [
            'author_name' => 'Account Director',
            'author_title' => 'Agency',
            'avatar_url' => '',
            'content_vi' => '"Tư duy hình ảnh hiện đại, phối hợp tốt với team media."',
            'content_en' => '"Modern visual thinking and great collaboration with our media team."',
            'sort_order' => 3
        ]
    ];

    foreach ($testimonialsData as $item) {
        $stmt = $pdo->prepare('INSERT INTO testimonials (author_name, author_title, avatar_url, content_vi, content_en, sort_order) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE content_vi=VALUES(content_vi), content_en=VALUES(content_en)');
        $stmt->execute([$item['author_name'], $item['author_title'], $item['avatar_url'], $item['content_vi'], $item['content_en'], $item['sort_order']]);
    }

    // Insert sample contact info
    $contactData = [
        'phone' => '(+84) 0912.275.643',
        'email' => 'hi@myntex.io.vn',
        'address_vi' => 'Thủ Đức - Hồ Chí Minh, Việt Nam',
        'address_en' => 'Thu Duc – Ho Chi Minh City, Vietnam',
        'facebook_url' => '',
        'instagram_url' => 'https://www.instagram.com/myntex.dsn/',
        'tiktok_url' => 'https://www.tiktok.com/@myntex_dsn',
        'zalo_url' => 'https://zalo.me/0912275643',
        'whatsapp_url' => '',
        'map_embed' => ''
    ];

    $stmt = $pdo->prepare('INSERT INTO contact_info (phone, email, address_vi, address_en, facebook_url, instagram_url, tiktok_url, zalo_url, whatsapp_url, map_embed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone=VALUES(phone), email=VALUES(email)');
    $stmt->execute(array_values($contactData));

    // Insert sample footer info
    $footerData = [
        'copyright_vi' => '© 2025 Minh Thuyết. All rights reserved.',
        'copyright_en' => '© 2025 Minh Thuyết. All rights reserved.',
        'extra_html' => ''
    ];

    $stmt = $pdo->prepare('INSERT INTO footer_info (copyright_vi, copyright_en, extra_html) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE copyright_vi=VALUES(copyright_vi)');
    $stmt->execute(array_values($footerData));

    // Insert sample projects
    $projectsData = [
        [
            'slug' => 'blanca-city',
            'title_vi' => 'Blanca City',
            'title_en' => 'Blanca City',
            'description_vi' => 'Bộ nhận diện Blanca City với lockup, palette và grid sang trọng.',
            'description_en' => 'Identity kit for Blanca City with a refined lockup, palette, and grid.',
            'sort_order' => 1
        ],
        [
            'slug' => 'la-tien-villa',
            'title_vi' => 'La Tiên Villa',
            'title_en' => 'La Tiên Villa',
            'description_vi' => 'Brochure La Tiên Villa phong cách Wabi-Sabi với layout tinh gọn, tập trung cảm xúc không gian.',
            'description_en' => 'Wabi-Sabi inspired brochure for La Tiên Villa with minimal layouts and emotive storytelling.',
            'sort_order' => 2
        ],
        [
            'slug' => 'ecopark',
            'title_vi' => 'Ecopark',
            'title_en' => 'Ecopark',
            'description_vi' => 'Hệ nhận diện mang cảm hứng resort cao cấp cho Ecopark gồm guideline, POSM và social kit.',
            'description_en' => 'Resort-inspired identity refresh for Ecopark covering guidelines, POSM, and social kits.',
            'sort_order' => 3
        ]
    ];

    foreach ($projectsData as $item) {
        $stmt = $pdo->prepare('INSERT INTO projects (slug, title_vi, title_en, description_vi, description_en, sort_order) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title_vi=VALUES(title_vi), description_vi=VALUES(description_vi)');
        $stmt->execute([$item['slug'], $item['title_vi'], $item['title_en'], $item['description_vi'], $item['description_en'], $item['sort_order']]);
    }

    // Insert sample project media
    $mediaData = [
        // Blanca City
        ['project_slug' => 'blanca-city', 'section' => 'cover', 'url' => 'assets/img/projects/blanca/blanca-cover.jpg', 'title' => 'Blanca City Cover', 'sort_order' => 1],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-1.jpg', 'title' => 'Blanca City Gallery 1', 'sort_order' => 1],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-2.jpg', 'title' => 'Blanca City Gallery 2', 'sort_order' => 2],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-3.jpg', 'title' => 'Blanca City Gallery 3', 'sort_order' => 3],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-4.jpg', 'title' => 'Blanca City Gallery 4', 'sort_order' => 4],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-5.jpg', 'title' => 'Blanca City Gallery 5', 'sort_order' => 5],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-6.jpg', 'title' => 'Blanca City Gallery 6', 'sort_order' => 6],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-7.jpg', 'title' => 'Blanca City Gallery 7', 'sort_order' => 7],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-8.jpg', 'title' => 'Blanca City Gallery 8', 'sort_order' => 8],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-9.jpg', 'title' => 'Blanca City Gallery 9', 'sort_order' => 9],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-10.jpg', 'title' => 'Blanca City Gallery 10', 'sort_order' => 10],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-11.jpg', 'title' => 'Blanca City Gallery 11', 'sort_order' => 11],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-12.jpg', 'title' => 'Blanca City Gallery 12', 'sort_order' => 12],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-13.jpg', 'title' => 'Blanca City Gallery 13', 'sort_order' => 13],
        ['project_slug' => 'blanca-city', 'section' => 'gallery', 'url' => 'assets/img/projects/blanca/blanca-14.jpg', 'title' => 'Blanca City Gallery 14', 'sort_order' => 14],
        ['project_slug' => 'blanca-city', 'section' => 'vecan', 'url' => 'assets/img/projects/blanca/blanca-vecan-1.jpg', 'title' => 'Blanca City Ve Can 1', 'sort_order' => 1],
        ['project_slug' => 'blanca-city', 'section' => 'vecan', 'url' => 'assets/img/projects/blanca/blanca-vecan-2.jpg', 'title' => 'Blanca City Ve Can 2', 'sort_order' => 2],
        ['project_slug' => 'blanca-city', 'section' => 'vecan', 'url' => 'assets/img/projects/blanca/blanca-vecan-3.jpg', 'title' => 'Blanca City Ve Can 3', 'sort_order' => 3],
        ['project_slug' => 'blanca-city', 'section' => 'vecan', 'url' => 'assets/img/projects/blanca/blanca-vecan-4.jpg', 'title' => 'Blanca City Ve Can 4', 'sort_order' => 4],
        ['project_slug' => 'blanca-city', 'section' => 'vecan', 'url' => 'assets/img/projects/blanca/blanca-vecan-5.jpg', 'title' => 'Blanca City Ve Can 5', 'sort_order' => 5],

        // La Tiên Villa
        ['project_slug' => 'la-tien-villa', 'section' => 'cover', 'url' => 'assets/img/projects/latien/latien-cover.jpg', 'title' => 'La Tiên Villa Cover', 'sort_order' => 1],
        ['project_slug' => 'la-tien-villa', 'section' => 'gallery', 'url' => 'assets/img/projects/latien/latien-1.jpg', 'title' => 'La Tiên Villa Gallery 1', 'sort_order' => 1],
        ['project_slug' => 'la-tien-villa', 'section' => 'gallery', 'url' => 'assets/img/projects/latien/latien-2.jpg', 'title' => 'La Tiên Villa Gallery 2', 'sort_order' => 2],
        ['project_slug' => 'la-tien-villa', 'section' => 'gallery', 'url' => 'assets/img/projects/latien/latien-3.jpg', 'title' => 'La Tiên Villa Gallery 3', 'sort_order' => 3],
        ['project_slug' => 'la-tien-villa', 'section' => 'gallery', 'url' => 'assets/img/projects/latien/latien-4.jpg', 'title' => 'La Tiên Villa Gallery 4', 'sort_order' => 4],
        ['project_slug' => 'la-tien-villa', 'section' => 'chinhsach', 'url' => 'assets/img/projects/latien/latien-chinhsach-1.jpg', 'title' => 'La Tiên Villa Chính Sách 1', 'sort_order' => 1],
        ['project_slug' => 'la-tien-villa', 'section' => 'chinhsach', 'url' => 'assets/img/projects/latien/latien-chinhsach-2.jpg', 'title' => 'La Tiên Villa Chính Sách 2', 'sort_order' => 2],
        ['project_slug' => 'la-tien-villa', 'section' => 'chinhsach', 'url' => 'assets/img/projects/latien/latien-chinhsach-3.jpg', 'title' => 'La Tiên Villa Chính Sách 3', 'sort_order' => 3],
        ['project_slug' => 'la-tien-villa', 'section' => 'vecan', 'url' => 'assets/img/projects/latien/latien-vecan-1.jpg', 'title' => 'La Tiên Villa Ve Can 1', 'sort_order' => 1],
        ['project_slug' => 'la-tien-villa', 'section' => 'vecan', 'url' => 'assets/img/projects/latien/latien-vecan-2.jpg', 'title' => 'La Tiên Villa Ve Can 2', 'sort_order' => 2],

        // Ecopark
        ['project_slug' => 'ecopark', 'section' => 'cover', 'url' => 'assets/img/projects/eco/eco-cover.jpg', 'title' => 'Ecopark Cover', 'sort_order' => 1],
        ['project_slug' => 'ecopark', 'section' => 'gallery', 'url' => 'assets/img/projects/eco/eco-1.jpg', 'title' => 'Ecopark Gallery 1', 'sort_order' => 1],
        ['project_slug' => 'ecopark', 'section' => 'gallery', 'url' => 'assets/img/projects/eco/eco-2.jpg', 'title' => 'Ecopark Gallery 2', 'sort_order' => 2],
        ['project_slug' => 'ecopark', 'section' => 'gallery', 'url' => 'assets/img/projects/eco/eco-3.jpg', 'title' => 'Ecopark Gallery 3', 'sort_order' => 3],
        ['project_slug' => 'ecopark', 'section' => 'chinhsach', 'url' => 'assets/img/projects/eco/eco-chinhsach-1.jpg', 'title' => 'Ecopark Chính Sách 1', 'sort_order' => 1],
        ['project_slug' => 'ecopark', 'section' => 'chinhsach', 'url' => 'assets/img/projects/eco/eco-chinhsach-2.jpg', 'title' => 'Ecopark Chính Sách 2', 'sort_order' => 2]
    ];

    foreach ($mediaData as $item) {
        // Get project ID by slug
        $stmt = $pdo->prepare('SELECT id FROM projects WHERE slug = ?');
        $stmt->execute([$item['project_slug']]);
        $project = $stmt->fetch();

        if ($project) {
            $stmt = $pdo->prepare('INSERT INTO project_media (project_id, section, url, title, sort_order) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE url=VALUES(url)');
            $stmt->execute([$project['id'], $item['section'], $item['url'], $item['title'], $item['sort_order']]);
        }
    }

    echo "✅ Sample data imported successfully!\n";
    echo "📊 Summary:\n";
    echo "- Timeline entries: " . count($timelineData) . "\n";
    echo "- Services: " . count($servicesData) . "\n";
    echo "- Skills: " . count($skillsData) . "\n";
    echo "- Testimonials: " . count($testimonialsData) . "\n";
    echo "- Projects: " . count($projectsData) . "\n";
    echo "- Contact info: 1\n";
    echo "- Footer info: 1\n";

} catch (Throwable $e) {
    echo "❌ Error importing data: " . $e->getMessage() . "\n";
}
?>
