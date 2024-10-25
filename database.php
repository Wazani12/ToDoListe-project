<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'todoliste');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if ($conn->connect_errno){
    die("Failed to connect to MySQL: ". $conn->connect_error);
    
}




?>