/**
 * ============================================================
 * CHECKOUT.JS — Gọi Backend tính toán, cập nhật giao diện
 * Toàn bộ logic tính toán nằm ở CartController@ajaxCalculateTotal
 * File JS này CHỈ gửi request & hiển thị kết quả từ server.
 * ============================================================
 */

// Biến lưu mã coupon đang áp dụng
let currentCouponCode = '';

// ==================================
// 1. TOGGLE PHƯƠNG THỨC VẬN CHUYỂN
// ==================================
function toggleDelivery(method) {
    const lblShipping = document.getElementById('lblShipping');
    const lblPickup   = document.getElementById('lblPickup');
    const pickupSec   = document.getElementById('pickupSection');
    const addressFields = document.querySelectorAll(
        'input[name="customer_city"], input[name="customer_district"], input[name="customer_ward"], input[name="customer_street"]'
    );

    lblShipping.classList.remove('active');
    lblPickup.classList.remove('active');

    if (method === 'shipping') {
        lblShipping.classList.add('active');
        lblShipping.querySelector('input').checked = true;
        pickupSec.style.display = 'none';
        addressFields.forEach(f => f.required = true);
        document.getElementById('selectStore').required = false;
    } else {
        lblPickup.classList.add('active');
        lblPickup.querySelector('input').checked = true;
        pickupSec.style.display = 'block';
        addressFields.forEach(f => f.required = false);
        document.getElementById('selectStore').required = true;
    }
    // Gọi Backend tính lại phí ship
    calculateTotal();
}

// ==================================
// 2. TOGGLE FORM HÓA ĐƠN VAT
// ==================================
function toggleVatForm() {
    const cb   = document.getElementById('vatCheckbox');
    const form = document.getElementById('vatForm');
    const lbl  = document.getElementById('vatToggleLabel');

    if (cb.checked) {
        form.classList.add('show');
        lbl.classList.add('active');
    } else {
        form.classList.remove('show');
        lbl.classList.remove('active');
    }
}

// ==================================
// 3. CHỌN PHƯƠNG THỨC THANH TOÁN
// ==================================
function selectPayment(value) {
    document.querySelectorAll('.pay-option').forEach(opt => opt.classList.remove('active'));

    const map = {
        'COD': 'payOptCOD',
        'bank_transfer': 'payOptBank',
        'zalopay': 'payOptZalo',
        'momo': 'payOptMomo'
    };
    const el = document.getElementById(map[value]);
    if (el) {
        el.classList.add('active');
        el.querySelector('input').checked = true;
    }

    const bankBox = document.getElementById('bankInfoBox');
    if (value === 'bank_transfer') {
        bankBox.classList.add('show');
    } else {
        bankBox.classList.remove('show');
    }
}

// ==================================
// 4. ÁP DỤNG MÃ GIẢM GIÁ
//    (Lưu mã vào biến → gọi calculateTotal)
// ==================================
function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim().toUpperCase();
    const msg  = document.getElementById('couponMessage');

    if (!code) {
        msg.innerHTML = '<span style="color:var(--text-light)"><i class="fas fa-info-circle me-1"></i>Vui lòng nhập mã giảm giá</span>';
        return;
    }

    currentCouponCode = code;
    document.getElementById('hiddenCouponCode').value = code;

    msg.innerHTML = '<span style="color:var(--text-light)"><i class="fas fa-spinner fa-spin me-1"></i>Đang kiểm tra...</span>';

    // Gọi calculateTotal — backend sẽ validate coupon luôn
    calculateTotal();
}

// ==================================
// 5. GỌI BACKEND TÍNH TOÁN TOÀN BỘ
//    Fetch → CartController@ajaxCalculateTotal
// ==================================
let calcTimeout = null; // Debounce

function calculateTotal() {
    // Debounce 200ms để tránh gọi quá nhiều lần liên tục
    if (calcTimeout) clearTimeout(calcTimeout);
    calcTimeout = setTimeout(_doCalculate, 200);
}

