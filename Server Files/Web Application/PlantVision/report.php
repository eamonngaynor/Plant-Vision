<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/circle.css">
<script type="text/javascript" src="fusioncharts/js/fusioncharts.js"></script>
<script type="text/javascript" src="fusioncharts/js/themes/fusioncharts.theme.ocean.js"></script>
<title>Plant Vision</title>
</head>

<style>

.div {
    width: 1400px;
    padding: 2px;
    height: 680px;
    border: 2px solid gray;
    background: white;
    position:absolute;
    top: 3%;
    left: 4%;  
}
	
#home{
    position:absolute;
    top: 3%;
    left: 2%;
}
	
#homeText{
    position:absolute;
    top: 11%;
    left: 2.5%;
}

#CSVText{
    position:absolute;
    top: 24%;
    left: 1%;
}
	
#plantText{
    position:absolute;
    top: 36%;
    left: 0.2%;
}
	
#csv{
    position:absolute;
    top: 15%;
    left: 2%;
}
	
#plant{
    position:absolute;
    top: 28%;
    left: 2%;
}
	
	
#title{
	position:absolute;
    top: 8%;
    left: 20%;
}
	
#displayP{
    position:absolute;
    top: 10%;
    left: 13%;
}

#chart-1{
    position:absolute;
    top: 3%;
    left: 54%;
}

#chart2{
    position:absolute;
    top: 50%;
    left: 55%;
}
	

#chart-3{
    position:absolute;
    top: 55%;
    left: 9%;
}	

</style>


<?php 
include 'config.php';
session_start();
$group = $_SESSION['group'];
$_SESSION['group'] = $group;
//$group = "Plant Group A";
//echo $group;
$username = $_SESSION['login_user'];
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}	

include 'fusioncharts.php';

/* The following 4 code lines contain the database connection information. Alternatively, you can move these code lines to a separate file and include the file here. You can also modify this code based on your database connection. */

 $hostdb = "mysql3792.cp.blacknight.com";  // MySQl host
 $userdb = "u1480761_Eamonn";  // MySQL username
 $passdb = "Fabregas@2019";  // MySQL password
 $namedb = "db1480761_plantvision";  // MySQL database name

 // Establish a connection to the database
 $dbhandle = new mysqli($hostdb, $userdb, $passdb, $namedb);


