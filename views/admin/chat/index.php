<?php
$pageTitle = "Quản lý Tin nhắn | Glow Admin";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .admin-content { padding: 30px; height: 100vh; display: flex; flex-direction: column; }
        .chat-container { flex: 1; display: flex; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .chat-sidebar { width: 300px; border-right: 1px solid #eee; display: flex; flex-direction: column; }
        .chat-main { flex: 1; display: flex; flex-direction: column; background: #fdfdfd; }
        .user-item { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; transition: background 0.3s; }
        .user-item:hover, .user-item.active { background: #fdf0f5; }
        .user-avatar { width: 45px; height: 45px; border-radius: 50%; background: var(--brand-primary, #db2777); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; margin-right: 15px; }
        .user-info { flex: 1; overflow: hidden; }
        .user-name { font-weight: 600; margin-bottom: 3px; color: #333; }
        .user-last-msg { font-size: 0.85rem; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        .chat-header { padding: 20px; border-bottom: 1px solid #eee; background: white; display: flex; align-items: center; }
        .chat-body { flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; }
        .chat-footer { padding: 20px; background: white; border-top: 1px solid #eee; display: flex; }
        .chat-input { flex: 1; border: 1px solid #ddd; border-radius: 25px; padding: 12px 20px; outline: none; }
        .chat-send-btn { background: var(--brand-primary, #db2777); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; margin-left: 15px; cursor: pointer; transition: 0.3s; }
        .chat-send-btn:hover { background: #be185d; }
        
        .msg-bubble { max-width: 70%; padding: 12px 18px; border-radius: 20px; margin-bottom: 15px; font-size: 0.95rem; clear: both; }
        .msg-client { background: #e9ecef; color: #333; align-self: flex-start; border-bottom-left-radius: 5px; }
        .msg-admin { background: var(--brand-primary, #db2777); color: white; align-self: flex-end; border-bottom-right-radius: 5px; }

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
        .msg-product-card img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .msg-product-info { flex: 1; }
        .msg-product-name { font-weight: bold; font-size: 0.85rem; margin: 0; }
        .msg-product-price { color: #db2777; font-weight: bold; font-size: 0.8rem; margin: 0; }
        
        .chat-attach-btn { background: none; border: none; color: #6c757d; font-size: 20px; margin-right: 10px; cursor: pointer; }
        .chat-search-product {
            position: absolute; bottom: 100%; left: 0; right: 0; background: white;
            border-top: 1px solid #eee; box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            display: none; flex-direction: column; z-index: 10; border-radius: 10px 10px 0 0;
        }
        .chat-search-product.active { display: flex; }
        .chat-search-input { border: none; padding: 10px 15px; border-bottom: 1px solid #eee; outline: none; border-radius: 10px 10px 0 0; }
        .chat-search-results { max-height: 150px; overflow-y: auto; }
        .chat-search-item { padding: 10px 15px; display: flex; align-items: center; gap: 10px; cursor: pointer; border-bottom: 1px solid #f8f9fa; }
        .chat-search-item:hover { background: #f8f9fa; }
        .chat-search-item img { width: 30px; height: 30px; object-fit: cover; border-radius: 4px; }
        .selected-product-badge {
            background: #fce7f3; color: #db2777; padding: 5px 10px; border-radius: 15px;
            font-size: 0.8rem; display: none; align-items: center; margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <?php require_once 'views/admin/includes/sidebar.php'; ?>
            
            <div class="col-md-10 bg-light">
                <div class="admin-content">
                    <h3 class="fw-bold mb-4"><i class="fas fa-comment-dots text-primary me-2"></i> Hỗ trợ Khách hàng</h3>
                    
                    <div class="chat-container">
                        <!-- Cột danh sách user -->
                        <div class="chat-sidebar">
                            <div class="p-3 border-bottom bg-light">
                                <h6 class="fw-bold mb-0 text-muted text-uppercase">Danh sách Chat</h6>
                            </div>
                            <div style="overflow-y: auto; flex: 1;">
                                <?php if(empty($chatUsers)): ?>
                                    <div class="p-4 text-center text-muted">Chưa có khách hàng nào nhắn tin.</div>
                                <?php else: ?>
                                    <?php foreach($chatUsers as $u): ?>
                                        <div class="user-item <?php echo ($active_user_id == $u['id']) ? 'active' : ''; ?>" onclick="window.location.href='index.php?controller=admin&action=chat&user_id=<?php echo $u['id']; ?>'">
                                            <div class="user-avatar"><?php echo strtoupper(substr($u['fullname'], 0, 1)); ?></div>
                                            <div class="user-info">
                                                <div class="user-name"><?php echo htmlspecialchars($u['fullname']); ?></div>
                                                <div class="user-last-msg"><?php echo htmlspecialchars($u['last_message']); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Khung chat chính -->
                        <div class="chat-main">
                            <?php if ($active_user_id > 0): ?>
                                <?php 
                                    $activeUser = null;
                                    foreach($chatUsers as $u) {
                                        if($u['id'] == $active_user_id) $activeUser = $u;
                                    }
                                ?>
                                <div class="chat-header">
                                    <div class="user-avatar" style="width: 40px; height: 40px; font-size: 18px; margin-right: 15px;">
                                        <?php echo strtoupper(substr($activeUser['fullname'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($activeUser['fullname']); ?></h5>
                                        <small class="text-success"><i class="fas fa-circle" style="font-size: 8px;"></i> Online</small>
                                    </div>
                                </div>
                                <div class="chat-body" id="chatBody">
                                    <!-- Messages load via AJAX or PHP initially -->
                                </div>
                                <div style="padding: 0 20px;">
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
                                    <input type="text" id="chatInput" class="chat-input" placeholder="Nhập tin nhắn hỗ trợ..." onkeypress="if(event.key === 'Enter') sendAdminChat()">
                                    <input type="hidden" id="chatProductId" value="">
                                    <button class="chat-send-btn" onclick="sendAdminChat()"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="far fa-comments fa-4x mb-3 text-light"></i>
                                    <h5>Chọn một cuộc trò chuyện để bắt đầu</h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if ($active_user_id > 0): ?>
    <script>
        const userId = <?php echo $active_user_id; ?>;
        let lastMessageCount = 0;

        function loadAdminMessages() {
            fetch('index.php?controller=admin&action=getAdminMessages&user_id=' + userId)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const body = document.getElementById('chatBody');
                        
                        const shouldScroll = data.data.length > lastMessageCount;
                        lastMessageCount = data.data.length;

                        let html = '';
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

        function sendAdminChat() {
            const input = document.getElementById('chatInput');
            const productIdInput = document.getElementById('chatProductId');
            const msg = input.value.trim();
            const pId = productIdInput.value;
            
            if (msg === '' && pId === '') return;
            
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('message', msg);
            formData.append('product_id', pId);
            
            fetch('index.php?controller=admin&action=sendAdminMessage', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = '';
                    removeSelectedProduct();
                    loadAdminMessages();
                }
            });
        }

        // Load initially and set interval
        loadAdminMessages();
        setInterval(loadAdminMessages, 3000);
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
