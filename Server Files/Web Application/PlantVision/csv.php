<?php
include 'config.php';
session_start();
$group = 'Plant Group A';
$id = $_SESSION['user_id'];
$group = $_SESSION['group'];

// Creates a new csv file and store it in tmp directory

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=myPlantDetails.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');





$result = mysqli_query($db,"SELECT id, name, plant_group FROM db_images WHERE plant_group = '$group' AND user_id = $id");
$count = 0;
$i = 0;
while ($aRow = mysqli_fetch_array($result)) {
    $imageID[$i] = $aRow[0];
	$count++;
	$i++;
}


fputcsv($output, array('Leaf Name','Plant Group', 'Disease %', 'Time Taken'));
$rows4 = mysqli_query($db, "SELECT leaf_name, plant_group, disease_per, time FROM report WHERE plant_group = '$group' AND user_id = $id");
while($row4 = mysqli_fetch_assoc($rows4)){
	fputcsv($output, $row4);
}

fputcsv($output, array('Image ID', 'Name of Leaf', 'Plant Group'));
$rows3= mysqli_query($db, "SELECT id, name, plant_group FROM db_images WHERE plant_group = '$group' AND user_id = $id");
while($row3 = mysqli_fetch_assoc($rows3)){
	fputcsv($output, $row3);
}

$i = 0;
fputcsv($output, array('Image ID', 'Temperature(C)', 'Humidity %', 'Wind Km/h', 'Pressure(Pa)', 'Cloud Coverage %', 'Time Taken', 'Description'));
while($i < $count){
$rows2 = mysqli_query($db, "SELECT imageId, temp, hum, wind, pressure, cloud, time, description FROM weather WHERE imageId = $imageID[$i]");
$i++;
while($row2 = mysqli_fetch_assoc($rows2)){
	fputcsv($output, $row2);
}
}




$i = 0;
fputcsv($output, array('Image ID','Address', 'Longitude', 'Latitude'));
while($i < $count){
$rows5 = mysqli_query($db, "SELECT imageId, address, longitude, latitude FROM gpsLocation WHERE imageId = $imageID[$i]");
$i++;
while($row5 = mysqli_fetch_assoc($rows5)){
	fputcsv($output, $row5);
}
}



?>