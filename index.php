<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <br/>
 <div class="container"> 
    <h3>ScienceNews Scrape</h3>
 </div>
 <?php  
    include_once('simple_html_dom.php');
    include_once('articleClass.php');
    include_once('parseDivs.php');
    error_reporting(E_ALL ^ E_WARNING);
    
    
    
    /*Get the arguments for this with JS in a button*/
    function chooseTopic(int $id = 0) {
        switch ($id) {
        case 0:
            return 'life-evolution';
            break;
        case 1:
            echo "i equals 1";
            break;
        case 2:
            echo "i equals 2";
            break;
} 
    }
     
    #base url for the website
    $scienceNewsURL = 'https://www.sciencenews.org/';
    $topic = chooseTopic();
    $chosenTopic = 'topic/' . $topic;
    
    #echo '<div class=container topicTitle><h4>' . $topic . '</h4></div>';
    
     #Url containing news about the chosen topic
    $scrapLink = $scienceNewsURL . $chosenTopic;
    
    #Get the file from the web
    $attemptedConnections = 0;
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $scrapLink);  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
    $str = curl_exec($curl);
    curl_close($curl);  
    $html = str_get_html($str);
    while (!$html && $attemptedConnections<=10){
        $html = file_get_html($scrapLink);
        sleep(1);
        $attemptedConnections++;
    }   
    #Check successfull load
    if ($html != false){
        foreach ($html->find('div') as $element){
            $att = $element->class;
            /*
            if($att == 'article-info'){
                replaceLinks($element, $scienceNewsURL);
            echo ('Featured article: ' . $element );    
            }*/

            # Get the list of news
            if ($att == 'item-list'){
                replaceLinks($element, $scienceNewsURL);
                # Strip elements of the website's divs
                $simplifiedElements = removeBloatDivs($element);
                
                #Loop through articles and show them in rows by Category.
                $indexCat = $simplifiedElements[0]->plainTextCategory; #Initial values for the loop
                $thisCat = $simplifiedElements[0]->getCategory(); #First category of articles
                #$allCategories[-1] = array($indexCat,$simplifiedElements[0]->getCategory());
                $columns ='';
                $id = 0;
                
                foreach($simplifiedElements as $article){
                    if($indexCat === $article->plainTextCategory){
                        $columns = $columns . $article->getArticleDOM()->outertext;
                        #echo $indexCat;
                    }else{
                        #echo $article->plainTextCategory;
                        $allCategories[$id] = array($indexCat,$thisCat);
                        $thisCat = $article->getCategory();
                        $rows[$id] = '<div class="row">' . $columns . '</div>';
                        $indexCat = $article->plainTextCategory;
                        $id++;
                        $columns = $article->getArticleDOM();
                    }
                    
                }
                $allCategories[$id+1] = array($indexCat,$thisCat);
                $rows[$id+1] = '<div class="row">' . $columns . '</div>';
                $mainContainer = '<div class="container">';
                
                $categoryCol = '';
                $i=0;
                foreach($allCategories as $cat){
                    $morebutton = '<div class="one column">' 
                        . '<a href="showTopicNews.php?topic=' . rawurlencode($cat[1]) 
                            .'&pt=' . $cat[0] . '" id="more">(More)</a>'
                        .'</div>';
                    $catColumn = '<div class="eleven columns">'
                                    . '<h5>' . $cat[0] . ': ' 
                                    . '</h5>' 
                                    . '</div>'; 
                    $categoryRow[$i] = '<div class="row categoryBox">' . $catColumn . $morebutton . '</div>';                        
                    $i++;
                }
                
                $i=0;                
                foreach($rows as $row){
                    $mainContainer = $mainContainer . $categoryRow[$i] . $row;
                    $i++;
                }
                $mainContainer = $mainContainer . '</div>';
                echo $mainContainer;

                #echo $simplifiedElement->innertext;
               # echo $element;
            }   
        }
    }else{
       echo 'Failed to load the news page with ' . $attemptedConnections . 'connection attempts.'; 
       
    }    
    
 ?>
 </body>
 <footer>
  <p>Eric Zancanaro: ericzanca@gmail.com</p>
</footer>
</html>