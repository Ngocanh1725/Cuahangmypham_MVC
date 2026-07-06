<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Glow Cosmetics - Đánh Thức Vẻ Đẹp Tự Nhiên'; ?></title>
    
    <!-- Thư viện CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Thư viện Font Awesome (Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tự động load CSS: Nếu là link admin thì tải style.css, nếu khách hàng tải style_user.css -->
    <?php if (isset($_GET['controller']) && $_GET['controller'] == 'admin'): ?>
        <link rel="stylesheet" href="assets/css/style.css">
    <?php else: ?>
        <link rel="stylesheet" href="assets/css/style_user.css">
    <?php endif; ?>

    <!-- Nơi nhúng thêm CSS riêng lẻ cho từng trang nếu cần -->
    <?php if(isset($extraCSS)) echo $extraCSS; ?>
</head>
<body>