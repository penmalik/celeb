<?php

session_start();
require_once "../db/db.php";
require_once "../utils/functions.php";
global $connect;


// registration logic
if($_GET['action'] == 'regAdmin'){

    if(!empty($_POST['email']) && !empty($_POST['password'])){

        // collect from data
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);
    
        // create admin unique id
        $id = uniqid("adm_");
    
        // confirm email not exist in database
        $check_email = get_admin_email($email);
    
        if($check_email){
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Email registered to another admin"
            ]);
            exit;
        }
    
        // hash password
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
    
        // INSERT INTO DATABASE
        $sql = $connect->prepare("INSERT INTO `admin`(`admin_id`, `email`, `password`) VALUES(?,?,?)");
        $sql->bind_param("sss", $id,$email,$hash_password);

        if($sql->execute()){
            echo json_encode([
                "statusCode"=>200,
                "message"=>"Registration Success"
            ]);
            exit;
        } else {
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Registration Failed"
            ]);
            exit;
        }
    }
    else {
        echo json_encode([
            "statusCode"=>"400",
            "message"=>"Please fill all required fields"
        ]);
        exit;
    }
    
}

// login logic
if($_GET['action'] == 'loginAdmin'){
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        // collect form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check if email exist in database
        $check_email = get_admin_email($email);
        if(!$check_email){
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Email not registered to an admin"
            ]);
            exit;
        }

        // check if password match with password in database
        if(!password_verify($password, $check_email['password'])){
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Incorrect Password"
            ]);
            exit;
        }

        // create session for admin
        $_SESSION['ADMIN'] = $check_email['admin_id'];
        if($_SESSION['ADMIN']){
            echo json_encode([
                "statusCode"=>200,
                "message"=>"Login Success"
            ]);
            exit;
        } else {
            echo json_encode([
                "statusCode"=>500,
                "message"=>"Login failed. Try again"
            ]);
            exit;
        }
        

    } else {
        echo json_encode([
            "statusCode"=>"400",
            "message"=>"Please fill all required fields"
        ]);
        exit;
    }
}

// logout logic
if($_GET['action'] == 'logout'){
    session_unset();
    session_destroy();
    exit;
}