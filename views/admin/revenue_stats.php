<?php 
$pageTitle = "Thống kê Doanh Thu - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <a href="index.php?controller=admin&action=index" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard</a>
                    <h3 class="fw-bold text-dark">Bảng Thống Kê Doanh Thu</h3>
                    <p class="text-muted">Danh sách các đơn hàng đã hoàn thành và đóng góp vào tổng doanh thu</p>
                </div>
            </div>

            <!-- Box Thống kê luồng tiền (MỚI THÊM) -->
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3"><i class="fas fa-wallet fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Tổng doanh thu</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($totalRevenue); ?>đ</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100 border-start border-success border-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-3"><i class="fas fa-money-bill-wave fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Tiền mặt (COD)</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-success"><?php echo isset($revenueBreakdown['cod']) ? number_format($revenueBreakdown['cod']) : 0; ?>đ</h3>
                        <p class="small text-muted mb-0 mt-1">Đã thu qua Cửa hàng / Shipper</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100 border-start border-info border-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 text-info p-2 rounded-3 me-3"><i class="fas fa-qrcode fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Chuyển khoản (QR)</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-info"><?php echo isset($revenueBreakdown['qr']) ? number_format($revenueBreakdown['qr']) : 0; ?>đ</h3>
                        <p class="small text-muted mb-0 mt-1">Tiền đã vào tài khoản ngân hàng</p>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ doanh thu -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Biểu đồ doanh thu</h5>
                        <select id="chartViewType" class="form-select form-select-sm w-auto shadow-sm border-0 bg-light fw-bold" onchange="updateChart(this.value)">
                            <option value="day" selected>Theo ngày</option>
                            <option value="month">Theo tháng</option>
                            <option value="year">Theo năm</option>
                        </select>
                    </div>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
            
            <!-- Bảng dữ liệu -->
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Mã Đơn</th>
                                <th class="py-3">Khách hàng</th>
                                <th class="py-3">Số điện thoại</th>
                                <th class="py-3">Ngày hoàn thành</th>
                                <th class="py-3">Phương thức TT</th>
                                <th class="text-end pe-4 py-3">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($revenues)): ?>
                                <?php foreach($revenues as $row): ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">#ORD-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['customer_phone']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['payment_method']); ?></span></td>
                                        <td class="text-end pe-4 fw-bold text-success">+<?php echo number_format($row['total_price']); ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-receipt fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5 class="text-muted">Chưa có đơn hàng nào được hoàn thành.</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rawData = <?php echo json_encode($revenues); ?>;
    let revenueChartInstance = null;

    function processData(type) {
        const revenueGrouped = {};
        
        rawData.forEach(order => {
            const dateObj = new Date(order.order_date);
            let key = '';
            
            if (type === 'day') {
                key = order.order_date.split(' ')[0]; // YYYY-MM-DD
            } else if (type === 'month') {
                const m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                const y = dateObj.getFullYear();
                key = `${y}-${m}`; // YYYY-MM
            } else if (type === 'year') {
                key = dateObj.getFullYear().toString(); // YYYY
            }

            const amount = parseFloat(order.total_price);
            if (!revenueGrouped[key]) {
                revenueGrouped[key] = 0;
            }
            revenueGrouped[key] += amount;
        });

        const sortedKeys = Object.keys(revenueGrouped).sort();
        
        // Format labels
        const formattedLabels = sortedKeys.map(k => {
            if (type === 'day') return k.split('-').reverse().join('/');
            if (type === 'month') return `Tháng ${k.split('-')[1]}/${k.split('-')[0]}`;
            if (type === 'year') return `Năm ${k}`;
            return k;
        });

        const chartData = sortedKeys.map(k => revenueGrouped[k]);
        
        return { labels: formattedLabels, data: chartData };
    }

    function updateChart(type) {
        const processed = processData(type);
        
        if (revenueChartInstance) {
            revenueChartInstance.destroy();
        }

        const ctx = document.getElementById('revenueChart').getContext('2d');
        revenueChartInstance = new Chart(ctx, {
            type: type === 'year' ? 'bar' : 'line',
            data: {
                labels: processed.labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: processed.data,
                    borderColor: '#be185d',
                    backgroundColor: type === 'year' ? 'rgba(190, 24, 93, 0.6)' : 'rgba(190, 24, 93, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#be185d',
                    pointRadius: 4,
                    borderRadius: type === 'year' ? 4 : 0
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN').format(context.raw) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    }

    // Initialize chart with 'day' view
    updateChart('day');
</script>

<?php include 'views/layout/footer.php'; ?>