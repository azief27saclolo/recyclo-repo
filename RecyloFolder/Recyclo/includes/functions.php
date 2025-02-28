
function optimizeProductImage($file, $targetDir) {
    $image_info = getimagesize($file['tmp_name']);
    $mime_type = $image_info['mime'];
    
    switch ($mime_type) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file['tmp_name']);
            break;
        default:
            return false;
    }
    
    // Target dimensions
    $max_width = 800;
    $max_height = 800;
    
    // Get original dimensions
    $old_width = imagesx($image);
    $old_height = imagesy($image);
    
    // Calculate new dimensions
    $scale = min($max_width/$old_width, $max_height/$old_height);
    $new_width = ceil($scale * $old_width);
    $new_height = ceil($scale * $old_height);
    
    // Create new image
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Handle transparency for PNG
    if ($mime_type == 'image/png') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    }
    
    // Resize
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
    
    // Generate unique filename
    $filename = uniqid() . '.jpg';
    $filepath = $targetDir . $filename;
    
    // Save image
    imagejpeg($new_image, $filepath, 85);
    
    imagedestroy($new_image);
    imagedestroy($image);
    
    return $filename;
}
