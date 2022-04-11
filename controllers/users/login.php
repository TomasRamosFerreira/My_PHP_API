<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Heather: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/database.php';
    include_once '../../models/users.php';

    // Instace Database
    $database = new Database();
    $db = $database->connectDB();

    $userClass = new Users($db);

    $data = json_decode(file_get_contents('php://input'));

    $userClass->email = $data->email;
    $userClass->pass = $data->password;

    echo json_encode($userClass->login());
?>