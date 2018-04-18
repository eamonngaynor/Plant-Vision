<?php

$servername = "mysql3792.cp.blacknight.com";
$username = "u1480761_Eamonn";
$password = "Fabregas@2019";
$database = "db1480761_plantvision";
 
 
//creating a new connection object using mysqli 
$conn = new mysqli($servername, $username, $password, $database);
 
//if there is some error connecting to the database
//with die we will stop the further execution by displaying a message causing the error 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


	  
$username = file_get_contents("log.txt");

//if everything is fine
//creating an array for storing the data 
$heroes = array(); 
//echo $username;
$searchQ = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
$rowUser = mysqli_fetch_array($searchQ);	
$idUser = $rowUser[0];
//echo "<br>" .$idUser;


//this is our sql query 
//$sql = "SELECT user_id, name FROM user;";
$sql = "SELECT  id, name FROM db_images WHERE user_id = '$idUser';";



//creating an statment with the query
$stmt = $conn->prepare($sql);
 
//executing that statment
$stmt->execute();
 
//binding results for that statment 
$stmt->bind_result($id, $name);
 
//looping through all the records
while($stmt->fetch()){
 
 //pushing fetched data in an array 
 $temp = [
 'id'=>$id,
 'name'=>$name
 ];

 //pushing the array inside the hero array 
 array_push($heroes, $temp);
}
  
//displaying the data in json format 
echo json_encode($heroes);

?>