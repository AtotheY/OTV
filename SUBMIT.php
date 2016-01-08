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
$select = mysql_query("SELECT * FROM submitvideos");
$select2 = mysql_query("SELECT * FROM submitvideos WHERE no_of_views<$user_views ");
$numRow = mysql_num_rows($select);


$using =10;

$videoid = '';
$viewcount = '';
$idnum = '';

while ($videos = mysql_fetch_assoc($select)){

	$idnum [] = $videos['id'];

	if ($videos ['IdVideoId'] == $user_upload){ 
	
		$using = 0; 
		break;

	}
	else {		
		while ($videos = mysql_fetch_assoc($select2)){							
			$lessviews [] = $videos['id'];
		}
	}
}


if ($using == 0){
	//echo 'File already exists';
	//echo "<br>";
	return 0;
}


if (!empty($lessviews)){


	for ($a = 0; $a<sizeof($lessviews); $a++){

		$random_pick = $lessviews[array_rand($lessviews)];

		mysql_query("UPDATE  submitvideos SET IdVideoId = '$user_upload', no_of_views = '$user_views' WHERE id = '$random_pick' ");
		return $random_pick;
	}

}


if (empty($lessviews)){

	for ($a = 0; $a<sizeof($idnum); $a++){

		$random_pick = $idnum[array_rand($idnum)];

		mysql_query("UPDATE  submitvideos SET IdVideoId = '$user_upload', no_of_views = '$user_views' WHERE id = '$random_pick' ");
		return $random_pick;

	}
	
}


}


?>

