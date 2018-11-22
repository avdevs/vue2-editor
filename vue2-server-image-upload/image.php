<?php
header("Access-Control-Allow-Origin: *");

if (isset($_FILES['image'])) {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

    $expensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if (empty($errors) == true) {
        if (file_exists(dirname(__FILE__) . "/image/" . $file_name)) {
            unlink(dirname(__FILE__) . "/image/" . $file_name);
        }

        if (move_uploaded_file($file_tmp, dirname(__FILE__) . "/image/" . $file_name)) {
            if (resize(1920, dirname(__FILE__) . "/image/" . $file_name, dirname(__FILE__) . "/image/" . $file_name)) {
                echo json_encode('http://' . $_SERVER['HTTP_HOST'] . '/vue2-editor/vue2-server-image-upload/image/' . $file_name);
            } else {
                echo json_encode('Not uploaded');
            }
        } else {
            echo json_encode('Not uploaded');
        }
    } else {
        echo json_encode($errors[0]);
    }
    exit();
}

function resize($newWidth, $targetFile, $originalFile)
{
    $info = getimagesize($originalFile);
    list($width, $height) = $info;

    if ($width > 1920) {
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
                throw new Exception('Unknown image type.');
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
    }
    return true;
}