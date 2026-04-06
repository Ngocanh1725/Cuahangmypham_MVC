<!-- Bootstrap JS cho dropdown và các hiệu ứng hoạt động -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Nơi chèn JS phụ trợ cho từng trang (nếu có) -->
    <?php if(isset($extraJS)) echo $extraJS; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const moreMenuBtn = document.getElementById('moreMenuBtn');
        const moreMenuDropdown = document.getElementById('moreMenuDropdown');

        // Bật/tắt class 'show' khi click vào nút 3 chấm
        moreMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
            moreMenuDropdown.classList.toggle('show');
        });

        // Ẩn menu khi click ra bất kỳ đâu ngoài menu
        document.addEventListener('click', function(e) {
            if (!moreMenuDropdown.contains(e.target) && e.target !== moreMenuBtn) {
                moreMenuDropdown.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>