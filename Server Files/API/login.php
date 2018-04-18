<?php
    $con = mysqli_connect("mysql3792.cp.blacknight.com", "u1480761_Eamonn", "Fabregas@2019", "db1480761_plantvision");

    $username = $_POST["username"];
    $password = $_POST["password"];
	
	$file = 'log.txt';
	file_put_contents($file, $username);

    $statement = mysqli_prepare($con, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");
    mysqli_stmt_bind_param($statement, "ss", $username, $password);
    mysqli_stmt_execute($statement);
    
    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $userID, $email, $name, $username, $password);
    
    $response = array();
    $response["success"] = false;  
    
    while(mysqli_stmt_fetch($statement)){
        $response["success"] = true;  
		$response["email"]= $email;
        $response["name"] = $name;
        $response["username"] = $username;
        $response["password"] = $password;
    }
    
    echo json_encode($response);







?>
