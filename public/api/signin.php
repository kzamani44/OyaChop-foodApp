<?php
require "cors.php";
require 'db_con.php';

$data = json_decode(file_get_contents('php://input'));


    if(!empty($data->email) && !empty($data->password)){

        $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    
        $password = filter_var($data->password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $sql = "SELECT * FROM users WHERE email = '$email'";


        $result = $db_con->query($sql);


    if($result->num_rows > 0){

            $row = $result->fetch_array();

            $user_id = $row['id'];

            $user_email = $row['email'];

            $user_password = $row['password'];

    if ($email === $user_email && password_verify($password, $user_password)) {
        // Password verification successful
        setcookie('id', $user_id, time() + 86400 * 2);
    
        $response = json_encode(['status' => 'success', 'code' => '200', 'message' => 'Signed in.']);
        echo $response;
    } else {
        // Invalid login details
        $response = json_encode(['status' => 'error', 'code' => '420', 'message' => 'Invalid details.']);
        echo $response;
    }
    

    

    }else{

        $response = json_encode(['status'=>'error','code'=>'400','message'=>'Please Complete All Fields.']);
        echo $response;

    }}
   
$db_con->close();

?>