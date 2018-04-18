<?php 
 
 //importing dbDetails file 
 require_once 'dbDetails.php';
 
 
 //this is our upload folder 
 $upload_path = 'AndroidImageUpload/uploads/';
 $upload_path2 = '/AndroidImageUpload/uploads/';

 
 //Getting the server ip 
 $server_ip = 'www.c00197458.candept.com';
 
 //creating the upload url 
 $upload_url = 'http://'.$server_ip.$upload_path2; 


 //response array 
 $response = array(); 
 
 
 if($_SERVER['REQUEST_METHOD']=='POST'){
 
 //checking the required parameters from the request 
 if(isset($_POST['name']) and isset($_FILES['image']['name'])){
 
 //connecting to the database 
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');
 
 //getting name from the request 
 $name = $_POST['name'];
	 

 //getting file info from the request 
 $fileinfo = pathinfo($_FILES['image']['name']);
 
 //getting the file extension 
 $extension = $fileinfo['extension'];
 
 //file url to store in the database 
 $file_url = $upload_url . getFileName() . '.' . $extension;
 
 //file path to upload in the server 
 $file_path = $upload_path  . getFileName() . '.'. $extension; 

	 
//getting username
$username = $_POST['username'];
//$username = file_get_contents("log.txt");
$result = mysqli_query($con, "SELECT * FROM user WHERE username='$username' ");
$row = mysqli_fetch_array($result);
$userID = $row['user_id'];

//$plant_group = 'No Group Defined';
$plant_group = $_POST['group'];
	 
date_default_timezone_set("Europe/Dublin"); //getting current timezone
$date = date('Y-m-d H:i:s', time()); //getting current timestamp
	 
	 
//Writing to group.txt for later retrieval
$file = 'group.txt';
file_put_contents($file, $plant_group);	 
	 


	 

	

	 
// ------------- Plant duplicate name check ------------------------------------
$i;
$sql = mysqli_query($con, "SELECT * from db_images WHERE user_id = '$userID'");
 while ($row = mysqli_fetch_array($sql)) {
 	 if($name.$i == $row[2]){
		 $i++;
		 //echo $i;
		 //echo "<br>".$name.$i;		 
		 }	 
	 }
$name = $name . $i;
//echo "<br>" .$name;
// ------------ end / Duplicate name check -------------------------------------------	 
	 
	 
	 
	 
 //trying to save the file in the directory 
 try{
 //saving the file 
 move_uploaded_file($_FILES['image']['tmp_name'],$file_path);
 $sql = "INSERT INTO db_images (id, url, name, user_id, plant_group, datetime) VALUES (null, '$file_url', '$name', '$userID', '$plant_group', '$date')";
  
 echo "<br>".$file_url;	 

	 
//---------------------------------- Image processing --------------------------------
	 	 
include_once("colors.inc.php");
$ex=new GetMostCommonColors();
$ex->image=$file_url;
$colors=$ex->Get_Color();
$how_many=12;
$colors_key=array_keys($colors);
	 
$i = 0;
$totalPix = 0;
$percentage = [];

// Gathering total amount of pixels
while($i < 12){

	$totalPix = $totalPix + $colors[$colors_key[$i]];
	$colourCount[$i] = $colors[$colors_key[$i]];
	$i ++;	
}
	 
	 
// Gatering percentage 
$i = 0;
$p = 0;
while($i < 12){
	$percentage[$p] = round($colors[$colors_key[$i]] / $totalPix * 100, 2);
	$p ++;
	$i ++;
	
} 	 

	 
$i = 0;
$p = 0;
$mark = "";
//Getting ID of image to insert to 'imageResult'
$idNumber = getFileName();
while($i < 12){
	$hex = $colors_key[$i];
	$hex = hexdec($hex);
	$per = $percentage[$p];
	$result = mysqli_query($con, "INSERT into imageResult (imageID, colourCode, colourPer, colourCount, diseaseM, user_id) VALUES ($idNumber, $hex, $per,   $colourCount[$i], '$mark', '$userID')");
	$p ++;
	$i ++;			
}
//-----------------------------------------------------------------------------------
	  
	 

 // ---------------  Getting radio answer / Check for avoided colours --------------------	 
 $checkGroup = $_POST['radio'];
 $i = 0;
 $count = 0;
 if($checkGroup == "Yes"){
	//echo "<br>in";
	//echo "<br>" .$userID;
	//echo "<br>" .$plant_group;
	$avoidSQL = mysqli_query($con, "SELECT * from markedtable WHERE user_id = '$userID' AND plant_group = '$plant_group' AND mark = ''"); 
	while ($row = mysqli_fetch_array($avoidSQL)) {
    $avoid[$i] =  $row[3];
    //$colour[$i] = dechex($colour[$i]);
	//echo "<br>" .$avoid[$i];
    $i++;
 	$count++;	
	}
	$i = 0;
	while($i < $count){
		$deleteSQL = mysqli_query($con, "DELETE from imageResult WHERE imageID = '$idNumber' AND user_id = '$userID' AND colourCode= '$avoid[$i]'"); 
		$i ++;
	}
	 
	$searchSQL = mysqli_query($con,"SELECT * FROM imageResult WHERE imageID = '$idNumber'");
	$i = 0;
	$counter = 0;
	while ($row2 = mysqli_fetch_array($searchSQL)){
		$countOfColours[$i] =  $row2[4];
		$imageIDs[$i] = $row2[0];
		//echo "<br>" .$count[$i];
		//echo "<br>" .$imageID[$i];
		$i++;
		$counter ++;
	}

	$i = 0;
	$totalCount = 0;
	while($i < $counter){
		$totalCount = $totalCount + $countOfColours[$i];
		$i++;
	}
	//echo "<br>" .$totalCount;
	$i = 0;

	while($i < $counter){
		$per = $countOfColours[$i]/$totalCount*100;
		$per = round($per, '2');
		//echo "<br>" .$per;
		$update = mysqli_query($con, "UPDATE imageResult SET colourPer = '$per' WHERE id = '$imageIDs[$i]' AND imageID = '$idNumber'");
		$i++;      
	}  	
	 
	// ------------ Check for marked disease ----------------- 
	 
	$markCheck = mysqli_query($con, "SELECT * from markedtable WHERE user_id = $userID AND plant_group = '$plant_group' AND mark = 'Marked'");
	$markCheckRow = mysqli_fetch_array($markCheck);
	$markedDisease = $markCheckRow[3];
	//echo "<br>".$markedDisease;
	$hex = dechex($markedDisease);
	$hex = "#".$hex;
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	//echo "<br>";
	//echo "$hex -> $r $g $b";
	$r = $r+1;
	$g = $g+1;
	$b = $b+1;
	 
	$getResults = mysqli_query($con, "SELECT * from imageResult WHERE user_id = $userID AND imageID = $idNumber");
	$i = 0;
	while ($getResultA = mysqli_fetch_array($getResults)){
		$id[$i] = $getResultA[0];
		$hex = $getResultA[2];
		$hex = dechex($hex);
		$hexVal[$i] = "#".$hex;
		//echo "<br>" .$hexVal[$i];
		
		list($r1, $g1, $b1) = sscanf($hexVal[$i], "#%02x%02x%02x");
		
		$r1 = $r1+1;
		$g1 = $g1+1;
		$b1 = $b1+1;
		//echo "<br>" .$r1;
		//echo "," .$g1;
		//echo "," .$b1;		
		
		$percentageChangeRed = (1- $r/$r1) * 100;
		$percentageChangeGreen = (1- $g/$g1) * 100;
		$percentageChangeBlue = (1- $b/$b1) * 100;
		
		//echo "<br> Red is " .$percentageChangeRed;
		//echo "<br> Green is " .$percentageChangeGreen;
		//echo "<br> Blue is " .$percentageChangeBlue;
		
		if($percentageChangeRed < 5 && $percentageChangeRed > -5){
			if($percentageChangeGreen < 5 && $percentageChangeGreen > -5){
				if($percentageChangeBlue < 5 && $percentageChangeBlue > -5){
					$target = $hexVal[$i];
					$targetID = $id[$i];
				}
			}			
		}
		$i++;
	}
	//echo "<br><br> The Target is: ".$target;
	$updateQuery = mysqli_query($con, "UPDATE imageResult SET diseaseM = 'Diseased' WHERE user_id = $userID AND id = $targetID");
	
	 //echo "<br>" .$targetID;
	
	// ---------------- Inserting in mobileResult -----------------
	
	//$deleteMob = mysqli_query($con, "DELETE * from mobileResult WHERE id = 1");
	$result3 = mysqli_query($con, "SELECT * from imageResult WHERE id = $targetID");
	echo "<br>" .$targetID;
	$rArray = mysqli_fetch_array($result3);
	echo "<br>" .$rArray[3];
	$value = $rArray[3];
	echo "<br>" .$value;
	$str = "$value"; 
	echo "<br>" .$str;
	$mobile = mysqli_query($con, "UPDATE mobileResult SET name = '$str' WHERE id = 1");	 
	 
	echo "<br> All works";
	// ---------------- end / mobileResult ------------------------
	
	
	// ------------ end / check for marked disease -----------
	  
}
	 
else if($checkGroup == "No"){
	echo "<br>Not in";
}
// ---------------- end ----------------------------------		 

	 
	 
	 
//--------- Getting GPS co -----	
$address = $_POST['address']; 
$long = $_POST['longitude'];
$lat = $_POST['latitude'];
$gpsLocationQuery = mysqli_query($con, "INSERT into gpsLocation (imageId, address, longitude, latitude) VALUES ($idNumber, '$address', $long, $lat)");	


	 
	 
//-------------------- Weather ---------------------------------------------------------------	 
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$region = $details -> region;
$country = $details -> country;
$ip = ip2long($ip);
$location = $region. "," .$country;
if($region == ""){
	$location = "Ireland";
}
$jsonurl = "http://api.openweathermap.org/data/2.5/weather?q={$location}&appid=fc72c995fe6227c7f1f244ca806b300b";
$json = file_get_contents($jsonurl);
$weather = json_decode($json);
$data=json_decode($json,true);

$temp = $weather->main->temp;
$temp = $temp - 273.15;
$humidity =  $weather->main->humidity;
$cloud =  $weather->clouds->all;
$wind = $weather->wind->speed;
$pressure = $weather->main->pressure;
$icon = $data['weather']['0']['icon'];
$url = 'http://openweathermap.org/img/w/';
$icon = $url.$icon.".png";
$desc = $data['weather']['0']['description'];
date_default_timezone_set("Europe/Dublin");

$weather = mysqli_query($con, "INSERT into weather (imageId, ip, temp, hum, wind, pressure, cloud, time, description, icon) VALUES ($idNumber, $ip, $temp, $humidity, $wind, $pressure, $cloud, NOW(), '$desc', '$icon')");


//------------------------- end / Weather ----------------------------------------------------------
	 
	 
 
	 
 //adding the path and name to database 
 if(mysqli_query($con,$sql)){
 
 //filling response array with values 
 $response['error'] = false; 
 $response['url'] = $file_url; 
 $response['name'] = $name;
 }
 //if some error occurred 
 }catch(Exception $e){
 $response['error']=true;
 $response['message']=$e->getMessage();
 } 
 //displaying the response 
 //echo json_encode($response);
 
 //closing the connection 
 mysqli_close($con);
 }else{
 $response['error']=true;
 $response['message']='Please choose a file';
 }
 }
 
 /*
 We are generating the file name 
 so this method will return a file name for the image to be upload 
 */
 function getFileName(){
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');
 $sql = "SELECT max(id) as id FROM db_images";
 $result = mysqli_fetch_array(mysqli_query($con,$sql));
 mysqli_close($con);
 if($result['id']==null)
 return 1; 
 else 
 return ++$result['id']; 
 }
 
 ?>
 
