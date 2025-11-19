<?php
// Mock API for testing - returns sample data instead of database connection
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Mock data for testing
$mockData = [
    'hero' => [
        'title_vi' => 'Chào mừng đến với Myntex',
        'title_en' => 'Welcome to Myntex',
        'subtitle_vi' => 'Chúng tôi tạo ra những trải nghiệm tuyệt vời',
        'subtitle_en' => 'We create amazing experiences',
        'description_vi' => 'Mô tả chi tiết về công ty và dịch vụ của chúng tôi',
        'description_en' => 'Detailed description about our company and services'
    ],
    'timeline' => [
        ['year' => '2020', 'title_vi' => 'Thành lập', 'title_en' => 'Founded', 'description_vi' => 'Công ty được thành lập', 'description_en' => 'Company was founded'],
        ['year' => '2021', 'title_vi' => 'Phát triển', 'title_en' => 'Growth', 'description_vi' => 'Mở rộng hoạt động', 'description_en' => 'Expanded operations'],
        ['year' => '2022', 'title_vi' => 'Thành công', 'title_en' => 'Success', 'description_vi' => 'Đạt nhiều thành tựu', 'description_en' => 'Achieved many accomplishments']
    ],
    'services' => [
        ['title_vi' => 'Thiết kế', 'title_en' => 'Design', 'description_vi' => 'Thiết kế chuyên nghiệp', 'description_en' => 'Professional design'],
        ['title_vi' => 'Phát triển', 'title_en' => 'Development', 'description_vi' => 'Phát triển ứng dụng', 'description_en' => 'Application development'],
        ['title_vi' => 'Marketing', 'title_en' => 'Marketing', 'description_vi' => 'Chiến lược marketing', 'description_en' => 'Marketing strategy']
    ],
    'skills' => [
        ['name' => 'Web Development', 'level' => 90],
        ['name' => 'UI/UX Design', 'level' => 85],
        ['name' => 'Mobile Development', 'level' => 80]
    ],
    'contact' => [
        'address_vi' => '123 Đường ABC, TP.HCM',
        'address_en' => '123 ABC Street, HCMC',
        'phone' => '+84 123 456 789',
        'email' => 'info@myntex.io.vn'
    ],
    'footer' => [
        ['section' => 'social', 'title' => 'Facebook', 'url' => 'https://facebook.com'],
        ['section' => 'social', 'title' => 'LinkedIn', 'url' => 'https://linkedin.com'],
        ['section' => 'links', 'title' => 'Về chúng tôi', 'url' => '#about']
    ],
    'projects' => [
        [
            'id' => 1,
            'slug' => 'blanca',
            'title_vi' => 'Dự án Blanca',
            'title_en' => 'Blanca Project',
            'cover_url' => 'assets/img/projects/blanca/blanca-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 2,
            'slug' => 'caraworld',
            'title_vi' => 'Dự án CaraWorld',
            'title_en' => 'CaraWorld Project',
            'cover_url' => 'assets/img/projects/caraworld/caraworld-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 3,
            'slug' => 'celadon',
            'title_vi' => 'Dự án Celadon',
            'title_en' => 'Celadon Project',
            'cover_url' => 'assets/img/projects/celadon/celadon-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 4,
            'slug' => 'eco',
            'title_vi' => 'Dự án Eco',
            'title_en' => 'Eco Project',
            'cover_url' => 'assets/img/projects/eco/eco-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 5,
            'slug' => 'latien',
            'title_vi' => 'Dự án La Tien',
            'title_en' => 'La Tien Project',
            'cover_url' => 'assets/img/projects/latien/latien-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 6,
            'slug' => 'eaton',
            'title_vi' => 'Dự án Eaton',
            'title_en' => 'Eaton Project',
            'cover_url' => 'assets/img/projects/eaton/eaton-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 7,
            'slug' => 'tgc',
            'title_vi' => 'Dự án TGC',
            'title_en' => 'TGC Project',
            'cover_url' => 'assets/img/projects/tgc/tgc-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 8,
            'slug' => 'lapura',
            'title_vi' => 'Dự án Lapura',
            'title_en' => 'Lapura Project',
            'cover_url' => 'assets/img/projects/lapura/lapura-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 9,
            'slug' => 'mt',
            'title_vi' => 'Dự án MT',
            'title_en' => 'MT Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 10,
            'slug' => 'ttnb',
            'title_vi' => 'Dự án TTNB',
            'title_en' => 'TTNB Project',
            'cover_url' => 'assets/img/projects/ttnb/ttnb-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 11,
            'slug' => 'mteastmark',
            'title_vi' => 'Dự án MT Eastmark',
            'title_en' => 'MT Eastmark Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 12,
            'slug' => 'm-klab',
            'title_vi' => 'Dự án M-KLab',
            'title_en' => 'M-KLab Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 13,
            'slug' => 'vin',
            'title_vi' => 'Dự án VIN',
            'title_en' => 'VIN Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 14,
            'slug' => 'viva',
            'title_vi' => 'Dự án VIVA',
            'title_en' => 'VIVA Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 15,
            'slug' => 'vin2',
            'title_vi' => 'Dự án VIN2',
            'title_en' => 'VIN2 Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 16,
            'slug' => 'viva2',
            'title_vi' => 'Dự án VIVA2',
            'title_en' => 'VIVA2 Project',
            'cover_url' => 'assets/img/projects/mt/mt-cover.jpg',
            'category' => 'brand'
        ],
        [
            'id' => 17,
            'slug' => 'blanca-city2',
            'title_vi' => 'Dự án Blanca City 2',
            'title_en' => 'Blanca City 2 Project',
            'cover_url' => 'assets/img/projects/blanca/blanca-cover.jpg',
            'category' => 'brand'
        ]
    ],
    'testimonials' => [
        [
            'quote_vi' => 'Dịch vụ tuyệt vời!',
            'quote_en' => 'Excellent service!',
            'author' => 'Nguyễn Văn A',
            'role_title' => 'CEO'
        ],
        [
            'quote_vi' => 'Rất hài lòng với kết quả',
            'quote_en' => 'Very satisfied with the results',
            'author' => 'Trần Thị B',
            'role_title' => 'Marketing Director'
        ]
    ]
];

echo json_encode($mockData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);