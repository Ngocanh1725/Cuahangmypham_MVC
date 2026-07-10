<style>
    /* Footer Style */
    .rhode-footer {
        background-color: #ffffff;
        padding: 80px 0 30px;
        margin-top: 60px;
        border-top: 1px solid var(--rhode-pink-light);
    }
    
    .footer-brand {
        font-family: var(--font-serif);
        font-size: 2.5rem;
        color: var(--rhode-pink-accent);
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .footer-title {
        font-family: var(--font-serif);
        font-size: 1.2rem;
        color: var(--text-main);
        margin-bottom: 25px;
        font-weight: 600;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: var(--text-light);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .footer-links a:hover {
        color: var(--rhode-pink-accent);
        padding-left: 5px; /* Hiệu ứng trượt nhẹ khi hover */
    }

    /* Form đăng ký footer */
    .footer-newsletter .input-group {
        background: var(--rhode-bg-main);
        border-radius: var(--radius-pill);
        padding: 5px;
        border: 1px solid var(--rhode-pink-light);
    }

    .footer-newsletter input {
        border: none;
        background: transparent;
        padding: 10px 20px;
        box-shadow: none;
        font-size: 0.9rem;
    }
    
    .footer-newsletter input:focus {
        outline: none;
        box-shadow: none;
        background: transparent;
    }

    .footer-newsletter button {
        border-radius: var(--radius-pill) !important;
        padding: 10px 25px;
    }

    /* Social Icons */
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icons a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--rhode-bg-main);
        color: var(--text-main);
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-icons a:hover {
        background-color: var(--rhode-pink-accent);
        color: #fff;
        transform: translateY(-3px);
    }

    .footer-bottom {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding-top: 20px;
        margin-top: 60px;
        font-size: 0.85rem;
        color: var(--text-light);
    }
</style>

<footer class="rhode-footer">
    <div class="container">
        <div class="row g-5">
            <!-- Cột 1: Thông tin thương hiệu -->
            <div class="col-lg-4 col-md-6">
                <a href="index.php" class="footer-brand">glow.</a>
                <p class="text-muted pe-lg-4" style="font-size: 0.95rem; line-height: 1.6;">
                    Triết lý làm đẹp tôn vinh sự tự nhiên. Cung cấp các dòng sản phẩm thuần chay, lành tính, giúp làn da bạn luôn căng mọng và rạng rỡ mỗi ngày.
                </p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <!-- Cột 2: Cửa hàng -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Cửa hàng</h4>
                <ul class="footer-links">
                    <li><a href="index.php?controller=product&action=index">Tất cả sản phẩm</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Chăm sóc da">Chăm sóc da</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Trang điểm">Trang điểm</a></li>
                    <li><a href="index.php?controller=product&action=index&category[]=Phụ kiện">Phụ kiện làm đẹp</a></li>
                    <li><a href="index.php?controller=brand&action=index">Thương hiệu</a></li>
                </ul>
            </div>

            <!-- Cột 3: Hỗ trợ khách hàng -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Hỗ trợ</h4>
                <?php
                if (!class_exists('MenuModel')) {
                    require_once 'models/MenuModel.php';
                }
                global $db;
                $menuModel = new MenuModel($db);
                $footerMenus = $menuModel->getMenuTree('footer');
                ?>
                <ul class="footer-links">
                    <?php foreach ($footerMenus as $fmenu): ?>
                        <li><a href="<?php echo htmlspecialchars($fmenu['url']); ?>" target="<?php echo htmlspecialchars($fmenu['target']); ?>"><?php echo htmlspecialchars($fmenu['title']); ?></a></li>
                        <?php if (!empty($fmenu['children'])): ?>
                            <ul class="ps-3 mt-1 list-unstyled">
                                <?php foreach ($fmenu['children'] as $child): ?>
                                    <li><a href="<?php echo htmlspecialchars($child['url']); ?>" target="<?php echo htmlspecialchars($child['target']); ?>" class="text-muted" style="font-size: 0.85rem;">- <?php echo htmlspecialchars($child['title']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Cột 4: Đăng ký nhận tin -->
            <div class="col-lg-4 col-md-6">
                <h4 class="footer-title">Đừng bỏ lỡ</h4>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">Đăng ký để nhận thông tin về sản phẩm mới, xu hướng làm đẹp và các chương trình ưu đãi độc quyền.</p>
                <form class="footer-newsletter">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email của bạn..." required>
                        <button class="btn rhode-btn-primary" type="button">Gửi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dòng bản quyền cuối trang -->
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">&copy; <?php echo date('Y'); ?> Glow Cosmetics. Đã đăng ký bản quyền.</p>
            <div class="payment-methods">
                <i class="fab fa-cc-visa fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-mastercard fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-paypal fa-lg text-muted me-2"></i>
                <i class="fab fa-cc-apple-pay fa-lg text-muted"></i>
            </div>
        </div>
    </div>
</footer>

<!-- Nhúng Bootstrap JS (nếu dự án của bạn đang dùng) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- FontAwesome (Dành cho Icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CHAT WIDGET (FRONTEND) -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <style>
        .chat-widget-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: var(--brand-primary, #db2777);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 5px 15px rgba(219, 39, 119, 0.4);
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .chat-widget-btn:hover { transform: scale(1.1); }
        .chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 450px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .chat-window.active { display: flex; }
        .chat-header {
            background-color: var(--brand-primary, #db2777);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
        }
        .chat-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }
        .chat-footer {
            padding: 10px;
            border-top: 1px solid #eee;
            display: flex;
        }
        .chat-input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            outline: none;
        }
        .chat-send-btn {
            background: none;
            border: none;
            color: var(--brand-primary, #db2777);
            font-size: 20px;
            margin-left: 10px;
            cursor: pointer;
        }
        .msg-bubble {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            clear: both;
        }
        .msg-client {
            background: var(--brand-primary, #db2777);
            color: white;
            float: right;
            border-bottom-right-radius: 2px;
        }
        .msg-admin {
            background: #e9ecef;
            color: #333;
            float: left;
            border-bottom-left-radius: 2px;
        }
        .msg-product-card {
            background: white;
            border-radius: 10px;
            padding: 10px;
            margin-top: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #333;
        }
        .msg-product-card img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .msg-product-info { flex: 1; }
        .msg-product-name { font-weight: bold; font-size: 0.85rem; margin: 0; }
        .msg-product-price { color: #db2777; font-weight: bold; font-size: 0.8rem; margin: 0; }
        
        .chat-attach-btn {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 20px;
            margin-right: 10px;
            cursor: pointer;
        }
        .chat-search-product {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #eee;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            display: none;
            flex-direction: column;
            z-index: 10;
        }
        .chat-search-product.active { display: flex; }
        .chat-search-input {
            border: none;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            outline: none;
        }
        .chat-search-results {
            max-height: 150px;
            overflow-y: auto;
        }
        .chat-search-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
        }
        .chat-search-item:hover { background: #f8f9fa; }
        .chat-search-item img { width: 30px; height: 30px; object-fit: cover; border-radius: 4px; }
        .selected-product-badge {
            background: #fce7f3;
            color: #db2777;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: none;
            align-items: center;
            margin-bottom: 5px;
        }
    </style>

    <div class="chat-widget-btn" onclick="toggleChat()">
        <i class="fas fa-comment-dots"></i>
    </div>

    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <span><i class="fas fa-headset me-2"></i> Glow Support</span>
            <i class="fas fa-times" style="cursor: pointer;" onclick="toggleChat()"></i>
        </div>
        <div class="chat-body" id="chatBody">
            <!-- Tin nhắn sẽ được load bằng AJAX -->
        </div>
        <div style="padding: 0 10px;">
            <div class="selected-product-badge" id="selectedProductBadge">
                <span id="selectedProductName">Sản phẩm...</span>
                <i class="fas fa-times ms-2" style="cursor:pointer;" onclick="removeSelectedProduct()"></i>
            </div>
        </div>
        <div class="chat-footer" style="position: relative;">
            <div class="chat-search-product" id="chatSearchProduct">
                <input type="text" class="chat-search-input" placeholder="Tìm tên sản phẩm..." oninput="searchProductChat(this.value)">
                <div class="chat-search-results" id="chatSearchResults"></div>
            </div>
            
            <button class="chat-attach-btn" onclick="toggleProductSearch()"><i class="fas fa-box-open"></i></button>
            <input type="text" id="chatInput" class="chat-input" placeholder="Nhập tin nhắn..." onkeypress="if(event.key === 'Enter') sendChat()">
            <input type="hidden" id="chatProductId" value="">
            <button class="chat-send-btn" onclick="sendChat()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script>
        let chatInterval = null;
        let lastMessageCount = 0;

        function toggleChat() {
            const win = document.getElementById('chatWindow');
            win.classList.toggle('active');
            if (win.classList.contains('active')) {
                loadMessages();
                chatInterval = setInterval(loadMessages, 3000); // Load lại mỗi 3s
            } else {
                clearInterval(chatInterval);
            }
        }

        function loadMessages() {
            fetch('index.php?controller=chat&action=getMessages')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const body = document.getElementById('chatBody');
                        
                        // Chỉ cuộn xuống nếu có tin nhắn mới
                        const shouldScroll = data.data.length > lastMessageCount;
                        lastMessageCount = data.data.length;

                        let html = '';
                        if (data.data.length === 0) {
                            html = '<div class="text-center text-muted mt-3 small">Bắt đầu trò chuyện với chúng tôi.</div>';
                        } else {
                            data.data.forEach(msg => {
                                let productHtml = '';
                                if (msg.product_id) {
                                    const priceStr = new Intl.NumberFormat('vi-VN').format(msg.product_price) + 'đ';
                                    productHtml = `
                                        <a href="index.php?controller=product&action=detail&id=${msg.product_id}" target="_blank" class="msg-product-card">
                                            <img src="assets/images/${msg.product_image}" alt="">
                                            <div class="msg-product-info">
                                                <p class="msg-product-name">${msg.product_name}</p>
                                                <p class="msg-product-price">${priceStr}</p>
                                            </div>
                                        </a>
                                    `;
                                }
                                
                                const msgText = msg.message ? `<div>${msg.message}</div>` : '';
                                
                                if (msg.is_admin == 0) {
                                    html += `<div class="msg-bubble msg-client">${msgText}${productHtml}</div>`;
                                } else {
                                    html += `<div class="msg-bubble msg-admin">${msgText}${productHtml}</div>`;
                                }
                            });
                        }
                        
                        // Nếu đang ở dưới cùng hoặc có tin mới, thì auto scroll xuống dưới
                        const isScrolledToBottom = body.scrollHeight - body.clientHeight <= body.scrollTop + 50;
                        
                        body.innerHTML = html;
                        
                        if (shouldScroll || isScrolledToBottom) {
                            body.scrollTop = body.scrollHeight;
                        }
                    }
                });
        }

        function toggleProductSearch() {
            document.getElementById('chatSearchProduct').classList.toggle('active');
        }
        
        let searchTimeout = null;
        function searchProductChat(keyword) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (keyword.length >= 2) {
                    fetch('index.php?controller=chat&action=searchProduct&q=' + encodeURIComponent(keyword))
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const resDiv = document.getElementById('chatSearchResults');
                                resDiv.innerHTML = '';
                                data.data.forEach(p => {
                                    const price = new Intl.NumberFormat('vi-VN').format(p.price) + 'đ';
                                    resDiv.innerHTML += `
                                        <div class="chat-search-item" onclick="selectProductForChat(${p.id}, '${p.name.replace(/'/g, "\\'")}')">
                                            <img src="assets/images/${p.image}" alt="">
                                            <div>
                                                <div style="font-size:0.8rem; font-weight:bold">${p.name}</div>
                                                <div style="font-size:0.75rem; color:#db2777">${price}</div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        });
                }
            }, 300);
        }
        
        function selectProductForChat(id, name) {
            document.getElementById('chatProductId').value = id;
            document.getElementById('selectedProductName').innerText = name;
            document.getElementById('selectedProductBadge').style.display = 'inline-flex';
            document.getElementById('chatSearchProduct').classList.remove('active');
            document.querySelector('.chat-search-input').value = '';
            document.getElementById('chatSearchResults').innerHTML = '';
        }
        
        function removeSelectedProduct() {
            document.getElementById('chatProductId').value = '';
            document.getElementById('selectedProductBadge').style.display = 'none';
        }

        function sendChat() {
            const input = document.getElementById('chatInput');
            const productIdInput = document.getElementById('chatProductId');
            const msg = input.value.trim();
            const pId = productIdInput.value;
            
            if (msg === '' && pId === '') return;
            
            const formData = new FormData();
            formData.append('message', msg);
            formData.append('product_id', pId);
            
            fetch('index.php?controller=chat&action=sendMessage', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = '';
                    removeSelectedProduct();
                    loadMessages(); // Load ngay lập tức
                }
            });
        }
    </script>
    <?php endif; ?>

    <?php 
        if (isset($extraJS)) {
            echo $extraJS;
        }
    ?>
</body>
</html>