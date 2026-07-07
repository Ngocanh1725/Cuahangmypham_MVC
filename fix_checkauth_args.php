<?php
$file = 'controllers/AdminController.php';
$content = file_get_contents($file);

// Replace checkAuth() in specific methods:
$replacements = [
    '/public function products\(\) \{\s*\$this->checkAuth\(\);/' => 'public function products() { $this->checkAuth(\'products\');',
    '/public function addProduct\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addProduct() { $this->checkAuth(\'products\');',
    '/public function editProduct\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editProduct() { $this->checkAuth(\'products\');',
    '/public function deleteProduct\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deleteProduct() { $this->checkAuth(\'products\');',
    
    '/public function categories\(\) \{\s*\$this->checkAuth\(\);/' => 'public function categories() { $this->checkAuth(\'products\');',
    '/public function addCategory\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addCategory() { $this->checkAuth(\'products\');',
    '/public function editCategory\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editCategory() { $this->checkAuth(\'products\');',
    '/public function deleteCategory\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deleteCategory() { $this->checkAuth(\'products\');',
    
    '/public function promotions\(\) \{\s*\$this->checkAuth\(\);/' => 'public function promotions() { $this->checkAuth(\'products\');',
    '/public function editPromotion\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editPromotion() { $this->checkAuth(\'products\');',
    
    '/public function banners\(\) \{\s*\$this->checkAuth\(\);/' => 'public function banners() { $this->checkAuth(\'banners\');',
    '/public function addBanner\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addBanner() { $this->checkAuth(\'banners\');',
    '/public function editBanner\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editBanner() { $this->checkAuth(\'banners\');',
    '/public function deleteBanner\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deleteBanner() { $this->checkAuth(\'banners\');',
    
    '/public function brands\(\) \{\s*\$this->checkAuth\(\);/' => 'public function brands() { $this->checkAuth(\'brands\');',
    '/public function addBrand\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addBrand() { $this->checkAuth(\'brands\');',
    '/public function editBrand\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editBrand() { $this->checkAuth(\'brands\');',
    '/public function deleteBrand\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deleteBrand() { $this->checkAuth(\'brands\');',
    
    '/public function posts\(\) \{\s*\$this->checkAuth\(\);/' => 'public function posts() { $this->checkAuth(\'posts\');',
    '/public function addPost\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addPost() { $this->checkAuth(\'posts\');',
    '/public function editPost\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editPost() { $this->checkAuth(\'posts\');',
    '/public function deletePost\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deletePost() { $this->checkAuth(\'posts\');',
    
    '/public function orders\(\) \{\s*\$this->checkAuth\(\);/' => 'public function orders() { $this->checkAuth(\'orders\');',
    '/public function orderDetail\(\) \{\s*\$this->checkAuth\(\);/' => 'public function orderDetail() { $this->checkAuth(\'orders\');',
    '/public function updateOrderStatus\(\) \{\s*\$this->checkAuth\(\);/' => 'public function updateOrderStatus() { $this->checkAuth(\'orders\');',
    
    '/public function settings\(\) \{\s*\$this->checkAuth\(\);/' => 'public function settings() { $this->checkAuth(\'settings\');',
    
    '/public function users\(\) \{\s*\$this->checkAuth\(\);/' => 'public function users() { $this->checkAuth(\'users\');',
    '/public function addUser\(\) \{\s*\$this->checkAuth\(\);/' => 'public function addUser() { $this->checkAuth(\'users\');',
    '/public function editUser\(\) \{\s*\$this->checkAuth\(\);/' => 'public function editUser() { $this->checkAuth(\'users\');',
    '/public function deleteUser\(\) \{\s*\$this->checkAuth\(\);/' => 'public function deleteUser() { $this->checkAuth(\'users\');',
];

foreach ($replacements as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content);
}

file_put_contents($file, $content);
echo "Added module arguments to checkAuth calls.";
?>
