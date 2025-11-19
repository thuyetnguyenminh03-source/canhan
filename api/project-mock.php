<?php
// Mock API for project detail - returns sample data instead of database connection
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing slug']);
    exit;
}

// Mock project data for different slugs
$mockProjects = [
    'blanca' => [
        'id' => 1,
        'title_vi' => 'Dự án Blanca',
        'title_en' => 'Blanca Project',
        'slug' => 'blanca',
        'description_vi' => 'Blanca là dự án bất động sản cao cấp với thiết kế hiện đại và tiện ích đẳng cấp.',
        'description_en' => 'Blanca is a premium real estate project with modern design and premium amenities.',
        'summary_vi' => 'Thiết kế nhận diện thương hiệu cho dự án bất động sản cao cấp Blanca',
        'summary_en' => 'Brand identity design for premium real estate project Blanca',
        'objective_vi' => 'Tạo dựng hình ảnh thương hiệu cao cấp, chuyên nghiệp cho dự án bất động sản',
        'objective_en' => 'Build premium, professional brand image for real estate project',
        'challenge_vi' => 'Cạnh tranh trong thị trường bất động sản cao cấp đòi hỏi sự khác biệt rõ rệt',
        'challenge_en' => 'Competing in the premium real estate market requires clear differentiation',
        'strategy_vi' => 'Tập trung vào sự sang trọng, tinh tế và phong cách sống thượng lưu',
        'strategy_en' => 'Focus on luxury, sophistication and upscale lifestyle',
        'workflow_vi' => 'Research → Concept → Design → Implementation',
        'workflow_en' => 'Research → Concept → Design → Implementation',
        'meta_role' => 'Brand Identity Designer',
        'meta_time' => '3 months',
        'meta_tools' => 'Adobe Creative Suite, Figma',
        'kpi1' => 'Brand recognition increased by 150%',
        'kpi2' => 'Sales conversion improved by 40%',
        'kpi3' => 'Customer engagement up 200%',
        'kpi4' => 'Premium perception score 9.2/10'
    ],
    'caraworld' => [
        'id' => 2,
        'title_vi' => 'Dự án CaraWorld',
        'title_en' => 'CaraWorld Project',
        'slug' => 'caraworld',
        'description_vi' => 'CaraWorld là khu đô thị thông minh với công nghệ tiên tiến và thiết kế bền vững.',
        'description_en' => 'CaraWorld is a smart urban area with advanced technology and sustainable design.',
        'summary_vi' => 'Thiết kế thương hiệu cho khu đô thị thông minh CaraWorld',
        'summary_en' => 'Brand design for smart urban area CaraWorld',
        'objective_vi' => 'Thể hiện sự hiện đại, công nghệ và bền vững trong thiết kế',
        'objective_en' => 'Express modernity, technology and sustainability in design',
        'challenge_vi' => 'Cân bằng giữa công nghệ và tính người dùng trong thiết kế',
        'challenge_en' => 'Balancing technology and user-friendliness in design',
        'strategy_vi' => 'Tích hợp yếu tố công nghệ với thiết kế thân thiện',
        'strategy_en' => 'Integrate technology elements with friendly design',
        'workflow_vi' => 'User Research → UX Design → Brand Design → Implementation',
        'workflow_en' => 'User Research → UX Design → Brand Design → Implementation',
        'meta_role' => 'UX/UI Designer',
        'meta_time' => '4 months',
        'meta_tools' => 'Sketch, Principle, After Effects',
        'kpi1' => 'User satisfaction increased by 85%',
        'kpi2' => 'Technology adoption rate 95%',
        'kpi3' => 'Energy efficiency improved by 30%',
        'kpi4' => 'Community engagement up 180%'
    ],
    'celadon' => [
        'id' => 3,
        'title_vi' => 'Dự án Celadon',
        'title_en' => 'Celadon Project',
        'slug' => 'celadon',
        'description_vi' => 'Celadon là dự án căn hộ cao cấp với thiết kế xanh và không gian sống hòa hợp thiên nhiên.',
        'description_en' => 'Celadon is a premium apartment project with green design and nature-harmonious living space.',
        'summary_vi' => 'Thiết kế thương hiệu cho dự án căn hộ xanh Celadon',
        'summary_en' => 'Brand design for green apartment project Celadon',
        'objective_vi' => 'Tạo không gian sống xanh, sạch và hiện đại cho cư dân',
        'objective_en' => 'Create green, clean and modern living space for residents',
        'challenge_vi' => 'Thiết kế thân thiện môi trường mà vẫn đảm bảo tính thẩm mỹ',
        'challenge_en' => 'Design environmentally friendly while ensuring aesthetics',
        'strategy_vi' => 'Sử dụng màu xanh thiên nhiên và họa tiết hữu cơ',
        'strategy_en' => 'Use natural green colors and organic patterns',
        'workflow_vi' => 'Environmental Research → Green Design → User Testing → Launch',
        'workflow_en' => 'Environmental Research → Green Design → User Testing → Launch',
        'meta_role' => 'Environmental Designer',
        'meta_time' => '5 months',
        'meta_tools' => 'Revit, Ecotect, Grasshopper',
        'kpi1' => 'Green building certification achieved',
        'kpi2' => 'Energy consumption reduced by 35%',
        'kpi3' => 'Air quality index improved by 40%',
        'kpi4' => 'Resident wellness score 8.8/10'
    ],
    'eco' => [
        'id' => 4,
        'title_vi' => 'Dự án Eco',
        'title_en' => 'Eco Project',
        'slug' => 'eco',
        'description_vi' => 'Eco là dự án bất động sản sinh thái với thiết kế bền vững và công nghệ xanh tiên tiến.',
        'description_en' => 'Eco is an ecological real estate project with sustainable design and advanced green technology.',
        'summary_vi' => 'Thiết kế thương hiệu cho dự án bất động sản sinh thái Eco',
        'summary_en' => 'Brand design for ecological real estate project Eco',
        'objective_vi' => 'Xây dựng thương hiệu bất động sản xanh với công nghệ bền vững',
        'objective_en' => 'Build green real estate brand with sustainable technology',
        'challenge_vi' => 'Cân bằng giữa phát triển bất động sản và bảo vệ môi trường',
        'challenge_en' => 'Balance between real estate development and environmental protection',
        'strategy_vi' => 'Tập trung vào công nghệ xanh và thiết kế sinh thái',
        'strategy_en' => 'Focus on green technology and ecological design',
        'workflow_vi' => 'Eco Research → Green Concept → Sustainable Design → Implementation',
        'workflow_en' => 'Eco Research → Green Concept → Sustainable Design → Implementation',
        'meta_role' => 'Sustainable Design Consultant',
        'meta_time' => '6 months',
        'meta_tools' => 'AutoCAD, SketchUp, V-Ray',
        'kpi1' => 'Carbon footprint reduced by 45%',
        'kpi2' => 'Energy efficiency improved by 60%',
        'kpi3' => 'Green certification achieved',
        'kpi4' => 'Environmental impact score 9.1/10'
    ],
    'latien' => [
        'id' => 5,
        'title_vi' => 'Dự án La Tien',
        'title_en' => 'La Tien Project',
        'slug' => 'latien',
        'description_vi' => 'La Tien là khu đô thị hiện đại với thiết kế thông minh và tiện ích đầy đủ.',
        'description_en' => 'La Tien is a modern urban area with smart design and complete amenities.',
        'summary_vi' => 'Thiết kế thương hiệu cho khu đô thị hiện đại La Tien',
        'summary_en' => 'Brand design for modern urban area La Tien',
        'objective_vi' => 'Tạo không gian sống hiện đại, tiện nghi cho cư dân thành thị',
        'objective_en' => 'Create modern, comfortable living space for urban residents',
        'challenge_vi' => 'Thiết kế phù hợp với phong cách sống đô thị hiện đại',
        'challenge_en' => 'Design suitable for modern urban lifestyle',
        'strategy_vi' => 'Tập trung vào sự tiện nghi, hiện đại và cộng đồng',
        'strategy_en' => 'Focus on convenience, modernity and community',
        'workflow_vi' => 'Urban Research → Modern Concept → Smart Design → Implementation',
        'workflow_en' => 'Urban Research → Modern Concept → Smart Design → Implementation',
        'meta_role' => 'Urban Designer',
        'meta_time' => '4 months',
        'meta_tools' => 'AutoCAD, SketchUp, Lumion',
        'kpi1' => 'Urban satisfaction increased by 80%',
        'kpi2' => 'Community engagement up 150%',
        'kpi3' => 'Smart features adoption 90%',
        'kpi4' => 'Modern lifestyle score 8.5/10'
    ],
    'tgc' => [
        'id' => 6,
        'title_vi' => 'Dự án TGC',
        'title_en' => 'TGC Project',
        'slug' => 'tgc',
        'description_vi' => 'TGC là dự án thương mại đa năng với thiết kế độc đáo và tiện ích hàng đầu.',
        'description_en' => 'TGC is a multifunctional commercial project with unique design and top amenities.',
        'summary_vi' => 'Thiết kế thương hiệu cho dự án thương mại TGC',
        'summary_en' => 'Brand design for commercial project TGC',
        'objective_vi' => 'Tạo trung tâm thương mại hiện đại và thu hút khách hàng',
        'objective_en' => 'Create modern commercial center and attract customers',
        'challenge_vi' => 'Thiết kế nổi bật trong khu vực thương mại sầm uất',
        'challenge_en' => 'Stand out design in busy commercial area',
        'strategy_vi' => 'Tập trung vào sự độc đáo, tiện nghi và trải nghiệm khách hàng',
        'strategy_en' => 'Focus on uniqueness, convenience and customer experience',
        'workflow_vi' => 'Market Research → Commercial Concept → Unique Design → Launch',
        'workflow_en' => 'Market Research → Commercial Concept → Unique Design → Launch',
        'meta_role' => 'Commercial Designer',
        'meta_time' => '5 months',
        'meta_tools' => '3ds Max, Photoshop, Illustrator',
        'kpi1' => 'Customer footfall increased by 200%',
        'kpi2' => 'Brand recognition improved by 85%',
        'kpi3' => 'Commercial success rate 95%',
        'kpi4' => 'Customer satisfaction score 9.0/10'
    ],
    'eaton' => [
        'id' => 7,
        'title_vi' => 'Dự án Eaton',
        'title_en' => 'Eaton Project',
        'slug' => 'eaton',
        'description_vi' => 'Eaton là dự án công nghiệp hiện đại với thiết kế tiên tiến và công nghệ hàng đầu.',
        'description_en' => 'Eaton is a modern industrial project with advanced design and leading technology.',
        'summary_vi' => 'Thiết kế thương hiệu cho dự án công nghiệp Eaton',
        'summary_en' => 'Brand design for industrial project Eaton',
        'objective_vi' => 'Tạo hình ảnh công nghiệp hiện đại và chuyên nghiệp',
        'objective_en' => 'Create modern and professional industrial image',
        'challenge_vi' => 'Thiết kế phù hợp với ngành công nghiệp hiện đại',
        'challenge_en' => 'Design suitable for modern industrial sector',
        'strategy_vi' => 'Tập trung vào sự chuyên nghiệp, công nghệ và hiệu quả',
        'strategy_en' => 'Focus on professionalism, technology and efficiency',
        'workflow_vi' => 'Industrial Research → Tech Concept → Professional Design → Implementation',
        'workflow_en' => 'Industrial Research → Tech Concept → Professional Design → Implementation',
        'meta_role' => 'Industrial Designer',
        'meta_time' => '4 months',
        'meta_tools' => 'SolidWorks, AutoCAD, KeyShot',
        'kpi1' => 'Industrial efficiency increased by 70%',
        'kpi2' => 'Technology adoption rate 90%',
        'kpi3' => 'Professional image score 8.8/10',
        'kpi4' => 'Client satisfaction rate 95%'
    ],
    'lapura' => [
        'id' => 8,
        'title_vi' => 'Dự án Lapura',
        'title_en' => 'Lapura Project',
        'slug' => 'lapura',
        'description_vi' => 'Lapura là khu nghỉ dưỡng cao cấp với thiết kế sang trọng và không gian thư giãn tuyệt vời.',
        'description_en' => 'Lapura is a premium resort with luxurious design and excellent relaxation space.',
        'summary_vi' => 'Thiết kế thương hiệu cho khu nghỉ dưỡng cao cấp Lapura',
        'summary_en' => 'Brand design for premium resort Lapura',
        'objective_vi' => 'Tạo không gian nghỉ dưỡng sang trọng và đẳng cấp quốc tế',
        'objective_en' => 'Create luxurious and international-class resort space',
        'challenge_vi' => 'Thiết kế nổi bật trong lĩnh vực nghỉ dưỡng cao cấp',
        'challenge_en' => 'Stand out design in the premium resort sector',
        'strategy_vi' => 'Tập trung vào sự sang trọng, thư giãn và trải nghiệm đẳng cấp',
        'strategy_en' => 'Focus on luxury, relaxation and premium experience',
        'workflow_vi' => 'Resort Research → Luxury Concept → Premium Design → Implementation',
        'workflow_en' => 'Resort Research → Luxury Concept → Premium Design → Implementation',
        'meta_role' => 'Resort Designer',
        'meta_time' => '5 months',
        'meta_tools' => 'AutoCAD, SketchUp, V-Ray',
        'kpi1' => 'Guest satisfaction increased by 95%',
        'kpi2' => 'Luxury perception score 9.3/10',
        'kpi3' => 'Resort bookings up 180%',
        'kpi4' => 'International recognition achieved'
    ],
    'mt' => [
        'id' => 9,
        'title_vi' => 'Dự án MT',
        'title_en' => 'MT Project',
        'slug' => 'mt',
        'description_vi' => 'MT là dự án thương mại đa chức năng với thiết kế độc đáo và tiện ích hiện đại.',
        'description_en' => 'MT is a multifunctional commercial project with unique design and modern amenities.',
        'summary_vi' => 'Thiết kế thương hiệu cho dự án thương mại đa chức năng MT',
        'summary_en' => 'Brand design for multifunctional commercial project MT',
        'objective_vi' => 'Tạo trung tâm thương mại hiện đại và tiện ích đa dạng',
        'objective_en' => 'Create modern commercial center with diverse amenities',
        'challenge_vi' => 'Thiết kế đa chức năng mà vẫn đảm bảo tính thẩm mỹ',
        'challenge_en' => 'Design multifunctional while ensuring aesthetics',
        'strategy_vi' => 'Tích hợp nhiều chức năng trong thiết kế thống nhất',
        'strategy_en' => 'Integrate multiple functions in unified design',
        'workflow_vi' => 'Commercial Research → Multi-concept → Integrated Design → Implementation',
        'workflow_en' => 'Commercial Research → Multi-concept → Integrated Design → Implementation',
        'meta_role' => 'Commercial Architect',
        'meta_time' => '6 months',
        'meta_tools' => 'Revit, Rhino, Enscape',
        'kpi1' => 'Commercial efficiency increased by 120%',
        'kpi2' => 'Multi-function utilization rate 85%',
        'kpi3' => 'Customer traffic up 200%',
        'kpi4' => 'Revenue growth achieved 150%'
    ],
    'ttnb' => [
        'id' => 10,
        'title_vi' => 'Dự án TTNB',
        'title_en' => 'TTNB Project',
        'slug' => 'ttnb',
        'description_vi' => 'TTNB là khu đô thị thông minh với thiết kế hiện đại và công nghệ tiên tiến.',
        'description_en' => 'TTNB is a smart urban area with modern design and advanced technology.',
        'summary_vi' => 'Thiết kế thương hiệu cho khu đô thị thông minh TTNB',
        'summary_en' => 'Brand design for smart urban area TTNB',
        'objective_vi' => 'Tạo khu đô thị thông minh và bền vững cho tương lai',
        'objective_en' => 'Create smart and sustainable urban area for the future',
        'challenge_vi' => 'Tích hợp công nghệ thông minh trong thiết kế đô thị',
        'challenge_en' => 'Integrate smart technology in urban design',
        'strategy_vi' => 'Tập trung vào công nghệ, bền vững và chất lượng sống',
        'strategy_en' => 'Focus on technology, sustainability and quality of life',
        'workflow_vi' => 'Smart Research → Tech Concept → Sustainable Design → Implementation',
        'workflow_en' => 'Smart Research → Tech Concept → Sustainable Design → Implementation',
        'meta_role' => 'Smart City Designer',
        'meta_time' => '8 months',
        'meta_tools' => 'AutoCAD, Grasshopper, Dynamo',
        'kpi1' => 'Smart technology integration 95%',
        'kpi2' => 'Sustainability score 9.2/10',
        'kpi3' => 'Quality of life improved by 80%',
        'kpi4' => 'Energy efficiency increased by 65%'
    ]
];

