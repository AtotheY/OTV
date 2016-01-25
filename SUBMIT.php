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

$JSON = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$user_upload}&fields=items(id%2Csnippet)&key={$DEVELOPER_KEY}");
$JSON_title = json_decode($JSON,true);

$title = $JSON_title['items'][0]['snippet']['title'];

$JSON = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$user_upload}&key={$DEVELOPER_KEY}");
$JSON_Data = json_decode($JSON,true);

$user_views = $JSON_Data['items'][0]['statistics']['viewCount'];

mysql_connect("localhost","root", "secret");
mysql_select_db("test_database");
$select2 = mysql_query("SELECT * FROM submitvideos WHERE no_of_views<$user_views ");
$select3 =  mysql_query("SELECT * FROM submitvideos WHERE video_id = '$user_upload'");
$numRow = mysql_num_rows($select);
$thumbnail_url = "http://img.youtube.com/vi/$user_upload/0.jpg";

$using =10;
$videoid = '';
$viewcount = '';
$idnum = '';

if(!(mysql_num_rows($select3) == 0))
{
	$using = 0; 
}

else {

	while ($videos = mysql_fetch_assoc($select2)){							
			$lessviews [] = $videos['rank'];
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
  		
		mysql_query("UPDATE  submitvideos SET title = '$title', video_id = '$user_upload', no_of_views = '$user_views', thumbnail_url = '$thumbnail_url' WHERE rank = '$random_pick' ");
		return $random_pick;
	}

}


if (empty($lessviews)){

	$random_pick = rand (1,25);

		mysql_query("UPDATE  submitvideos SET title = '$title', video_id = '$user_upload', no_of_views = '$user_views', thumbnail_url = '$thumbnail_url' WHERE rank = '$random_pick' ");

		
		return $random_pick;

	
}


}


?>

