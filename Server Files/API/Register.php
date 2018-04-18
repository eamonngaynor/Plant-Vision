<?php

	
	$con = mysqli_connect("mysql3792.cp.blacknight.com", "u1480761_Eamonn", "Fabregas@2019", "db1480761_plantvision");
    
    $email = $_POST["email"];
	$name = $_POST["name"];
    $username = $_POST["username"];	
    $password = $_POST["password"];

    $statement = mysqli_prepare($con, "INSERT INTO user (email, name, username, password) VALUES ('$email', '$name','$username','$password')");
    mysqli_stmt_bind_param($statement, "siss", $user_id, $email, $name, $username, $password);
    mysqli_stmt_execute($statement);
    
	
    $response = array();
    $response["success"] = true;  
    echo json_encode($response);
?>
