<?php
require __DIR__ . '/bootstrap.php';
require_login();

// Import project data from static HTML files to database
$projectsData = [
    [
        'slug' => 'internal',
        'title_vi' => 'Thiết kế Ấn phẩm Nội bộ & Truyền thông',
        'title_en' => 'Internal Collaterals & Communications',
        'summary_vi' => 'Hệ ấn phẩm nội bộ và truyền thông giúp đồng bộ tiếng nói thương hiệu, từ handbook, newsletter đến tài liệu đào tạo.',
        'summary_en' => 'Internal collateral system covering handbooks, newsletters, and training decks for a unified brand voice.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/ttnb/ttnb-1.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/ttnb/ttnb-2.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/ttnb/ttnb-3.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/ttnb/ttnb-4.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 04', 'sort_order' => 4],
                ['url' => '../assets/img/projects/ttnb/ttnb-5.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 05', 'sort_order' => 5],
                ['url' => '../assets/img/projects/ttnb/ttnb-6.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 06', 'sort_order' => 6],
                ['url' => '../assets/img/projects/ttnb/ttnb-7.jpg', 'title' => 'Ấn phẩm nội bộ ttnb 07', 'sort_order' => 7],
            ]
        ]
    ],
    [
        'slug' => 'blanca',
        'title_vi' => 'Blanca City',
        'title_en' => 'Blanca City',
        'summary_vi' => 'Bộ nhận diện Blanca City với lockup, palette và grid sang trọng.',
        'summary_en' => 'Identity kit for Blanca City with a refined lockup, palette, and grid.',
        'meta_role' => 'Design Specialist',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/blanca/blanca-1.jpg', 'title' => 'Blanca City visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/blanca/blanca-2.jpg', 'title' => 'Blanca City visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/blanca/blanca-3.jpg', 'title' => 'Blanca City visual 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/blanca/blanca-4.jpg', 'title' => 'Blanca City visual 04', 'sort_order' => 4],
                ['url' => '../assets/img/projects/blanca/blanca-5.jpg', 'title' => 'Blanca City visual 05', 'sort_order' => 5],
                ['url' => '../assets/img/projects/blanca/blanca-6.jpg', 'title' => 'Blanca City visual 06', 'sort_order' => 6],
                ['url' => '../assets/img/projects/blanca/blanca-7.jpg', 'title' => 'Blanca City visual 07', 'sort_order' => 7],
                ['url' => '../assets/img/projects/blanca/blanca-8.jpg', 'title' => 'Blanca City visual 08', 'sort_order' => 8],
                ['url' => '../assets/img/projects/blanca/blanca-9.jpg', 'title' => 'Blanca City visual 09', 'sort_order' => 9],
                ['url' => '../assets/img/projects/blanca/blanca-10.jpg', 'title' => 'Blanca City visual 10', 'sort_order' => 10],
                ['url' => '../assets/img/projects/blanca/blanca-11.jpg', 'title' => 'Blanca City visual 11', 'sort_order' => 11],
                ['url' => '../assets/img/projects/blanca/blanca-12.jpg', 'title' => 'Blanca City visual 12', 'sort_order' => 12],
                ['url' => '../assets/img/projects/blanca/blanca-13.jpg', 'title' => 'Blanca City visual 13', 'sort_order' => 13],
            ],
            'floor' => [
                ['url' => '../assets/img/projects/blanca/blanca-vecan-1.jpg', 'title' => 'Blanca City - Vẽ căn 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/blanca/blanca-vecan-2.jpg', 'title' => 'Blanca City - Vẽ căn 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/blanca/blanca-vecan-3.jpg', 'title' => 'Blanca City - Vẽ căn 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/blanca/blanca-vecan-4.jpg', 'title' => 'Blanca City - Vẽ căn 04', 'sort_order' => 4],
            ]
        ]
    ],
    [
        'slug' => 'latien',
        'title_vi' => 'La Tiên Villa',
        'title_en' => 'La Tiên Villa',
        'summary_vi' => 'Brochure La Tiên Villa phong cách Wabi-Sabi với layout tinh gọn, tập trung cảm xúc không gian.',
        'summary_en' => 'Wabi-Sabi inspired brochure for La Tiên Villa with minimal layouts and emotive storytelling.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/latien/latien-1.jpg', 'title' => 'La Tiên Villa visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/latien/latien-2.jpg', 'title' => 'La Tiên Villa visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/latien/latien-3.jpg', 'title' => 'La Tiên Villa visual 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/latien/latien-4.jpg', 'title' => 'La Tiên Villa visual 04', 'sort_order' => 4],
                ['url' => '../assets/img/projects/latien/latien-chinhsach-1.jpg', 'title' => 'La Tiên Villa chính sách 01', 'sort_order' => 5],
                ['url' => '../assets/img/projects/latien/latien-chinhsach-2.jpg', 'title' => 'La Tiên Villa chính sách 02', 'sort_order' => 6],
                ['url' => '../assets/img/projects/latien/latien-chinhsach-3.jpg', 'title' => 'La Tiên Villa chính sách 03', 'sort_order' => 7],
            ],
            'floor' => [
                ['url' => '../assets/img/projects/latien/latien-vecan-1.jpg', 'title' => 'La Tiên Villa - Vẽ căn 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/latien/latien-vecan-2.jpg', 'title' => 'La Tiên Villa - Vẽ căn 02', 'sort_order' => 2],
            ]
        ]
    ],
    [
        'slug' => 'ecopark',
        'title_vi' => 'Ecopark',
        'title_en' => 'Ecopark',
        'summary_vi' => 'Hệ nhận diện mang cảm hứng resort cao cấp cho Ecopark gồm guideline, POSM và social kit.',
        'summary_en' => 'Resort-inspired identity refresh for Ecopark covering guidelines, POSM, and social kits.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/eco/eco-1.jpg', 'title' => 'Ecopark visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/eco/eco-2.jpg', 'title' => 'Ecopark visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/eco/eco-3.jpg', 'title' => 'Ecopark visual 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/eco/eco-chinhsach-1.jpg', 'title' => 'Ecopark chính sách 01', 'sort_order' => 4],
                ['url' => '../assets/img/projects/eco/eco-chinhsach-2.jpg', 'title' => 'Ecopark chính sách 02', 'sort_order' => 5],
            ]
        ]
    ],
    [
        'slug' => 'lapura',
        'title_vi' => 'Lapura',
        'title_en' => 'Lapura',
        'summary_vi' => 'Trọn bộ nhận diện Lapura cho phân khúc an cư cao cấp: logo set, sales kit và brandbook.',
        'summary_en' => 'Complete identity for Lapura\'s upscale residential line including logo set, sales kit, and brandbook.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => []
    ],
    [
        'slug' => 'caraworld',
        'title_vi' => 'Caraworld',
        'title_en' => 'Caraworld',
        'summary_vi' => 'Visual system kể câu chuyện lifestyle Caraworld: social content, brochure và landing page.',
        'summary_en' => 'Lifestyle visual system for Caraworld spanning social content, brochure, and landing page.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/caraworld/caraworld-1.jpg', 'title' => 'Caraworld visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/caraworld/caraworld-2.jpg', 'title' => 'Caraworld visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/caraworld/caraworld-3.jpg', 'title' => 'Caraworld visual 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/caraworld/caraworld-4.jpg', 'title' => 'Caraworld visual 04', 'sort_order' => 4],
                ['url' => '../assets/img/projects/caraworld/caraworld-5.jpg', 'title' => 'Caraworld visual 05', 'sort_order' => 5],
            ]
        ]
    ],
    [
        'slug' => 'globalcity',
        'title_vi' => 'The Global City',
        'title_en' => 'The Global City',
        'summary_vi' => 'Launch assets đa nền tảng cho The Global City: KV chuyển động, social ads, sales deck và POSM.',
        'summary_en' => 'Multi-channel launch assets for The Global City: animated KV, social ads, sales deck, and POSM.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/tgc/tgc-1.jpg', 'title' => 'The Global City visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/tgc/tgc-2.jpg', 'title' => 'The Global City visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/tgc/tgc-3.png.jpg', 'title' => 'The Global City visual 03', 'sort_order' => 3],
            ]
        ]
    ],
    [
        'slug' => 'celadon',
        'title_vi' => 'Celadon City',
        'title_en' => 'Celadon City',
        'summary_vi' => 'Thiết kế trải nghiệm Celadon City gồm microsite, bản đồ tiện ích và brochure tương tác.',
        'summary_en' => 'Experience suite for Celadon City including microsite, amenity map, and interactive brochure.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => [
            'gallery' => [
                ['url' => '../assets/img/projects/celadon/celadon-1.jpg', 'title' => 'Celadon City visual 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/celadon/celadon-2.jpg', 'title' => 'Celadon City visual 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/celadon/celadon-3.jpg', 'title' => 'Celadon City visual 03', 'sort_order' => 3],
                ['url' => '../assets/img/projects/celadon/celadon-4.jpg', 'title' => 'Celadon City visual 04', 'sort_order' => 4],
                ['url' => '../assets/img/projects/celadon/celadon-5.jpg', 'title' => 'Celadon City visual 05', 'sort_order' => 5],
                ['url' => '../assets/img/projects/celadon/celadon-6.jpg', 'title' => 'Celadon City visual 06', 'sort_order' => 6],
                ['url' => '../assets/img/projects/celadon/celadon-chinhsach-1.jpg', 'title' => 'Celadon City chính sách 01', 'sort_order' => 7],
                ['url' => '../assets/img/projects/celadon/celadon-chinhsach-2.jpg', 'title' => 'Celadon City chính sách 02', 'sort_order' => 8],
                ['url' => '../assets/img/projects/celadon/celadon-chinhsach-3.jpg', 'title' => 'Celadon City chính sách 03', 'sort_order' => 9],
            ],
            'floor' => [
                ['url' => '../assets/img/projects/celadon/celadon-vecan-1.jpg', 'title' => 'Celadon City - Vẽ căn 01', 'sort_order' => 1],
                ['url' => '../assets/img/projects/celadon/celadon-vecan-2.jpg', 'title' => 'Celadon City - Vẽ căn 02', 'sort_order' => 2],
                ['url' => '../assets/img/projects/celadon/celadon-vecan-3.jpg', 'title' => 'Celadon City - Vẽ căn 03', 'sort_order' => 3],
            ]
        ]
    ],
    [
        'slug' => 'mteastmark',
        'title_vi' => 'MT Eastmark City',
        'title_en' => 'MT Eastmark City',
        'summary_vi' => 'Sales kit và performance ad set cho MT Eastmark City, tối ưu quy trình bán hàng.',
        'summary_en' => 'Sales kit and performance ad set for MT Eastmark City to streamline the sales workflow.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2024–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => []
    ],
    [
        'slug' => 'miscprojects',
        'title_vi' => 'Tổng Hợp Dự Án Nhỏ & Ấn Phẩm Hỗ Trợ',
        'title_en' => 'Supporting Works & Collaterals',
        'summary_vi' => 'Tập hợp mini campaign, tài liệu đào tạo, POSM phụ trợ phục vụ vận hành thương hiệu.',
        'summary_en' => 'Collection of mini campaigns, training materials, and supporting POSM for day-to-day operations.',
        'meta_role' => 'Art Director · Designer',
        'meta_time' => '2022–2025',
        'meta_tools' => 'AI, PS, ID, Figma',
        'objective_vi' => 'Mục tiêu: Tăng nhận biết thương hiệu & thúc đẩy chuyển đổi qua hệ thống visual đồng nhất đa kênh.',
        'objective_en' => 'Objective: Boost brand awareness and conversions through a cohesive visual system across every channel.',
        'challenge_vi' => 'Thách thức: Deadline gấp, nhiều điểm chạm (POSM, social, landing), phải đảm bảo tính nhất quán và hiệu quả sản xuất.',
        'challenge_en' => 'Challenge: Tight timeline with many touchpoints (POSM, social, landing) requiring consistency and efficient production.',
        'strategy_vi' => 'Chiến lược: Thiết lập lưới bố cục, palette & typographic scale; chuẩn hoá template; tạo asset kit dùng lại được.',
        'strategy_en' => 'Strategy: Build a layout grid, palette, and typographic scale; standardize templates; create a reusable asset kit.',
        'workflow_vi' => 'Workflow: Tư duy Design Thinking — Discover → Define → Develop → Deliver, kiểm thử A/B.',
        'workflow_en' => 'Workflow: Design Thinking — Discover → Define → Develop → Deliver, with iterative A/B testing.',
        'kpi1' => '+45%',
        'kpi2' => '-30%',
        'kpi3' => '+120%',
        'kpi4' => '98%',
        'media' => []
    ]
];