// Mock media data
$mockMedia = [
    'blanca' => [
        'cover' => [['url' => 'assets/img/projects/blanca/blanca-cover.jpg', 'title' => 'Blanca Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/blanca/blanca-1.jpg', 'title' => 'Main Entrance'],
            ['url' => 'assets/img/projects/blanca/blanca-2.jpg', 'title' => 'Lobby Area'],
            ['url' => 'assets/img/projects/blanca/blanca-3.jpg', 'title' => 'Swimming Pool']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/blanca/blanca-vecan-1.jpg', 'title' => 'Payment Policy'],
            ['url' => 'assets/img/projects/blanca/blanca-vecan-2.jpg', 'title' => 'Warranty Policy']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/blanca/blanca-4.jpg', 'title' => 'Floor Plan A'],
            ['url' => 'assets/img/projects/blanca/blanca-5.jpg', 'title' => 'Floor Plan B']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/blanca/blanca-6.jpg', 'title' => 'Sales Team']
        ]
    ],
    'caraworld' => [
        'cover' => [['url' => 'assets/img/projects/caraworld/caraworld-cover.jpg', 'title' => 'CaraWorld Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/caraworld/caraworld-1.jpg', 'title' => 'Smart Entrance'],
            ['url' => 'assets/img/projects/caraworld/caraworld-2.jpg', 'title' => 'Tech Hub'],
            ['url' => 'assets/img/projects/caraworld/caraworld-3.jpg', 'title' => 'Green Spaces']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/caraworld/caraworld-4.jpg', 'title' => 'Smart City Policy'],
            ['url' => 'assets/img/projects/caraworld/caraworld-5.jpg', 'title' => 'Tech Support']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/caraworld/caraworld-1.jpg', 'title' => 'Smart Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/caraworld/caraworld-2.jpg', 'title' => 'Tech Team Hiring']
        ]
    ],
    'eco' => [
        'cover' => [['url' => 'assets/img/projects/eco/eco-cover.jpg', 'title' => 'Eco Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/eco/eco-1.jpg', 'title' => 'Eco Entrance'],
            ['url' => 'assets/img/projects/eco/eco-2.jpg', 'title' => 'Green Building'],
            ['url' => 'assets/img/projects/eco/eco-3.jpg', 'title' => 'Solar Panels']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/eco/eco-4.jpg', 'title' => 'Green Policy'],
            ['url' => 'assets/img/projects/eco/eco-5.jpg', 'title' => 'Sustainability Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/eco/eco-6.jpg', 'title' => 'Eco Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/eco/eco-7.jpg', 'title' => 'Green Team']
        ]
    ],
    'latien' => [
        'cover' => [['url' => 'assets/img/projects/latien/latien-cover.jpg', 'title' => 'La Tien Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/latien/latien-1.jpg', 'title' => 'Modern Entrance'],
            ['url' => 'assets/img/projects/latien/latien-2.jpg', 'title' => 'Smart Lobby'],
            ['url' => 'assets/img/projects/latien/latien-3.jpg', 'title' => 'Urban Garden']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/latien/latien-chinhsach-1.jpg', 'title' => 'Urban Policy'],
            ['url' => 'assets/img/projects/latien/latien-chinhsach-2.jpg', 'title' => 'Community Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/latien/latien-4.jpg', 'title' => 'Smart Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/latien/latien-vecan-1.jpg', 'title' => 'Urban Team']
        ]
    ],
    'tgc' => [
        'cover' => [['url' => 'assets/img/projects/tgc/tgc-cover.jpg', 'title' => 'TGC Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/tgc/tgc-1.jpg', 'title' => 'Commercial Entrance'],
            ['url' => 'assets/img/projects/tgc/tgc-2.jpg', 'title' => 'Shopping Area'],
            ['url' => 'assets/img/projects/tgc/tgc-3.jpg', 'title' => 'Business Hub']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/tgc/tgc-chinhsach-1.jpg', 'title' => 'Commercial Policy'],
            ['url' => 'assets/img/projects/tgc/tgc-chinhsach-2.jpg', 'title' => 'Business Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/tgc/tgc-4.jpg', 'title' => 'Commercial Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/tgc/tgc-5.jpg', 'title' => 'Business Team']
        ]
    ],
    'eaton' => [
        'cover' => [['url' => 'assets/img/projects/eaton/eaton-cover.jpg', 'title' => 'Eaton Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/eaton/eaton-1.jpg', 'title' => 'Industrial Entrance'],
            ['url' => 'assets/img/projects/eaton/eaton-2.jpg', 'title' => 'Tech Facility'],
            ['url' => 'assets/img/projects/eaton/eaton-3.jpg', 'title' => 'Production Area']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/eaton/eaton-4.jpg', 'title' => 'Industrial Policy'],
            ['url' => 'assets/img/projects/eaton/eaton-5.jpg', 'title' => 'Safety Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/eaton/eaton-6.jpg', 'title' => 'Industrial Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/eaton/eaton-7.jpg', 'title' => 'Industrial Team']
        ]
    ],
    'lapura' => [
        'cover' => [['url' => 'assets/img/projects/lapura/lapura-cover.jpg', 'title' => 'Lapura Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/lapura/lapura-1.jpg', 'title' => 'Resort Entrance'],
            ['url' => 'assets/img/projects/lapura/lapura-2.jpg', 'title' => 'Luxury Pool'],
            ['url' => 'assets/img/projects/lapura/lapura-3.jpg', 'title' => 'Spa Area']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/lapura/lapura-4.jpg', 'title' => 'Resort Policy'],
            ['url' => 'assets/img/projects/lapura/lapura-5.jpg', 'title' => 'Luxury Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/lapura/lapura-6.jpg', 'title' => 'Resort Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/lapura/lapura-7.jpg', 'title' => 'Resort Team']
        ]
    ],
    'mt' => [
        'cover' => [['url' => 'assets/img/projects/mt/mt-cover.jpg', 'title' => 'MT Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/mt/mt-1.jpg', 'title' => 'Commercial Entrance'],
            ['url' => 'assets/img/projects/mt/mt-2.jpg', 'title' => 'Multi-function Area'],
            ['url' => 'assets/img/projects/mt/mt-3.jpg', 'title' => 'Modern Facility']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/mt/mt-4.jpg', 'title' => 'Commercial Policy'],
            ['url' => 'assets/img/projects/mt/mt-5.jpg', 'title' => 'Business Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/mt/mt-6.jpg', 'title' => 'Commercial Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/mt/mt-7.jpg', 'title' => 'Commercial Team']
        ]
    ],
    'ttnb' => [
        'cover' => [['url' => 'assets/img/projects/ttnb/ttnb-cover.jpg', 'title' => 'TTNB Cover']],
        'gallery' => [
            ['url' => 'assets/img/projects/ttnb/ttnb-1.jpg', 'title' => 'Smart Entrance'],
            ['url' => 'assets/img/projects/ttnb/ttnb-2.jpg', 'title' => 'Tech Center'],
            ['url' => 'assets/img/projects/ttnb/ttnb-3.jpg', 'title' => 'Green Spaces']
        ],
        'policy' => [
            ['url' => 'assets/img/projects/ttnb/ttnb-4.jpg', 'title' => 'Smart City Policy'],
            ['url' => 'assets/img/projects/ttnb/ttnb-5.jpg', 'title' => 'Sustainability Guide']
        ],
        'floor' => [
            ['url' => 'assets/img/projects/ttnb/ttnb-6.jpg', 'title' => 'Smart Floor Plan']
        ],
        'recruitment' => [
            ['url' => 'assets/img/projects/ttnb/ttnb-7.jpg', 'title' => 'Smart Team']
        ]
    ],
    'latien' => [
        [
            'quote_vi' => 'La Tien mang đến không gian sống hiện đại và tiện nghi bậc nhất.',
            'quote_en' => 'La Tien provides the most modern and convenient living space.',
            'author' => 'Vũ Thị F',
            'role_title' => 'Urban Planning Expert'
        ],
        [
            'quote_vi' => 'Thiết kế thông minh của La Tien thật sự ấn tượng và phù hợp với lối sống đô thị.',
            'quote_en' => 'The smart design of La Tien is truly impressive and suitable for urban lifestyle.',
            'author' => 'Ngô Văn G',
            'role_title' => 'Real Estate Consultant'
        ]
    ],
    'tgc' => [
        [
            'quote_vi' => 'TGC là biểu tượng mới của thương mại hiện đại với thiết kế độc đáo.',
            'quote_en' => 'TGC is a new symbol of modern commerce with unique design.',
            'author' => 'Trần Văn H',
            'role_title' => 'Commercial Director'
        ],
        [
            'quote_vi' => 'Thiết kế thương mại của TGC thật sự ấn tượng và thu hút khách hàng.',
            'quote_en' => 'The commercial design of TGC is truly impressive and attracts customers.',
            'author' => 'Lê Thị I',
            'role_title' => 'Business Manager'
        ]
    ],
    'eaton' => [
        [
            'quote_vi' => 'Eaton mang lại hiệu quả công nghiệp vượt trội với thiết kế tiên tiến.',
            'quote_en' => 'Eaton brings superior industrial efficiency with advanced design.',
            'author' => 'Phạm Văn J',
            'role_title' => 'Industrial Manager'
        ],
        [
            'quote_vi' => 'Thiết kế công nghiệp của Eaton thật sự chuyên nghiệp và hiện đại.',
            'quote_en' => 'The industrial design of Eaton is truly professional and modern.',
            'author' => 'Ngô Thị K',
            'role_title' => 'Technology Consultant'
        ]
    ],
    'lapura' => [
        [
            'quote_vi' => 'Lapura mang đến trải nghiệm nghỉ dưỡng đẳng cấp quốc tế với thiết kế tuyệt vời.',
            'quote_en' => 'Lapura provides international-class resort experience with excellent design.',
            'author' => 'Lê Thị L',
            'role_title' => 'Resort Manager'
        ],
        [
            'quote_vi' => 'Thiết kế của Lapura thật sự sang trọng và tạo cảm giác thư giãn tuyệt vời.',
            'quote_en' => 'The design of Lapura is truly luxurious and creates wonderful relaxation feeling.',
            'author' => 'Vũ Văn M',
            'role_title' => 'Hospitality Consultant'
        ]
    ],
    'mt' => [
        [
            'quote_vi' => 'MT là mô hình thương mại đa chức năng hiệu quả với thiết kế thông minh.',
            'quote_en' => 'MT is an effective multifunctional commercial model with smart design.',
            'author' => 'Ngô Văn N',
            'role_title' => 'Commercial Director'
        ],
        [
            'quote_vi' => 'Thiết kế đa năng của MT thật sự ấn tượng và tối ưu hóa không gian.',
            'quote_en' => 'The multifunctional design of MT is truly impressive and optimizes space.',
            'author' => 'Trần Thị O',
            'role_title' => 'Business Analyst'
        ]
    ],
    'ttnb' => [
        [
            'quote_vi' => 'TTNB là khu đô thị thông minh tiên phong với công nghệ hiện đại bậc nhất.',
            'quote_en' => 'TTNB is a pioneering smart urban area with top-notch modern technology.',
            'author' => 'Phạm Văn P',
            'role_title' => 'Smart City Expert'
        ],
        [
            'quote_vi' => 'Thiết kế thông minh của TTNB thật sự ấn tượng và bền vững.',
            'quote_en' => 'The smart design of TTNB is truly impressive and sustainable.',
            'author' => 'Lê Thị Q',
            'role_title' => 'Urban Technology Consultant'
        ]
    ]
];

