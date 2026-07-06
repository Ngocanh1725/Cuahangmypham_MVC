/**
 * ============================================================
 * CART.JS - AJAX Cart System (Fetch API)
 * Glow Cosmetics - Cửa hàng Mỹ Phẩm MVC
 * ============================================================
 * Chức năng:
 * 1. Thêm sản phẩm vào giỏ hàng (AJAX, không reload)
 * 2. Cập nhật số lượng trong giỏ (AJAX)
 * 3. Xóa sản phẩm khỏi giỏ (AJAX)
 * 4. Cập nhật badge giỏ hàng trên navbar
 * 5. Toast notification mượt mà
 */

// ============================================================
// 1. TOAST NOTIFICATION SYSTEM
// ============================================================
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.innerHTML = `
        <i class="${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button class="toast-close" onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300);">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Tự động xóa sau 3 giây
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

// ============================================================
// 2. THÊM VÀO GIỎ HÀNG (AJAX)
// ============================================================
function addToCart(productId, productName) {
    fetch(`index.php?controller=cart&action=ajaxAdd&id=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(
                `<i class="fas fa-shopping-bag me-1"></i> <strong>${productName || 'Sản phẩm'}</strong> đã được thêm vào giỏ hàng!`, 
                'success'
            );
            updateCartBadge(data.cart_count);
        } else {
            showToast(data.message || 'Không thể thêm vào giỏ hàng!', 'error');
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        showToast('Đã xảy ra lỗi, vui lòng thử lại!', 'error');
    });
}

// ============================================================
// 3. CẬP NHẬT SỐ LƯỢNG TRONG GIỎ (AJAX)
// ============================================================
function updateCartItem(productId, newQty) {
    if (newQty <= 0) {
        removeFromCart(productId);
        return;
    }

    fetch('index.php?controller=cart&action=ajaxUpdate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id=${productId}&qty=${newQty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật UI
            updateCartBadge(data.cart_count);
            
            // Cập nhật subtotal của dòng sản phẩm
            const subtotalEl = document.getElementById(`subtotal-${productId}`);
            if (subtotalEl) {
                subtotalEl.textContent = data.item_subtotal + 'đ';
            }
            
            // Cập nhật tổng tiền
            const totalEl = document.getElementById('cart-total-price');
            if (totalEl) {
                totalEl.textContent = data.total_price + 'đ';
            }
            
            // Cập nhật tạm tính
            const subtotalTotalEl = document.getElementById('cart-subtotal-price');
            if (subtotalTotalEl) {
                subtotalTotalEl.textContent = data.total_price + 'đ';
            }

            showToast('Đã cập nhật số lượng!', 'info');
        } else {
            showToast(data.message || 'Lỗi cập nhật!', 'error');
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        showToast('Đã xảy ra lỗi, vui lòng thử lại!', 'error');
    });
}

// ============================================================
// 4. XÓA SẢN PHẨM KHỎI GIỎ (AJAX)
// ============================================================
function removeFromCart(productId) {
    fetch(`index.php?controller=cart&action=ajaxRemove&id=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate xóa dòng sản phẩm
            const row = document.getElementById(`cart-row-${productId}`);
            if (row) {
                row.classList.add('removing');
                setTimeout(() => {
                    row.remove();
                    
                    // Kiểm tra nếu giỏ hàng trống
                    const tbody = document.querySelector('.cart-table tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload(); // Reload để hiện trạng thái trống
                    }
                }, 400);
            }
            
            // Cập nhật badge + tổng tiền
            updateCartBadge(data.cart_count);
            
            const totalEl = document.getElementById('cart-total-price');
            if (totalEl) totalEl.textContent = data.total_price + 'đ';
            
            const subtotalTotalEl = document.getElementById('cart-subtotal-price');
            if (subtotalTotalEl) subtotalTotalEl.textContent = data.total_price + 'đ';

            showToast('Đã xóa sản phẩm khỏi giỏ hàng!', 'warning');
        } else {
            showToast(data.message || 'Lỗi xóa sản phẩm!', 'error');
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        showToast('Đã xảy ra lỗi, vui lòng thử lại!', 'error');
    });
}

// ============================================================
// 5. CẬP NHẬT BADGE GIỎ HÀNG TRÊN NAVBAR
// ============================================================
function updateCartBadge(count) {
    const badges = document.querySelectorAll('.cart-badge');
    
    if (count > 0) {
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = 'inline-block';
            // Pulse animation
            badge.style.animation = 'none';
            badge.offsetHeight; // Trigger reflow
            badge.style.animation = 'badgePulse 0.3s ease';
        });
        
        // Nếu chưa có badge, tạo mới
        if (badges.length === 0) {
            const cartLink = document.querySelector('a[title="Giỏ hàng"]');
            if (cartLink) {
                const newBadge = document.createElement('span');
                newBadge.className = 'cart-badge';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    } else {
        badges.forEach(badge => badge.remove());
    }
}

// ============================================================
// 6. SỰ KIỆN KHI TRANG CART LOAD
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // Lắng nghe thay đổi số lượng trên trang giỏ hàng
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        let debounceTimer;
        input.addEventListener('change', function() {
            clearTimeout(debounceTimer);
            const productId = this.getAttribute('data-product-id');
            const newQty = parseInt(this.value) || 0;
            debounceTimer = setTimeout(() => {
                updateCartItem(productId, newQty);
            }, 300);
        });
    });

    // Lắng nghe nút xóa AJAX trên trang giỏ hàng
    document.querySelectorAll('.btn-ajax-remove').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            removeFromCart(productId);
        });
    });
});
