<?php

function display($id){

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'draft_database';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
	die("Connection failed:" . $conn->connect_error);
}

$query = "SELECT * FROM `videos` WHERE `id`='" . $id . "'";
$result = mysqli_query($conn,$query);

$row = mysqli_fetch_array($result);

$vidId = $row['IdVideoid'];

$uRL = 'https://www.youtube.com/embed/' . $vidId;

$conn->close();
return $uRL;

}
?>