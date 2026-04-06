<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Sử dụng biến $pageTitle để đổi tên tab động cho từng trang -->
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Glow Cosmetics MVC'; ?></title>
    
    <!-- Các thư viện dùng chung cho toàn dự án -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tự động load CSS tùy thuộc vào việc đang ở Admin hay Shop -->
    <?php if (isset($_GET['controller']) && $_GET['controller'] == 'admin'): ?>
        <link rel="stylesheet" href="css/style.css"> <!-- CSS cho Admin -->
    <?php else: ?>
        <link rel="stylesheet" href="css/style_user.css"> <!-- CSS cho Shop -->
    <?php endif; ?>

    <!-- Nơi chèn CSS phụ trợ cho từng trang (nếu có) -->
    <?php if(isset($extraCSS)) echo $extraCSS; ?>
<style>
    /* Thanh topbar màu tím */
    .top-promo-bar {
        background-color: #6a2c91;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    /* Thanh tìm kiếm */
    .search-box-custom {
        background-color: #f5f5f5;
        border-radius: 50px;
        padding: 5px 20px;
    }
    .search-box-custom input {
        border: none;
        background: transparent;
        box-shadow: none;
        outline: none;
        width: 100%;
    }

    /* Các nút danh mục dạng viên thuốc (Pill buttons) */
    .pill-nav .nav-link {
        padding: 8px 24px;
        border-radius: 50px;
        color: #000 !important;
        font-weight: 500;
        font-size: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        white-space: nowrap;
    }
    .pill-nav .nav-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Bảng màu pastel giống Beauty Box */
    .bg-pill-1 { background-color: #fce4ec; } /* Hồng nhạt */
    .bg-pill-2 { background-color: #ffe0b2; } /* Cam nhạt */
    .bg-pill-3 { background-color: #ffcdd2; } /* Đỏ nhạt */
    .bg-pill-4 { background-color: #dcedc8; } /* Xanh lá nhạt */
    .bg-pill-5 { background-color: #b3e5fc; } /* Xanh dương nhạt */
    .bg-pill-6 { background-color: #b2dfdb; } /* Xanh ngọc */
    .bg-pill-7 { background-color: #ffcc80; } /* Cam đậm hơn */

    /* Animation cho Dropdown Menu */
    .custom-dropdown {
        position: relative;
    }
    .custom-dropdown-menu {
        position: absolute;
        top: 150%;
        right: 0;
        background: #fff;
        min-width: 250px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 15px 0;
        z-index: 1000;
        list-style: none;
        /* Animation properties */
        visibility: hidden;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    /* Class 'show' sẽ được JS thêm vào khi click */
    .custom-dropdown-menu.show {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }
    .custom-dropdown-menu li a {
        padding: 12px 24px;
        display: block;
        color: #333;
        text-decoration: none;
        font-size: 15px;
        transition: background 0.2s;
    }
    .custom-dropdown-menu li a:hover {
        background-color: #f8f9fa;
        color: #6a2c91;
    }
    .custom-dropdown-menu i {
        width: 25px;
        color: #666;
    }
</style>
</head>
<body class="bg-light">