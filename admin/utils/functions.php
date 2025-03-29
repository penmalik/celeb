<?php


// SANITIZE INPUTS TO AVOID SQL INJECTION
function sanitizeInput($input)
{
    global $connect;
    return mysqli_real_escape_string($connect, htmlspecialchars(trim($input)));
}

// GET ADMIN BY EMAIL
function get_admin_email($email) {
    global $connect;
    $query = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($connect, $query);
    return mysqli_fetch_assoc($result);
}

// GET CAR BY NAME
function get_car_name($car_name) {
    global $connect;
    $query = "SELECT * FROM car_bookings WHERE car_name = '$car_name'";
    $result = mysqli_query($connect, $query);
    return mysqli_fetch_assoc($result);
}


// GET CAR BY ID
function get_car_id($id) {
    global $connect;
    $query = "SELECT * FROM car_bookings WHERE id = '$id'";
    $result = mysqli_query($connect, $query);
    return $result;
}

// MAKE QUERY FUNCTION
function makeQuery($sql)
{
    global $connect;
    return mysqli_query($connect, $sql);
}

// PAGE REDIRECT FUNCTION
function redirect($url, $refresh = NULL) {
    if($refresh) return header("Refresh = $refresh, url= $url");
    return header("Location: $url");
}


// UPLOAD IMAGES FUNCTION
function upload_image($image){

    $image_original_name = $image['name'];

        // Validate image file type
        $image_info = getimagesize($image['tmp_name']);
        if ($image_info === false) {
            echo json_encode([
                "statusCode" => 500,
                "message" => "Uploaded file is not a valid image"
            ]);
            exit();
        }
        
        
        // check file size
        $max_file_size = 10 * 1024 * 1024; // 10 MB
        if($image['size'] > $max_file_size){
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Image size exceeds the 10MB limit"
            ]);
            exit();
        }


        // Set the upload directory
        $upload_dir = "../images/";

        // Check if the upload directory exists, if not, create it
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // rename the image
        $db_image_name = time() . "." . pathinfo($image_original_name, PATHINFO_EXTENSION);

        if(move_uploaded_file($image['tmp_name'], $upload_dir . $db_image_name)){
            return $db_image_name;
        } else {
            echo json_encode([
                "statusCode" => 500,
                "message" => "Image error, try again"
            ]);
            exit();
        }

}
