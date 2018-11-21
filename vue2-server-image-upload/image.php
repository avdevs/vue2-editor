<?php
header("Access-Control-Allow-Origin: *");
if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

    $expensions= array("jpeg","jpg","png");

    if(in_array($file_ext,$expensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }

    if(empty($errors)==true) {
        if(file_exists(dirname(__FILE__)."/image/".$file_name)) {
            unlink(dirname(__FILE__)."/image/".$file_name);
        }

        if(move_uploaded_file($file_tmp,dirname(__FILE__)."/image/".$file_name)) {
            echo json_encode('http://'.$_SERVER['HTTP_HOST'].'/vue2-server-image-upload/image/'.$file_name);
        } else {
            echo json_encode('Not uploaded');
        }
    }else{
        echo json_encode($errors[0]);
    }
    exit();
}
