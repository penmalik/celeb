<?php

define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'celeb');

$connect = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

// CHECK IF CONNECT SUCCESS
if($connect->connect_error) die('SERVER CONNECTION FAILD: ' . $connect->connect_error);