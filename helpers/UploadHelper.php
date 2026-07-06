<?php
/**
 * UploadHelper.php - Xử lý Upload Ảnh chuyên nghiệp
 * ===================================================
 * Tách biệt hoàn toàn logic upload ra khỏi Controller.
 * Được gọi từ bất kỳ Controller nào: AdminController, ProductController...
 * 
 * Quy trình xử lý:
 * 1. Nhận file từ form ($_FILES) hoặc chuỗi Base64 (từ Cropper.js)
 * 2. Validate định dạng (JPG, PNG, WEBP - chặn GIF, BMP, SVG...)
 * 3. Validate dung lượng (tối đa 5MB)
 * 4. Kiểm tra MIME type thực sự (chống giả mạo đuôi file)
 * 5. Đổi tên file an toàn (md5 + uniqid, chống trùng lặp & XSS)
 * 6. Di chuyển vào thư mục phân loại: /assets/uploads/{folder}/
 * 7. Trả về đường dẫn tương đối để lưu vào Database
 * 
 * Cách sử dụng:
 *   require_once 'helpers/UploadHelper.php';
 *   $result = UploadHelper::uploadFile($_FILES['image'], 'products');
 *   if ($result['success']) { $imagePath = $result['path']; }
 *   else { $errorMsg = $result['error']; }
 */

class UploadHelper {

    // ===== CẤU HÌNH TRUNG TÂM =====
    
    /** Thư mục gốc chứa tất cả ảnh upload */
    const UPLOAD_BASE_DIR = 'assets/uploads/';
    
    /** Dung lượng tối đa cho phép (5MB) */
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    
    /** Các đuôi file cho phép (Chỉ ảnh tĩnh, chặn GIF động để tránh lỗi hiển thị) */
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    
    /** Các MIME type hợp lệ (Kiểm tra nội dung thực sự của file, chống giả mạo) */
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    
    /** Các thư mục con hợp lệ (Whitelist - chặn path traversal) */
    const VALID_FOLDERS = ['products', 'brands', 'banners', 'users', 'others'];


    // ================================================================
    // PHƯƠNG THỨC 1: Upload file từ form HTML (<input type="file">)
    // ================================================================
    /**
     * Upload file ảnh từ $_FILES
     * 
     * @param array  $file   Mảng $_FILES['tên_input'] từ form
     * @param string $folder Thư mục đích: 'products', 'brands', 'banners'
     * @return array ['success' => bool, 'path' => string] hoặc ['success' => false, 'error' => string]
     */
    public static function uploadFile($file, $folder = 'others') {
        // --- Bước 0: Kiểm tra file có tồn tại không ---
        if (!isset($file) || !is_array($file) || $file['error'] !== UPLOAD_ERR_OK) {
            // Phân loại lỗi upload PHP
            $errorMsg = self::getUploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE);
            return ['success' => false, 'error' => $errorMsg];
        }

