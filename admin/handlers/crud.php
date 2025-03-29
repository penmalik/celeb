<?php

session_start();
require_once "../db/db.php";
require_once "../utils/functions.php";
global $connect;


// INSERT INTO DB
if($_GET['action'] == 'insert'){
    
    // collect form data
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $car = sanitizeInput($_POST['car']);
    $state = sanitizeInput($_POST['state']);
    $address = sanitizeInput($_POST['address']);
    $from = sanitizeInput($_POST['from']);
    $until = sanitizeInput($_POST['until']);
    $price = sanitizeInput($_POST['price']);

    
    // validate the email format 
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode([
            "statusCode" => 500,
            "message" => "Invalid email address."
        ]);
        exit();
    }
    
    if (empty($name) || empty($email) || empty($car) || empty($state) || empty($address) || empty($from) || empty($until) || empty($price)) {
        echo json_encode([
            "statusCode" => 500,
            "message" => "All fields are required."
        ]);
        exit();
    }
    
    // check if car and model already uploaded to database 
    // $check_car = get_car_name($car);
    // if($check_car){
    //     echo json_encode([
    //         "statusCode" => 500,
    //         "message" => "Car already uploaded"
    //     ]);
    //     exit();
    // } 

    // collect file(image) data
    $image = $_FILES['image'];
    if ($image['size'] !== 0) {

        $upload_result = upload_image($image);

        if($upload_result){
            $db_image_name = $upload_result;
            // give each booking a unique ID
            $booking_id = uniqid("book_");
        
            // insert data into database
            $sql = $connect->prepare("INSERT INTO `car_bookings`(`booking_id`, `name`, `email`, `car_name`, `car_image`, `state`, `address`, `price`, `book_from`, `book_until`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param('ssssssssss',$booking_id,$name,$email,$car,$db_image_name,$state,$address,$price,$from,$until);
        
            if($sql->execute()){
                echo json_encode([
                    "statusCode"=>200,
                    "message"=>"Successfully uploaded booking"
                ]);
                exit();
            } else {
                echo json_encode([
                    "statusCode"=>500,
                    "message"=>"Failed to upload data, try again"
                ]);
                exit();
            }
        } else{
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Image error, try again"
            ]);
            exit();
        }
    }
    else{
        echo json_encode([
            "statusCode"=>500,
            "message"=>"Image error, try again"
        ]);
        exit();
    }

}

// GET ALL BOOKINGS FROM DB
if($_GET['action'] == 'fetchAll'){
    $sql = "SELECT * FROM `car_bookings`";
    $res = makeQuery($sql);
    $data = [];

    while($row=mysqli_fetch_assoc($res)){
        $data[] = $row;
    }

    // close sql connection
    mysqli_close($connect);
    header("content-type: application/json");
    echo json_encode([
        "data"=>$data
    ]);
}

// GET A SINGLE BOOKING FROM DB
if($_GET['action'] == 'fetchSingle'){
    $id = sanitizeInput($_POST['id']);
    $sql = get_car_id($id);

    if(mysqli_num_rows($sql) > 0){
        $data = mysqli_fetch_assoc($sql);
        header("Content-Type: application/json");
        echo json_encode([
            "statusCode" => 200,
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "statusCode"=>404,
            "message"=>"Bookin data not found"
        ]);
    }
}

// EDIT SINGLE BOOKING
if($_GET['action'] == 'edit'){
    // $response = [
    //     'post' => $_POST
    // ];
    // echo json_encode($response);
    // exit();

    // collect form data
    $name = sanitizeInput($_POST['name']);
    $id = sanitizeInput($_POST['id']);
    $email = sanitizeInput($_POST['email']);
    $car = sanitizeInput($_POST['car']);
    $state = sanitizeInput($_POST['state']);
    $address = sanitizeInput($_POST['address']);
    $price = sanitizeInput($_POST['price']);
    $from = sanitizeInput($_POST['from']);
    $until = sanitizeInput($_POST['until']);
    $image = $_FILES['image'];

    if ($image['size'] !== 0) {
        $upload_result = upload_image($image);
        if ($upload_result) $db_image_name = $upload_result;
        $old_image = $_POST["image_old"];
        $old_image_path = "../images/" . $old_image;

        if (file_exists($old_image_path) && !is_dir($old_image_path)) {
            unlink($old_image_path);
        } else {
            echo json_encode([
                "statusCode" => 400,
                "message" => "Old file not found"
            ]);
            exit();
        }
    } else {
        $db_image_name = sanitizeInput($_POST["image_old"]);
    }

    // insert into database
    $sql = $connect->prepare("UPDATE `car_bookings` SET `name` = ?, `email` = ?, `car_name` = ?, `car_image` = ?, `state` = ?, `address` = ?, `price` = ?, `book_from` = ?, `book_until` = ? WHERE `id` = ?");
    $sql->bind_param('ssssssssss',$name,$email,$car,$db_image_name,$state,$address,$price,$from,$until,$id);
    if($sql->execute()){
        echo json_encode([
            "statusCode"=>200,
            "message"=>"Successfully updated booking"
        ]);
        exit();
    } else {
        echo json_encode([
            "statusCode"=>500,
            "message"=>"Failed to update data, try again"
        ]);
        exit();
    }


}

// DELETE SINGLE BOOKING
if($_GET['action'] == 'delete'){
    // $response = json_encode([
    //     "post"=>$_POST
    // ]);

    // echo($response);
    // exit();

    // collect form details
    $id = sanitizeInput($_POST['id']);
    $image = sanitizeInput($_POST['delete_image']);

    // delete from db
    $sql = $connect->prepare("DELETE FROM `car_bookings` WHERE id = ?");
    $sql->bind_param("s", $id);
    if($sql->execute()){
        $image_path = "../images/" . $image;
        if (file_exists($image_path) && !is_dir($image_path)) {
            unlink($image_path);
        }
        echo json_encode([
            "statusCode"=>200,
            "message"=>"Successfully deleted booking"
        ]);
    } else {
        echo json_encode([
            "statusCode"=>500,
            "message"=>"An error occured, try again"
        ]);
    }
    
}

// if ($_GET['action'] == 'delete') {
//     // Collect form details
//     $id = sanitizeInput($_POST['id']);
//     $image = sanitizeInput($_POST['delete_image']);
    
//     // Start MySQL transaction
//     $connect->begin_transaction();
    
//     try {
//         // Delete from DB
//         $sql = $connect->prepare("DELETE FROM `car_bookings` WHERE id = ?");
//         $sql->bind_param("s", $id);

//         if ($sql->execute()) {
//             // Check if the image exists before deleting it
//             $image_path = "../images/" . $image;
//             if (file_exists($image_path) && !is_dir($image_path)) {
//                 // Image exists, so delete it
//                 unlink($image_path);
//             } else {
//                 // If the image doesn't exist, we roll back the transaction
//                 throw new Exception('Image file not found');
//             }

//             // Commit the transaction since everything is successful
//             $connect->commit();

//             echo json_encode([
//                 "statusCode" => 200,
//                 "message" => "Successfully deleted booking"
//             ]);
//         } else {
//             // If database deletion fails, roll back the transaction
//             throw new Exception('Failed to delete booking from database');
//         }
//     } catch (Exception $e) {
//         // If any error occurs, roll back the transaction
//         $connect->rollback();
        
//         echo json_encode([
//             "statusCode" => 500,
//             "message" => $e->getMessage()
//         ]);
//     }
// }









