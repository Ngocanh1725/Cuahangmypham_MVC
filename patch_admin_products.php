<?php
$file = 'controllers/AdminController.php';
$content = file_get_contents($file);

// Replace remaining getAllBrands calls
$replacements = [
    "\$brandsList = \$this->adminModel->getAllBrands();" => "require_once 'models/BrandModel.php';\n        \$brandModel = new BrandModel(\$this->adminModel->getConn());\n        \$brandsList = \$brandModel->getAllBrands();"
];

$content = str_replace(array_keys($replacements), array_values($replacements), $content);

file_put_contents($file, $content);
echo "AdminController patched to fix products dropdown.\n";
?>