        // --- Bước 1: Validate dung lượng ---
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $maxMB = self::MAX_FILE_SIZE / 1024 / 1024;
            return ['success' => false, 'error' => "Dung lượng ảnh quá lớn. Tối đa cho phép là {$maxMB}MB."];
        }

        // --- Bước 2: Validate đuôi file (Extension) ---
        $originalName = $file['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            $allowed = strtoupper(implode(', ', self::ALLOWED_EXTENSIONS));
            return ['success' => false, 'error' => "Chỉ chấp nhận định dạng: {$allowed}."];
        }

        // --- Bước 3: Validate MIME type thực sự (Chống giả mạo) ---
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            return ['success' => false, 'error' => 'File không phải ảnh hợp lệ! Phát hiện nội dung giả mạo.'];
        }

        // --- Bước 4: Tạo tên file an toàn ---
        $safeFileName = self::generateSafeFileName($extension);

        // --- Bước 5: Tạo thư mục đích (nếu chưa có) ---
        $folder = self::sanitizeFolder($folder);
        $targetDir = self::UPLOAD_BASE_DIR . $folder . '/';
        if (!self::ensureDirectory($targetDir)) {
            return ['success' => false, 'error' => 'Lỗi hệ thống: Không thể tạo thư mục lưu trữ.'];
        }

        // --- Bước 6: Di chuyển file vào thư mục đích ---
        $targetPath = $targetDir . $safeFileName;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => $targetPath];
        } else {
            return ['success' => false, 'error' => 'Lỗi đường truyền: Không thể lưu file lên server.'];
        }
    }


    // ================================================================
    // PHƯƠNG THỨC 2: Upload ảnh từ chuỗi Base64 (Cropper.js)
    // ================================================================
    /**
     * Lưu ảnh đã cắt (cropped) từ dữ liệu Base64
     * Dùng cho tính năng Crop ảnh Banner bằng Cropper.js
     * 
     * @param string $base64String Chuỗi "data:image/jpeg;base64,/9j/4AAQ..."
     * @param string $folder       Thư mục đích: 'banners', 'products'...
     * @return array ['success' => bool, 'path' => string] hoặc ['success' => false, 'error' => string]
     */
    public static function uploadBase64($base64String, $folder = 'others') {
        if (empty($base64String)) {
            return ['success' => false, 'error' => 'Dữ liệu ảnh trống.'];
        }

        // Tách header và data
        $parts = explode(',', $base64String);
        if (count($parts) !== 2) {
            return ['success' => false, 'error' => 'Dữ liệu ảnh Base64 không đúng định dạng.'];
        }

        // Giải mã Base64
        $imgData = base64_decode($parts[1]);
        if ($imgData === false) {
            return ['success' => false, 'error' => 'Giải mã ảnh thất bại.'];
        }

        // Kiểm tra dung lượng sau giải mã
        if (strlen($imgData) > self::MAX_FILE_SIZE) {
            return ['success' => false, 'error' => 'Ảnh đã cắt quá lớn (> 5MB).'];
        }

        // Xác định MIME type thực sự từ nội dung binary
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imgData);
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            return ['success' => false, 'error' => 'Nội dung ảnh không hợp lệ.'];
        }

        // Xác định extension từ MIME
        $extension = self::mimeToExtension($mimeType);

        // Tạo tên file & thư mục
        $safeFileName = self::generateSafeFileName($extension, '_cropped');
        $folder = self::sanitizeFolder($folder);
        $targetDir = self::UPLOAD_BASE_DIR . $folder . '/';
        if (!self::ensureDirectory($targetDir)) {
            return ['success' => false, 'error' => 'Lỗi hệ thống: Không thể tạo thư mục.'];
        }

        // Ghi file
        $targetPath = $targetDir . $safeFileName;
        if (file_put_contents($targetPath, $imgData)) {
            return ['success' => true, 'path' => $targetPath];
        } else {
            return ['success' => false, 'error' => 'Không thể lưu ảnh đã cắt lên server.'];
        }
    }


    // ================================================================
    // PHƯƠNG THỨC 3: Xóa file ảnh vật lý (dùng khi Delete/Update SP)
    // ================================================================
    /**
     * Xóa file ảnh khỏi server
     * Bảo vệ: Chỉ cho phép xóa file trong thư mục uploads/
     * 
     * @param string $filePath Đường dẫn ảnh lưu trong DB
     * @return bool True nếu xóa thành công hoặc file không tồn tại
     */
    public static function deleteFile($filePath) {
        // Không xóa nếu là URL bên ngoài (placeholder, CDN...)
        if (empty($filePath) || strpos($filePath, 'http') === 0) {
            return true;
        }

        // Bảo vệ: Chỉ cho xóa file trong thư mục assets/uploads/ hoặc assets/images/
        if (strpos($filePath, 'assets/uploads/') !== 0 && strpos($filePath, 'assets/images/') !== 0) {
            return false; // Từ chối xóa file ngoài vùng an toàn
        }

        // Chặn path traversal (../)
        if (strpos($filePath, '..') !== false) {
            return false;
        }

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return true; // File không tồn tại = coi như đã xóa
    }


    // ================================================================
    // CÁC HÀM TIỆN ÍCH NỘI BỘ (Private)
    // ================================================================

    /**
     * Tạo tên file an toàn, không trùng lặp
     * Format: {hash}_{timestamp}{suffix}.{ext}
     */
    private static function generateSafeFileName($extension, $suffix = '') {
        $hash = md5(uniqid(mt_rand(), true));
        $timestamp = date('Ymd_His');
        return $hash . '_' . $timestamp . $suffix . '.' . $extension;
    }

    /**
     * Đảm bảo tên folder nằm trong whitelist (chống path traversal)
     */
    private static function sanitizeFolder($folder) {
        $folder = strtolower(trim($folder));
        // Chặn ký tự đặc biệt
        $folder = preg_replace('/[^a-z0-9_]/', '', $folder);
        return in_array($folder, self::VALID_FOLDERS) ? $folder : 'others';
    }

    /**
     * Tạo thư mục nếu chưa tồn tại (recursive)
     */
    private static function ensureDirectory($dir) {
        if (!file_exists($dir)) {
            return mkdir($dir, 0755, true);
        }
        return is_dir($dir) && is_writable($dir);
    }

    /**
     * Chuyển MIME type sang extension
     */
    private static function mimeToExtension($mimeType) {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        return $map[$mimeType] ?? 'jpg';
    }

    /**
     * Phân loại mã lỗi upload PHP sang thông báo tiếng Việt
     */
    private static function getUploadErrorMessage($errorCode) {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'File vượt quá giới hạn cho phép của server.',
            UPLOAD_ERR_FORM_SIZE  => 'File vượt quá giới hạn cho phép của form.',
            UPLOAD_ERR_PARTIAL    => 'File chỉ được tải lên một phần. Vui lòng thử lại.',
            UPLOAD_ERR_NO_FILE    => 'Không có file nào được chọn.',
            UPLOAD_ERR_NO_TMP_DIR => 'Lỗi server: Thiếu thư mục tạm.',
            UPLOAD_ERR_CANT_WRITE => 'Lỗi server: Không thể ghi file.',
            UPLOAD_ERR_EXTENSION  => 'File bị chặn bởi extension PHP trên server.',
        ];
        return $messages[$errorCode] ?? 'Lỗi upload không xác định.';
    }
}