// Mock testimonials
$mockTestimonials = [
    'blanca' => [
        [
            'quote_vi' => 'Thiết kế thương hiệu của Blanca thật sự nổi bật và chuyên nghiệp.',
            'quote_en' => 'Blanca brand design is truly outstanding and professional.',
            'author' => 'Nguyễn Văn A',
            'role_title' => 'Giám đốc Marketing'
        ],
        [
            'quote_vi' => 'Chúng tôi rất hài lòng với kết quả thiết kế và phản hồi tích cực từ khách hàng.',
            'quote_en' => 'We are very satisfied with the design results and positive customer feedback.',
            'author' => 'Trần Thị B',
            'role_title' => 'Trưởng phòng Kinh doanh'
        ]
    ],
    'caraworld' => [
        [
            'quote_vi' => 'CaraWorld thể hiện đúng tinh thần công nghệ và hiện đại.',
            'quote_en' => 'CaraWorld truly reflects the spirit of technology and modernity.',
            'author' => 'Lê Văn C',
            'role_title' => 'CEO Tech Company'
        ]
    ],
    'eco' => [
        [
            'quote_vi' => 'Eco là dự án tiên phong trong lĩnh vực bất động sản xanh tại Việt Nam.',
            'quote_en' => 'Eco is a pioneer project in green real estate in Vietnam.',
            'author' => 'Phạm Thị D',
            'role_title' => 'Environmental Consultant'
        ],
        [
            'quote_vi' => 'Thiết kế sinh thái của Eco mang lại không gian sống trong lành và tiết kiệm năng lượng.',
            'quote_en' => 'Eco ecological design provides fresh living space and energy efficiency.',
            'author' => 'Hoàng Văn E',
            'role_title' => 'Green Building Expert'
        ]
    ]
];

// Find project by slug
$project = $mockProjects[$slug] ?? null;
if (!$project) {
    http_response_code(404);
    echo json_encode(['error' => 'Project not found']);
    exit;
}

// Get media and testimonials for this project
$media = $mockMedia[$slug] ?? ['cover' => [], 'gallery' => [], 'policy' => [], 'floor' => [], 'recruitment' => []];
$testimonials = $mockTestimonials[$slug] ?? [];

echo json_encode([
    'project' => $project,
    'media' => $media,
    'testimonials' => $testimonials
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);