function _doCalculate() {
    // Lấy delivery method hiện tại
    const deliveryEl = document.querySelector('input[name="delivery_method"]:checked');
    const deliveryMethod = deliveryEl ? deliveryEl.value : 'shipping';

    // Gửi POST lên server
    const body = new URLSearchParams({
        delivery_method: deliveryMethod,
        coupon_code: currentCouponCode
    });

    fetch('index.php?controller=cart&action=ajaxCalculateTotal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;

        // --- Cập nhật DOM ---

        // Tạm tính
        const txtSubtotal = document.getElementById('txtSubtotal');
        if (txtSubtotal) txtSubtotal.innerText = data.subtotal_fmt;

        // Phí vận chuyển
        const txtShipping = document.getElementById('txtShipping');
        if (txtShipping) {
            txtShipping.innerText = data.shipping_fmt;
            txtShipping.style.color = data.shipping_free ? '#2e7d32' : '';
        }

        // Coupon message
        const msg = document.getElementById('couponMessage');
        if (msg) {
            if (currentCouponCode) {
                if (data.coupon_valid) {
                    msg.innerHTML = '<span style="color:#2e7d32"><i class="fas fa-check-circle me-1"></i>' + data.coupon_message + '</span>';
                } else if (data.coupon_message) {
                    msg.innerHTML = '<span style="color:#c62828"><i class="fas fa-times-circle me-1"></i>' + data.coupon_message + '</span>';
                    // Reset coupon nếu không hợp lệ
                    currentCouponCode = '';
                    document.getElementById('hiddenCouponCode').value = '';
                }
            }
        }

        // Coupon discount row
        const rowCoupon = document.getElementById('rowCouponDiscount');
        if (rowCoupon) {
            if (data.coupon_discount > 0) {
                rowCoupon.style.display = 'flex';
                document.getElementById('txtCouponDiscount').innerText = data.coupon_discount_fmt;
            } else {
                rowCoupon.style.display = 'none';
            }
        }

        // Member discount row
        const rowMember = document.getElementById('rowMemberDiscount');
        if (rowMember) {
            if (data.member_discount > 0) {
                rowMember.style.display = 'flex';
                document.getElementById('txtMemberDiscount').innerText = data.member_discount_fmt;
            } else {
                rowMember.style.display = 'none';
            }
        }

        // VAT
        const rowVat = document.getElementById('rowVat');
        if (rowVat) {
            if (data.vat_enabled && data.vat_amount > 0) {
                rowVat.style.display = 'flex';
                document.getElementById('txtVat').innerText = data.vat_amount_fmt;
                const txtVatPct = document.getElementById('txtVatPercent');
                if (txtVatPct) txtVatPct.innerText = data.vat_percent;
            } else if (data.vat_enabled) {
                rowVat.style.display = 'flex';
                document.getElementById('txtVat').innerText = '0đ';
                const txtVatPct = document.getElementById('txtVatPercent');
                if (txtVatPct) txtVatPct.innerText = data.vat_percent;
            } else {
                rowVat.style.display = 'none';
            }
        }

        // Tổng cộng
        const txtFinalTotal = document.getElementById('txtFinalTotal');
        if (txtFinalTotal) txtFinalTotal.innerText = data.final_total_fmt;
    })
    .catch(err => {
        console.error('Checkout calculateTotal error:', err);
    });
}

// ==================================
// 6. KHỞI TẠO KHI TRANG LOAD XONG
// ==================================
document.addEventListener('DOMContentLoaded', function() {
    // Tính toán lần đầu từ Backend
    calculateTotal();

    // Enter trên ô mã giảm giá → áp dụng
    const couponInput = document.getElementById('couponCode');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });
    }

    // Xử lý sự kiện khi chọn cửa hàng (Kiểm tra tồn kho)
    const selectStore = document.getElementById('selectStore');
    const storeStockError = document.getElementById('storeStockError');
    const btnSubmitOrder = document.getElementById('btnSubmitOrder');

    if (selectStore) {
        selectStore.addEventListener('change', function() {
            const storeId = this.value;
            storeStockError.style.display = 'none';
            btnSubmitOrder.disabled = false;
            btnSubmitOrder.innerHTML = '<i class="fas fa-lock"></i> HOÀN TẤT ĐẶT HÀNG';

            if (!storeId) return;

            // Vô hiệu hóa nút trong khi chờ
            btnSubmitOrder.disabled = true;
            btnSubmitOrder.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG KIỂM TRA TỒN KHO...';

            const body = new URLSearchParams({ store_id: storeId });
            fetch('index.php?controller=cart&action=ajaxCheckStoreStock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btnSubmitOrder.disabled = false;
                    btnSubmitOrder.innerHTML = '<i class="fas fa-lock"></i> HOÀN TẤT ĐẶT HÀNG';
                } else {
                    let errorHtml = `<strong><i class="fas fa-exclamation-triangle"></i> Lỗi tồn kho:</strong> ${data.message}<ul class="mb-0 mt-1 pl-3">`;
                    if (data.items) {
                        data.items.forEach(item => {
                            errorHtml += `<li><b>${item.name}</b> (Kho còn: ${item.available}, Cần: ${item.requested})</li>`;
                        });
                    }
                    errorHtml += '</ul><small class="mt-2 d-block">Vui lòng chọn cơ sở khác hoặc giao tận nơi.</small>';
                    
                    storeStockError.innerHTML = errorHtml;
                    storeStockError.style.display = 'block';
                    
                    // Nút Đặt hàng tiếp tục bị vô hiệu hóa
                    btnSubmitOrder.innerHTML = '<i class="fas fa-times-circle"></i> HẾT HÀNG TẠI CƠ SỞ NÀY';
                }
            })
            .catch(err => {
                console.error('Lỗi kiểm tra tồn kho:', err);
                btnSubmitOrder.disabled = false;
                btnSubmitOrder.innerHTML = '<i class="fas fa-lock"></i> HOÀN TẤT ĐẶT HÀNG';
            });
        });
    }
});
