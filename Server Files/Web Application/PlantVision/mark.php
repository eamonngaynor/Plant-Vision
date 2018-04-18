<?php
include 'config.php';
session_start();
$id = $_GET['id'];
echo $id;

$dbname = "db1480761_plantvision";
$conn = mysqli_connect("mysql3792.cp.blacknight.com", "u1480761_Eamonn", "Fabregas@2019", $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



$result = mysqli_query($db,"SELECT * FROM imageResult WHERE id = '$id'");
$row = mysqli_fetch_array($result);
$imageI = $row[1];

$mark = "Diseased";
$result = mysqli_query($db,"SELECT * FROM imageResult WHERE imageID = '$imageI' AND diseaseM = '$mark'");
$row = mysqli_fetch_array($result);
$queryR = $row[5];
$ID2 = $row[0];
echo "<br>" .$queryR;
echo "<br>" .$ID2;
if($queryR == "Diseased"){
    $mark = "";
    $result = mysqli_query($db,"UPDATE imageResult SET diseaseM = '$mark' WHERE id = $ID2");
}
$mark = "Diseased";
$result = mysqli_query($db,"UPDATE imageResult SET diseaseM = '$mark' WHERE id = $id");


//Insert into markedtable 'mark'
$query  = mysqli_query($db,"SELECT * FROM db_images WHERE id = '$imageI'");
$groupA = mysqli_fetch_array($query);
$group = $groupA[4];
echo "<br>" .$group;
$user = $_SESSION['login_user'];
$userQuery  = mysqli_query($db,"SELECT * FROM user WHERE username = '$user'");
$userA = mysqli_fetch_array($userQuery);
$user = $userA[0];
echo "<br>" .$user;
$diseaseQuery = mysqli_query($db,"SELECT * FROM imageResult WHERE id = $id");
$diseaseA = mysqli_fetch_array($diseaseQuery);
$colourCode = $diseaseA[2];
echo "<br>" .$colourCode;

$delete = mysqli_query($db,"DELETE FROM markedtable WHERE user_id = $user AND plant_group = '$group' AND mark = 'Marked'");

$insert = mysqli_query($db,"INSERT INTO markedtable 
(user_id, plant_group, colourCode, mark) VALUES ($user, '$group', $colourCode ,'Marked')");

header('Location: editor.php');
?>