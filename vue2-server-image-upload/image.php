<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode($_SERVER['REQUEST_METHOD'].' method not allowed'));
}

if (isset($_FILES['image'])) {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

    $expensions = array("jpeg", "jpg", "png", "gif");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    $image_url = '//' . $_SERVER['HTTP_HOST'] . '/vue2-editor/vue2-server-image-upload/image/';
    $image_path = dirname(__FILE__) . "/image/";
    $new_size = 1920;

    if (empty($errors) == true) {
        if (file_exists($image_path . $file_name)) {
            unlink($image_path . $file_name);
        }

        if (move_uploaded_file($file_tmp, $image_path . $file_name)) {
            $resize = resize($new_size, $image_path . $file_name, $image_path . $file_name);
            if(!$resize){
                exit(json_encode($image_url . $file_name));
            } else {
                exit(json_encode($image_url . $resize));
            }
        } else {
            exit(json_encode('issue with move upload'));
        }
    } else {
        exit(json_encode($errors[0]));
    }
}

function resize($newWidth, $targetFile, $originalFile)
{
    $info = getimagesize($originalFile);
    list($width, $height) = $info;

    if ($width > $newWidth) {
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                return false;
        }

        $img = $image_create_func($originalFile);

        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (file_exists($targetFile)) {
            unlink($targetFile);
        }

        $targetFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $targetFile);
        $image_save_func($tmp, "$targetFile.$new_image_ext");

        return basename("$targetFile.$new_image_ext");
    }

    return false;
}