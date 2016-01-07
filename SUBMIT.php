<?php

function submit ($URL) {


$parts = explode ('watch?v=', $URL);


require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';

$DEVELOPER_KEY = 'AIzaSyBgi9IQtPvHCtOFZEwD674WZV10rhm_5I4';

$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);

$youtube = new Google_Service_YouTube($client);

 
$user_upload=$parts[1];


$JSON = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$user_upload}&key={$DEVELOPER_KEY}");
$JSON_Data = json_decode($JSON,true);

$user_views = $JSON_Data['items'][0]['statistics']['viewCount'];

mysql_connect("localhost","root", "secret");
mysql_select_db("test_database");
$select = mysql_query("SELECT * FROM members ORDER BY no_of_views DESC");
$numRow = mysql_num_rows($select);


$using =10;

$videoid = '';
$viewcount = '';

while ($videos = mysql_fetch_assoc($select)){

	if ($videos ['IdVideoId'] == $user_upload){ 
	
		$using = 0; 
		break;

	}
	else {									
		$videoid[] = $videos ['IdVideoId'];
		$viewcount [] =$videos['no_of_views'];
		$using = 1;
	}
}

if ($using == 0){
	//echo 'File already exists';
	//echo "<br>";
	return 0;
}

if ($using == 1){

	for ($a = 0; $a<sizeof($videoid); $a++){
		for ($b = 0; $b<sizeof($viewcount); $b++){

			if ($viewcount [$b]<$user_views){

				mysql_query("UPDATE  members SET IdVideoId = '$user_upload', no_of_views = '$user_views' WHERE IdVideoId = '$videoid[$a]' ");
				return $user_upload;
 			}
			if (!($viewcount [$b] <$user_views)){

				$random_pick = $videoid[array_rand($videoid)];
				//echo "randomly picked id: ";
				//echo $random_pick;
				for ($a = 0; $a<sizeof($videoid); $a++){
  		 
					if ($videoid[$a] ==$random_pick){
			
						mysql_query("UPDATE  members SET IdVideoId = '$user_upload', no_of_views = '$user_views' WHERE IdVideoId = '$videoid[$a]' ");
						return $user_upload;			
					}
				}

			}
		}
	}
}



}


?>

