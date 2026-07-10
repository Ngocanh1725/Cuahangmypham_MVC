<?php
class MailHelper {
    public static function sendOrderReceipt($orderId, $email, $customerName, $orderDetails, $totalPrice, $paymentMethod) {
        $subject = "Xác nhận đơn hàng #" . $orderId . " từ Glow Cosmetics";
        
        $message = "Xin chào " . $customerName . ",\n\n";
        $message .= "Cảm ơn bạn đã đặt hàng tại Glow Cosmetics. Đơn hàng của bạn đã được ghi nhận.\n";
        $message .= "Mã đơn hàng: #" . $orderId . "\n";
        $message .= "Phương thức thanh toán: " . strtoupper($paymentMethod) . "\n";
        
        if ($paymentMethod == 'bank_transfer') {
            $message .= "\nVui lòng chuyển khoản với nội dung: GLOW " . $orderId . " hoặc quét mã QR trên website để thanh toán.\n";
        }
        
        $message .= "\nChi tiết đơn hàng:\n";
        foreach ($orderDetails as $item) {
            $message .= "- " . $item['name'] . " (x" . $item['qty'] . "): " . number_format($item['price'] * $item['qty']) . "đ\n";
        }
        $message .= "\nTổng cộng: " . number_format($totalPrice) . "đ\n\n";
        $message .= "Chúng tôi sẽ thông báo cho bạn khi đơn hàng được giao.\n\n";
        $message .= "Trân trọng,\nĐội ngũ Glow Cosmetics";
        
        return self::sendMail($email, $subject, $message);
    }
    
    public static function sendOrderStatusUpdate($orderId, $email, $customerName, $status) {
        $subject = "Cập nhật trạng thái đơn hàng #" . $orderId;
        
        $message = "Xin chào " . $customerName . ",\n\n";
        $message .= "Đơn hàng #" . $orderId . " của bạn vừa được cập nhật sang trạng thái: " . mb_strtoupper($status, 'UTF-8') . ".\n\n";
        
        if ($status == 'Đang giao') {
            $message .= "Đơn hàng đang trên đường giao đến bạn. Vui lòng chú ý điện thoại!\n\n";
        } elseif ($status == 'Hoàn thành') {
            $message .= "Đơn hàng đã giao thành công. Cảm ơn bạn đã mua sắm tại Glow Cosmetics.\n\n";
        }
        
        $message .= "Trân trọng,\nĐội ngũ Glow Cosmetics";
        
        return self::sendMail($email, $subject, $message);
    }
    
    public static function sendReviewInvitation($orderId, $email, $customerName) {
        $subject = "Mời đánh giá đơn hàng #" . $orderId;
        
        $message = "Xin chào " . $customerName . ",\n\n";
        $message .= "Hy vọng bạn hài lòng với các sản phẩm từ Glow Cosmetics ở đơn hàng #" . $orderId . ".\n";
        $message .= "Hãy dành chút thời gian chia sẻ cảm nhận của bạn về sản phẩm để chúng tôi có thể phục vụ tốt hơn và nhận thêm ưu đãi nhé!\n\n";
        $message .= "Truy cập tài khoản của bạn để đánh giá ngay.\n\n";
        $message .= "Trân trọng,\nĐội ngũ Glow Cosmetics";
        
        return self::sendMail($email, $subject, $message);
    }
    
    private static function sendMail($to, $subject, $message) {
        if (empty($to)) return false;
        
        $headers = "From: no-reply@glowcosmetics.com\r\n";
        $headers .= "Reply-To: support@glowcosmetics.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Log to file for local testing because mail() might not work on local XAMPP
        $logLine = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUB: $subject \n$message\n------------------------\n";
        file_put_contents(__DIR__ . '/../mail_log.txt', $logLine, FILE_APPEND);
        
        return @mail($to, $subject, $message, $headers);
    }
}
?>
