<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Plant Vision</title>
        <link rel="stylesheet" href="css/style.css">  
</head>



	
<?php
include 'config.php';
session_start();
//$plant = $_SESSION['plant'];
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}
$imageId = $_SESSION['imageId'];
$result = mysqli_query($db,"SELECT * FROM weather WHERE imageId = '$imageId'");
$row = mysqli_fetch_array($result);
$image = $row['icon'];
$temp = $row['temp'];
$hum = $row['hum'];
$wind = $row['wind'];
$pressure = $row['pressure'];
$cloud = $row['cloud'];
$time = $row['time'];
$desc = $row['description'];

$result2 = mysqli_query($db,"SELECT * FROM gpsLocation WHERE imageId = '$imageId'");
$row2 = mysqli_fetch_array($result2);
$address = $row2['address'];
$long = $row2['longitude'];
$lat = $row2['latitude'];

?>


<style>
.div {
    width: 830px;
    padding: 2px;
    height: 470px;
    border: 2px solid gray;
    background: white;
    position:absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;  
}

.mapContainer{
	position:absolute;
    top:200px;
    left: 300px;
	background: black;
}
</style>

	

<!-- ----------------------HTML------------------------ --> 
<body>
  
  <div class="form">			
    <form  method="POST" class="login-form" >
      <img src= "<?php echo $image?>" alt="" style="width:70% height:70%">
		<h1 align = "left"><u><font color = "darkgreen" >Weather Conditions</font></u></h1> 
	  <h3 align = "left">Description: <font size="5" color = "grey" ><?php echo $desc;?></font></h3>
      <h3 align = "left">Time taken: <font size="5" color = "grey" ><?php echo $time;?></font></h3> 
      <h3 align = "left">Temperature: <font size="5" color = "grey" ><?php echo $temp;?></font><font size="3" color = "grey"> &deg;C</font></h3> 
      <h3 align = "left">Humidity: <font size="5" color = "grey" ><?php echo $hum;?></font><font size="3" color = "grey"> %</font></h3> 
      <h3 align = "left">Wind: <font size="5" color = "grey" ><?php echo $wind;?></font><font size="3" color = "grey"> km/h</font></h3> 
      <h3 align = "left">Cloud coverage: <font size="5" color = "grey" ><?php echo $cloud;?></font><font size="3" color = "grey"> %</font></h3>
		<h1 align = "left"><u><font color = "darkgreen" >Image Location</font></u></h1> 
	   <h3 align = "left">Full Address: <font size="5" color = "grey" ><?php echo $address;?></font></h3> 
	  <div id="map" style="width:400px;height:400px;background:yellow"></div>
      <p class="message">All good?<a href="analyze.php"> Go back</a></p>
    </form>
	  <script>
function myMap() {
  var long = "<?php echo $long ?>";
  var lat = "<?php echo $lat ?>";
  var myLatLng = {lat: 53.2, lng: 64.3};

  var map = new google.maps.Map(document.getElementById('map'), {
	zoom: 15,
    center: new google.maps.LatLng(lat, long),
	mapTypeId: google.maps.MapTypeId.HYBRID,
  });

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(lat, long),
    map: map,
    title: 'Plant location!'
  });
}
</script>
	 
	</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnqWwhEdWXYP0nzbULQvXyfhjP6KvmWzQ&callback=myMap"></script>
</body>
</html> 
    
  
