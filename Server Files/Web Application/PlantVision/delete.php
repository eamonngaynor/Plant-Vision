<?php
include 'config.php';
session_start();
$id = $_GET['id'];
$plant = $_SESSION['plant'];



$dbname = "db1480761_plantvision";
$conn = mysqli_connect("mysql3792.cp.blacknight.com", "u1480761_Eamonn", "Fabregas@2019", $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$result = mysqli_query($db,"SELECT * FROM imageResult WHERE id = '$id'");
$row = mysqli_fetch_array($result);
$imageI = $row[1];
//echo "<br>" .$imageI;



$sql = mysqli_query($conn, "SELECT * FROM imageResult WHERE id = $id"); 
$row = mysqli_fetch_array($sql);
echo $row[0];
$colourCode = $row[2];
echo "<br>" .$colourCode;
$userID = $row[6];
echo "<br>" .$userID;
$sql = mysqli_query($conn, "SELECT * FROM db_images WHERE user_id = '$userID' AND name = '$plant'"); 
$row = mysqli_fetch_array($sql);
$plant_group = $row[4];
echo "<br>" .$plant_group;
$sql = mysqli_query($conn, "INSERT into markedtable (user_id, plant_group, colourCode, mark) VALUES ($userID, '$plant_group', $colourCode, '')"); 


// sql to delete a record
$sql = mysqli_query($conn, "DELETE FROM imageResult WHERE id = $id"); 


$result = mysqli_query($db,"SELECT * FROM imageResult WHERE imageID = '$imageI'");
$i = 0;
$counter = 0;
while ($row = mysqli_fetch_array($result)){
    $count[$i] =  $row[4];
    $imageID[$i] = $row[0];
    //echo "<br>" .$count[$i];
    //echo "<br>" .$imageID[$i];
    $i++;
    $counter ++;
}

$i = 0;
$totalCount = 0;
while($i < $counter){
    $totalCount = $totalCount + $count[$i];
    $i++;
}
//echo "<br>" .$totalCount;
$i = 0;

while($i < $counter){
    $per = $count[$i]/$totalCount*100;
    $per = round($per, '2');
    //echo "<br>" .$per;
    $sql = mysqli_query($conn, "UPDATE imageResult SET colourPer = '$per' WHERE id = '$imageID[$i]' AND imageID = '$imageI'");
    $i++;      
}

header('Location: editor.php');
?>