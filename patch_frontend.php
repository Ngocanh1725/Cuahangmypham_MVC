<?php
$files = [
    'views/home/index.php',
    'views/products/index.php'
];

$badgeCode = <<<PHP
                            <?php if(isset(\$p['stock']) && \$p['stock'] <= 0): ?>
                                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.5); z-index: 10; display:flex; align-items:center; justify-content:center;">
                                    <span style="background: #6c757d; color: #fff; padding: 6px 14px; font-weight: bold; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>
                            <button class="wishlist-btn" type="button"><i class="far fa-heart"></i></button>
PHP;

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Thay thế nút wishlist để chèn badge hết hàng ngay trước đó
        $content = str_replace('<button class="wishlist-btn" type="button"><i class="far fa-heart"></i></button>', $badgeCode, $content);
        
        // Thêm filter grayscale cho thẻ img.prod-img
        $content = preg_replace('/(<img[^>]+class="prod-img"[^>]*?)>/is', '$1 <?= (isset($p[\'stock\']) && $p[\'stock\'] <= 0) ? \'style="filter: grayscale(80%);"\' : \'\' ?>>', $content);
        
        file_put_contents($file, $content);
        echo "Patched $file\n";
    }
}
?>