$result = mysqli_query($db,"SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_array($result);
$user_id = $row[0];
$_SESSION['user_id'] = $user_id;
//echo $user_id;

 // sql to delete a record
$sql = "DELETE FROM report WHERE user_id = '$user_id'";

if ($db->query($sql) === TRUE) {
    //echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $db->error;
}

 $result = mysqli_query($db,"SELECT * FROM db_images WHERE plant_group = '$group' AND user_id = '$user_id'");
 $i = 0;
 $counter = 0;
 while ($row = mysqli_fetch_array($result)) {
     $id[$i] = $row[0];
     $name[$i] = $row[2];
     $time[$i] = $row[5];/*
     echo "<br>" .$time[$i];
     echo "<br>" .$id[$i];
     echo "<br>" .$name[$i];*/
     $i++;
     $counter++;
 }
$i = 0;
 while ($i < $counter){
     $result = mysqli_query($db,"SELECT * FROM imageResult WHERE diseaseM = 'Diseased' AND user_id = '$user_id' AND imageID = '$id[$i]'");
     $row = mysqli_fetch_array($result);
     $diseasePer[$i] = $row[3];
     //echo "<br>" .$diseasePer[$i];
     $i++;
 }

$i = 0;
while($i < $counter){    
    $sql = "INSERT INTO report (user_id, leaf_name, plant_group, disease_per, time) VALUES ('$user_id', '$name[$i]', '$group', '$diseasePer[$i]', '$time[$i]')";
			
			if($diseasePer[$i] == "")
			{
				echo "<script type='text/javascript'>alert('Please note: Some samples were not assigned a disease and have been left out from the report.')</script>";
			}			
			if ($db->query($sql) === TRUE){ //checking connection to the table/db
				//echo "<br>Done";
			} 
			/*
			else{
			echo "Error: " . $sql . "<br>" . $db->error;} //connect_error message */
    $i++;	
}



 // Render an error message, to avoid abrupt failure, if the database connection parameters are incorrect
 if ($dbhandle->connect_error) {
  exit("There was an error with your connection: ".$dbhandle->connect_error);
 }
  
 
  // Form the SQL query that returns the top 10 most populous countries
  $strQuery = "SELECT * FROM report WHERE plant_group = '$group' AND user_id = '$user_id'";  
  // Execute the query, or else return the error message.
  $result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");


  // If the query returns a valid response, prepare the JSON string
  if ($result) {
    // The `$arrData` array holds the chart attributes and data
    $arrData = array(
      "chart" => array(
          "caption" => "Disease % of " .$group,
          "paletteColors" => "#0075c2",
          "bgColor" => "#ffffff",
          "borderAlpha"=> "20",
          "canvasBorderAlpha"=> "0",
          "usePlotGradientColor"=> "0",
          "plotBorderAlpha"=> "10",
		   "palettecolors" => "FF5904,0372AB,FF0000,3fe1a5, a7e813, bb18af, 4a21e9",
          "xAxisName" => "Title of Leaf",
          "yAxisName" => "Disease (%)",
          "showXAxisLine"=> "1",
          "xAxisLineColor" => "#999999",
          "showValues" => "0",
          "divlineColor" => "#999999",
          "divLineIsDashed" => "1",
          "showAlternateHGridColor" => "0"
        )
    );

    $arrData["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($result)) {
      array_push($arrData["data"], array(
          "label" => $row["leaf_name"],
          "value" => $row["disease_per"]
          )
      );
    }    
    /*
    foreach($arrData['data'] as $result) {
        echo $result['value'], '<br>';
        echo $result['label'], '<br>';
    }
    */
    /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */
    $jsonEncodedData = json_encode($arrData);
    /*Create an object for the column chart using the FusionCharts PHP class constructor. 
    Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, 
    "div id to render the chart", "data format", "data source")`. 
    Because we are using JSON data to render the chart, the data format will be `json`. 
    The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the 
    data source parameter of the constructor.*/
    $columnChart = new FusionCharts("column3d", "myFirstChart" , 600, 300, "chart-1", "json", $jsonEncodedData);
    // Render the chart
    $columnChart->render();


  }

  
  // Form the SQL query that returns the top 10 most populous countries
  $strQuery = "SELECT * FROM report WHERE plant_group = '$group' AND user_id = '$user_id'";  
  // Execute the query, or else return the error message.
  $result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");


  // If the query returns a valid response, prepare the JSON string
  if ($result) {
    // The `$arrData` array holds the chart attributes and data
    $arrData2 = array(
      "chart" => array(
          "caption" => "Disease % of " .$group. " over time",
          "paletteColors" => "#0075c2",
          "bgColor" => "#ffffff",
          "borderAlpha"=> "20",
          "canvasBorderAlpha"=> "0",
          "usePlotGradientColor"=> "0",
          "plotBorderAlpha"=> "10",
          "xAxisName" => "Time Period",
		   "palettecolors" => "3fe1a5, a7e813, bb18af, 4a21e9",
          "yAxisName" => "Disease (%)",
          "showXAxisLine"=> "1",
          "xAxisLineColor" => "#999999",
          "showValues" => "0",
          "divlineColor" => "#999999",
          "divLineIsDashed" => "1",
          "showAlternateHGridColor" => "0"
        )
    );

    $arrData2["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($result)) {
      array_push($arrData2["data"], array(
          "label" => $row["time"],
          "value" => $row["disease_per"]
          )
      );
    }    
    /*
    foreach($arrData['data'] as $result) {
        echo $result['value'], '<br>';
        echo $result['label'], '<br>';
    }
    */
    /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */
    $jsonEncodedData2 = json_encode($arrData2);
    /*Create an object for the column chart using the FusionCharts PHP class constructor. 
    Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, 
    "div id to render the chart", "data format", "data source")`. 
    Because we are using JSON data to render the chart, the data format will be `json`. 
    The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the 
    data source parameter of the constructor.*/
    $columnChart2 = new FusionCharts("line", "myFirstChart2" , 600, 300, "chart2", "json", $jsonEncodedData2);
    // Render the chart
    $columnChart2->render();

  }

// Form the SQL query that returns the top 10 most populous countries
  $strQuery = "SELECT * FROM report WHERE plant_group = '$group' AND user_id = '$user_id'";  
  // Execute the query, or else return the error message.
  $result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");


  // If the query returns a valid response, prepare the JSON string
  if ($result) {
    // The `$arrData` array holds the chart attributes and data
    $arrData3 = array(
      "chart" => array(
          "caption" => "Disease % of " .$group,
          "paletteColors" => "#0075c2",
          "bgColor" => "#ffffff",
          "borderAlpha"=> "20",
          "canvasBorderAlpha"=> "0",
          "usePlotGradientColor"=> "0",
          "plotBorderAlpha"=> "10",
          "palettecolors" => "FF5904,0372AB,FF0000,3fe1a5, a7e813, bb18af, 4a21e9",
          "xAxisName" => "Title of Leaf",
          "yAxisName" => "Disease (%)",
          "showXAxisLine"=> "1",
          "xAxisLineColor" => "#999999",
          "showValues" => "0",
          "divlineColor" => "#999999",
          "divLineIsDashed" => "1",
          "showAlternateHGridColor" => "0"
        )
    );

    $arrData3["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($result)) {
      array_push($arrData3["data"], array(
          "label" => $row["leaf_name"],
          "value" => $row["disease_per"]
          )
      );
    }    
    /*
    foreach($arrData['data'] as $result) {
        echo $result['value'], '<br>';
        echo $result['label'], '<br>';
    }
    */
    /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */
    $jsonEncodedData3 = json_encode($arrData3);
    /*Create an object for the column chart using the FusionCharts PHP class constructor. 
    Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, 
    "div id to render the chart", "data format", "data source")`. 
    Because we are using JSON data to render the chart, the data format will be `json`. 
    The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the 
    data source parameter of the constructor.*/
    $columnChart3 = new FusionCharts("pie3d", "myFirstChart3" , 500, 300, "chart-3", "json", $jsonEncodedData3);
    // Render the chart
    $columnChart3->render();

    
    // Close the database connection
    $dbhandle->close();

  }


?>


<body>

	
<div class = "div">
	
<div id="title">
	<b><font color = "black" size = "3">Hightest value of Disease %</font></b>
</div>
	
<div id = "displayP">
<div class="c100 p10 orange big">
  <span><?php echo max($diseasePer);?></span>
  <div class="slice">
    <div class="bar"></div>
    <div class="fill"></div>
  </div>
</div>	
</div>
	
	
<div id="homeText">
<b><font size = "2">Home
</font>
</b>
</div>		
	
	
<div id="CSVText">
<b><font size = "2">Download Data
</font>
</b>
</div>		
	
	
<div id="plantText">
<b><font size = "2">Choose Anoter Plant
</font>
</b>
</div>		

<div id="home">
<a href = "home.php">
<img src="homeb.png" title="Go Home" width="50" height="50" alt="" onclick = "home.php">
</a>
</div>	
	
<div id="csv">
<a href = "csv.php">
<img src="csv.png" title="Download CSV data" width="50" height="50" alt="">
</a>
</div>
	
<div id="plant">
<a href = "reportIndex.php">
<img src="plant.png" title="Choose another Plant" width="50" height="50" alt="">
</a>
</div>
	
<div id="chart-1"><!-- Fusion Charts will render here--></div>



<div id="chart2"><!-- Fusion Charts will render here--></div>

	
<div id="chart-3"><!-- Fusion Charts will render here--></div>
</div> 




</body>
</html>



      