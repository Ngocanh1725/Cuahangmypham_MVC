/**
 * ============================================================
 * CHECKOUT.JS - Logic thanh toán realtime
 * Glow Cosmetics - Cửa hàng Mỹ Phẩm MVC
 * ============================================================
 */

// Biến lưu trạng thái voucher
let currentVoucherDiscount = 0;
let currentVoucherCode = '';

// ============================================================
// 1. ÁP DỤNG VOUCHER (AJAX)
// ============================================================
function applyVoucher() {
    const input = document.getElementById('voucherInput');
    const msgEl = document.getElementById('voucherMessage');
    const btn = document.getElementById('btnApplyVoucher');
    const code = input.value.trim().toUpperCase();

    if (!code) {
        msgEl.innerHTML = '<span class="voucher-error"><i class="fas fa-exclamation-circle me-1"></i>Vui lòng nhập mã khuyến mãi!</span>';
        input.focus();
        return;
    }

    // Loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="btn-spinner"></span> Đang kiểm tra...';

    fetch('index.php?controller=cart&action=applyVoucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `voucher_code=${encodeURIComponent(code)}&subtotal=${CHECKOUT_DATA.subtotal}`
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-tag me-1"></i> Áp dụng';

        if (data.success) {
            currentVoucherDiscount = data.discount;
            currentVoucherCode = data.code;
            input.value = data.code;
            input.readOnly = true;
            input.style.backgroundColor = '#d1fae5';
            input.style.borderColor = '#059669';
            
            // Đổi nút thành "Hủy"
            btn.innerHTML = '<i class="fas fa-times me-1"></i> Hủy';
            btn.onclick = removeVoucher;
            btn.style.background = '#dc2626';

            msgEl.innerHTML = `<span class="voucher-success"><i class="fas fa-check-circle me-1"></i>${data.message} - ${data.description} (-${data.discount_formatted}đ)</span>`;

            // Hiện dòng voucher discount
            const voucherLine = document.getElementById('voucherDiscountLine');
            if (voucherLine) {
                voucherLine.style.display = 'flex';
                document.getElementById('summaryVoucherDiscount').textContent = '-' + data.discount_formatted + 'đ';
            }

            // Tính lại tổng
            recalculateTotal();

            if (typeof showToast === 'function') {
                showToast('Áp dụng mã khuyến mãi thành công! 🎉', 'success');
            }
        } else {
            currentVoucherDiscount = 0;
            currentVoucherCode = '';
            msgEl.innerHTML = `<span class="voucher-error"><i class="fas fa-times-circle me-1"></i>${data.message}</span>`;
            
            // Lắc input
            input.style.animation = 'none';
            input.offsetHeight;
            input.style.animation = 'shake 0.5s ease';
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-tag me-1"></i> Áp dụng';
        console.error('Lỗi:', error);
        msgEl.innerHTML = '<span class="voucher-error"><i class="fas fa-exclamation-circle me-1"></i>Đã xảy ra lỗi, vui lòng thử lại!</span>';
    });
}

// ============================================================
// 2. HỦY VOUCHER
// ============================================================
function removeVoucher() {
    const input = document.getElementById('voucherInput');
    const msgEl = document.getElementById('voucherMessage');
    const btn = document.getElementById('btnApplyVoucher');

    currentVoucherDiscount = 0;
    currentVoucherCode = '';
    input.value = '';
    input.readOnly = false;
    input.style.backgroundColor = '';
    input.style.borderColor = '';

    btn.innerHTML = '<i class="fas fa-tag me-1"></i> Áp dụng';
    btn.onclick = applyVoucher;
    btn.style.background = '';
    
    msgEl.innerHTML = '';

    // Ẩn dòng voucher
    const voucherLine = document.getElementById('voucherDiscountLine');
    if (voucherLine) voucherLine.style.display = 'none';

    recalculateTotal();

    if (typeof showToast === 'function') {
        showToast('Đã hủy mã khuyến mãi!', 'warning');
    }
}

// ============================================================
// 3. TÍNH LẠI TỔNG TIỀN REALTIME
// ============================================================
function recalculateTotal() {
    const subtotal = CHECKOUT_DATA.subtotal;
    const memberPercent = CHECKOUT_DATA.memberDiscountPercent;
    const vatRate = CHECKOUT_DATA.vatRate;

    // Giảm voucher
    const voucherDiscount = currentVoucherDiscount;

    // Giảm thành viên (tính trên tiền sau voucher)
    const afterVoucher = subtotal - voucherDiscount;
    const memberDiscount = Math.round(afterVoucher * memberPercent / 100);

    // Tổng giảm
    const totalDiscount = voucherDiscount + memberDiscount;

    // Tiền sau giảm
    const afterDiscount = subtotal - totalDiscount;

    // Thuế VAT
    const tax = Math.round(afterDiscount * vatRate);

    // Phí ship
    const shipping = subtotal >= CHECKOUT_DATA.freeShipThreshold ? 0 : 30000;

    // Tổng cuối
    const total = afterDiscount + tax + shipping;

    // Cập nhật UI
    const memberDiscountEl = document.getElementById('summaryMemberDiscount');
    if (memberDiscountEl) {
        memberDiscountEl.textContent = '-' + formatNumber(memberDiscount) + 'đ';
    }

    const taxEl = document.getElementById('summaryTax');
    if (taxEl) taxEl.textContent = formatNumber(tax) + 'đ';

    const shippingEl = document.getElementById('summaryShipping');
    if (shippingEl) {
        shippingEl.textContent = shipping === 0 ? 'Miễn phí' : formatNumber(shipping) + 'đ';
        shippingEl.className = shipping === 0 ? 'fw-bold text-success' : 'fw-bold';
    }

    const totalEl = document.getElementById('summaryTotal');
    if (totalEl) totalEl.textContent = formatNumber(total) + 'đ';
}

// ============================================================
// 4. CHỌN PHƯƠNG THỨC THANH TOÁN
// ============================================================
function selectPayment(element, radioId) {
    // Bỏ chọn tất cả
    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
    // Chọn option hiện tại
    element.classList.add('selected');
    document.getElementById(radioId).checked = true;
}

// ============================================================
// 5. TIỆN ÍCH
// ============================================================
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ============================================================
// 6. SUBMIT FORM - LOADING STATE
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btnPlaceOrder');
            if (btn) {
                btn.classList.add('loading');
                btn.innerHTML = '<span class="btn-spinner"></span> Đang xử lý đơn hàng...';
                btn.disabled = true;
            }
        });
    }

    // Cho phép nhấn Enter để áp dụng voucher
    const voucherInput = document.getElementById('voucherInput');
    if (voucherInput) {
        voucherInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!this.readOnly) applyVoucher();
            }
        });
    }
});

// CSS bổ sung cho animation shake
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        50% { transform: translateX(8px); }
        75% { transform: translateX(-4px); }
    }
`;
document.head.appendChild(shakeStyle);
