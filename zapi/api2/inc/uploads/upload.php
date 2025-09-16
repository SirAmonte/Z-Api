<?php
// <!-- profielfotos veranderen -->
// <!-- https://phppot.com/php/php-upload-image-to-database/ -->
//<!-- https://stackoverflow.com/questions/70472403/how-to-store-blob-object-to-mysql-database -->
// https://www.taniarascia.com/how-to-upload-files-to-a-server-with-plain-javascript-and-php/
function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 85) {
    $isValid = @getimagesize($sourceImage);
    if (!$isValid) {
        return false;
    }

    list($origWidth, $origHeight, $type) = getimagesize($sourceImage);
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;
    $ratio = min($widthRatio, $heightRatio);
    $newWidth = intval($origWidth * $ratio);
    $newHeight = intval($origHeight * $ratio);
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($sourceImage);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($sourceImage);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($sourceImage);
            break;
        default:
            return false;
    }

    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    switch ($type) {
        case IMAGETYPE_JPEG:
            return imagejpeg($newImage, $targetImage, 75);
        case IMAGETYPE_PNG:
            return imagepng($newImage, $targetImage, 6);
        case IMAGETYPE_GIF:
            return imagegif($newImage, $targetImage);
        default:
            return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image']) && isset($_POST['account_id'])) {
    $file = $_FILES['profile_image'];
    $account_id = intval($_POST['account_id']);

    $allowed_types = ['image/jpeg', 'image/svg+xml', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, SVG, and GIF are allowed.']);
        exit;
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = $upload_dir . $account_id . '-' . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $file_name)) {
        chmod($file_name, 0644);
        echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully.', 'file_path' => $file_name]);
    } else {
      file_put_contents('upload_debug.log', "Failed to move uploaded file\n", FILE_APPEND);

      echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
    }
} else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['post_image']) && isset($_POST['account_id']) && isset($_POST['post_id'])){
   $file = $_FILES['post_image'];
   $account_id = intval($_POST['account_id']);
   $post_id = intval($_POST['post_id']);

    $allowed_types = ['image/jpeg', 'image/svg+xml', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, SVG, and GIF are allowed.']);
        exit;
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = $upload_dir . $account_id . '-' . $post_id . '-' . basename($file['name']);
    if (resizeImage($file['tmp_name'], $file_name, 1080, 1080)) {
        chmod($file_name, 0644);
        echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully.', 'file_path' => $file_name]);
    } else {
      file_put_contents('upload_debug.log', "Failed to move uploaded file\n", FILE_APPEND);

      echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
}
?>

