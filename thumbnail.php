<?php



function thumbnail($id){

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'draft_database';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM `thumbnaildb` WHERE `id`='" . $id . "'";
$result = mysqli_query($conn,$query);

$row = mysqli_fetch_array($result);

$data['descriptionofvid'] = $row['descriptionofvid'];
$data['title'] = $row['title'];
$data['imgurl'] = $row['imgurl'];
$data['views'] = $row['views'];

$conn->close();
return $data;

}




function store($id){

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'draft_database';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM `videos` WHERE `id`='" . $id . "'";
$result = mysqli_query($conn,$query);

$row = mysqli_fetch_array($result);

$videoId = $row['IdVideoid'];

require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';

$DEVELOPER_KEY = 'AIzaSyBgi9IQtPvHCtOFZEwD674WZV10rhm_5I4';
$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);

$youtube = new Google_Service_YouTube($client);
$JSON = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$videoId}&key={$DEVELOPER_KEY}");
$JSON_Data = json_decode($JSON,true);

echo $response['entry']['media$group']['media$description']['$t'];



}
?>