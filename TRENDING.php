<?php


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
 
    $PLAYLISTS = array('PLrEnWoR732-BHrPp_Pm8_VleD68f9s14-','PLrEnWoR732-D67iteOI6DPdJH1opjAuJt' ,'PLrEnWoR732-CFfUX4TPybGxiO7q_5OS8D','PLrEnWoR732-CvU2EIng1mKhlXJHvaiAVM','PLrEnWoR732-DN561GnxXKMlocLMc4v4jL','PLrEnWoR732-AMp_tf9DDKAP_Vymn8zqh3','PLrEnWoR732-CN09YykVof2lxdI3MLOZda','PLrEnWoR732-D6uerjQ8dZiyy9bJID58CK','PLrEnWoR732-AtjHiRPQD7-Xaqa3SaXo-4','PLrEnWoR732-CV75Y0BCvbVyGDtjoghNEg','PLrEnWoR732-ByK1fe0UAOPbvy5TF5SALg','PLrEnWoR732-DZV1Jc8bUpVTF_HTPbywpE','PL-DfNcB3lim_WSHspytYZewQMWys0UOal', 'PL-DfNcB3lim9A2N3j94iYR4_TZ9VHSkFd','PL-DfNcB3lim9QjDJ1R8SMqec36o0xulEw','PL-DfNcB3lim_CDqzfWjqIRTBATo91EXQj','PL-DfNcB3lim_zrWrA4YIcQmtSSw0rib0e','PL-DfNcB3lim9A5ByvM484EQVHgXiOlAf2','PL-DfNcB3lim8TRkPtqwDhN0oubN71vnr9','PLp12xt0S4J0WYbhrjgfF-A7PK8XE0sW_Z','PL3ZQ5CpNulQnQBTmhuEMlLaT3jEUDn9C1'); 

    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#playlist':   
    
        for ($index = 0; $index<sizeof ($PLAYLISTS); $index++){
    
          $playlists .= sprintf('<li>%s (%s)</li>',
          $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
          $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
              'playlistId' => $PLAYLISTS[$index], 
              'maxResults' => 50
          ));

      // Pulls 1015 videos. 4 of them appear as 'deleted videos' and the other 11 are actually deleted.
            foreach ($playlistItemsResponse['items'] as $playlistItem) {
                $_POST = sprintf('<li>%s (%s)</li>', $playlistItem['snippet']['title'],
                $playlistItem['snippet']['resourceId']['videoId']);
                echo $playlistItem['snippet']['title']; 
                echo "<br>";
                $counter = $counter +1;
                           
                mysql_connect("localhost","root", "secret");
                mysql_select_db("test_database");
                $select = "insert into members(snippettitle, idvideoid)value('". $playlistItem['snippet']['title']."', '".$playlistItem['snippet']['resourceId']['videoId']."')";
                $sql=mysql_query($select);

                mysql_close();

            }
 
        }
      break;
    }
}
  

?>
