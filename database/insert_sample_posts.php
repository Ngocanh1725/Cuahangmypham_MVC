<?php
$conn = new mysqli('localhost', 'root', '', 'cosmetics_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the table exists
$conn->query("CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$conn->query("TRUNCATE TABLE posts");

$posts = [
    [
        'title' => 'Xu Hướng Trang Điểm "Glass Skin" Vẫn Lên Ngôi Năm 2024',
        'content' => 'Làn da căng bóng như pha lê không còn là điều khó khăn nếu bạn nắm giữ 3 bí quyết chăm sóc da và lựa chọn kem nền dưới đây. Đầu tiên, hãy chú trọng dưỡng ẩm...',
        'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=1000&auto=format&fit=crop',
        'status' => 1
    ],
    [
        'title' => '5 Sai lầm khi dùng Retinol khiến da bạn "biểu tình"',
        'content' => 'Đừng để "thần dược" chống lão hóa trở thành thảm họa vì những thói quen sai lầm này. Dùng quá nhiều, không chống nắng, dùng sai bước...',
        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=500&auto=format&fit=crop',
        'status' => 1
    ],
    [
        'title' => 'Top 10 Kem Chống Nắng "Chân Ái" Cho Da Dầu Mụn',
        'content' => 'Khô ráo, không bóng nhờn và đặc biệt không gây bít tắc lỗ chân lông. Cùng khám phá ngay danh sách kem chống nắng không thể thiếu mùa hè này.',
        'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=500&auto=format&fit=crop',
        'status' => 1
    ],
    [
        'title' => 'Nghệ Thuật Xịt Nước Hoa Lưu Hương Suốt 24 Giờ',
        'content' => 'Vị trí nào trên cơ thể giúp mùi hương tỏa ra tinh tế và lâu phai nhất? Bí mật nằm ở các điểm mạch đập như cổ tay, sau gáy, hoặc nếp gấp khủy tay.',
        'image' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=500&auto=format&fit=crop',
        'status' => 1
    ]
];

$stmt = $conn->prepare("INSERT INTO posts (title, content, image, status) VALUES (?, ?, ?, ?)");
foreach ($posts as $p) {
    $stmt->bind_param("sssi", $p['title'], $p['content'], $p['image'], $p['status']);
    $stmt->execute();
}
$stmt->close();
$conn->close();
echo "Inserted sample posts.\n";
?>