try {
    $pdo->beginTransaction();

    foreach ($projectsData as $index => $project) {
        // Insert project
        $stmt = $pdo->prepare('
            INSERT INTO projects (
                slug, title_vi, title_en, summary_vi, summary_en,
                meta_role, meta_time, meta_tools,
                objective_vi, objective_en, challenge_vi, challenge_en,
                strategy_vi, strategy_en, workflow_vi, workflow_en,
                kpi1, kpi2, kpi3, kpi4, sort_order
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $project['slug'],
            $project['title_vi'],
            $project['title_en'],
            $project['summary_vi'],
            $project['summary_en'],
            $project['meta_role'],
            $project['meta_time'],
            $project['meta_tools'],
            $project['objective_vi'],
            $project['objective_en'],
            $project['challenge_vi'],
            $project['challenge_en'],
            $project['strategy_vi'],
            $project['strategy_en'],
            $project['workflow_vi'],
            $project['workflow_en'],
            $project['kpi1'],
            $project['kpi2'],
            $project['kpi3'],
            $project['kpi4'],
            $index + 1
        ]);

        $projectId = $pdo->lastInsertId();

        // Insert media
        if (!empty($project['media'])) {
            foreach ($project['media'] as $section => $mediaItems) {
                foreach ($mediaItems as $media) {
                    $stmt = $pdo->prepare('
                        INSERT INTO project_media (project_id, section, url, title, sort_order)
                        VALUES (?, ?, ?, ?, ?)
                    ');
                    $stmt->execute([
                        $projectId,
                        $section,
                        $media['url'],
                        $media['title'],
                        $media['sort_order']
                    ]);
                }
            }
        }
    }

    $pdo->commit();
    redirect_with_message('projects.php', 'Đã import thành công ' . count($projectsData) . ' dự án vào database.');

} catch (Throwable $e) {
    $pdo->rollBack();
    redirect_with_message('projects.php', 'Lỗi import: ' . $e->getMessage(), 'error');
}
?>
