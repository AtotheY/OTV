<?php

function update () {


require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';

$DEVELOPER_KEY = 'AIzaSyBgi9IQtPvHCtOFZEwD674WZV10rhm_5I4';

$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);

$youtube = new Google_Service_YouTube($client);

$searchResponse = $youtube->search->listSearch('id,snippet', array(
  'q' => 'Popular Right Now',
));

$playlists = '';
$counter = 0; 
$using = 1; 

$PLAYLISTS = array('PLrEnWoR732-BHrPp_Pm8_VleD68f9s14-','PLrEnWoR732-D67iteOI6DPdJH1opjAuJt' ,'PLrEnWoR732-CFfUX4TPybGxiO7q_5OS8D','PLrEnWoR732-CvU2EIng1mKhlXJHvaiAVM','PLrEnWoR732-DN561GnxXKMlocLMc4v4jL','PLrEnWoR732-AMp_tf9DDKAP_Vymn8zqh3','PLrEnWoR732-CN09YykVof2lxdI3MLOZda','PLrEnWoR732-D6uerjQ8dZiyy9bJID58CK','PLrEnWoR732-AtjHiRPQD7-Xaqa3SaXo-4','PLrEnWoR732-CV75Y0BCvbVyGDtjoghNEg','PLrEnWoR732-ByK1fe0UAOPbvy5TF5SALg','PLrEnWoR732-DZV1Jc8bUpVTF_HTPbywpE');

foreach ($searchResponse['items'] as $searchResult) {
  switch ($searchResult['id']['kind']) {
    case 'youtube#playlist':   
   
      while ($using == 1){
     
        for ($index = 0; $index<sizeof ($PLAYLISTS); $index++){
  
          $playlists .= sprintf('<li>%s (%s)</li>',
          $searchResult['snippet']['title'], $searchResult['id']['playlistId']);

        
          $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
              'playlistId' => $PLAYLISTS[$index], 
              'maxResults' => 2
          ));   
             
            foreach ($playlistItemsResponse['items'] as $playlistItem) {

                $_POST = sprintf('<li>%s (%s)</li>', $playlistItem['snippet']['title'],
                $playlistItem['snippet']['resourceId']['videoId']);

                $videoid =  $playlistItem['snippet']['resourceId']['videoId'];
      
                 
                $counter++;
                echo $counter; 
                echo $playlistItem['snippet']['title']; 
                echo "<br>";   

                $JSON = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$videoid}&key={$DEVELOPER_KEY}");
                $JSON_Data = json_decode($JSON,true);

                $user_views = $JSON_Data['items'][0]['statistics']['viewCount'];
        
                           
                mysql_connect("localhost","root", "secret");
                mysql_select_db("test_database");
               // $select = "insert into fill(snippettitle, idvideoid)value('". $playlistItem['snippet']['title']."', '".$playlistItem['snippet']['resourceId']['videoId']."')";

                $random = int rand(1,1000);
                mysql_query("UPDATE  members SET IdVideoId = $playlistItem['snippet']['resourceId']['videoId'], no_of_views = '$user_views' WHERE id = '$random' ");

                //$sql=mysql_query($select);

                mysql_close();

            

                  if ($counter == 24){  // VIDEO COUNTER
                    $using = 2;   
                    break; 
                  } 
            }

              if ($using == 2){ 
                break;
              }
               
          }         
        }

    break;
  }
}

}

?>