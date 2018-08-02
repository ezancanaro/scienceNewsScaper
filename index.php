<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <a href="fullStructure.php"> Original </a>
 <a href="articleText.php"> showTextxs </a>
 <?php  
    include_once('simple_html_dom.php');
    include_once('articleClass.php');
    
    
    
    
    /*Get the arguments for this with JS in a button*/
    function chooseTopic(int $id = 0) {
        switch ($id) {
        case 0:
            return 'topic/life-evolution';
            break;
        case 1:
            echo "i equals 1";
            break;
        case 2:
            echo "i equals 2";
            break;
} 
    }
    # Replaces the local links with full URLS
    function replaceLinks($element, $url){
        foreach ($element->find('a') as $hyperlink){
            $shortLink = $url . $hyperlink->href;
            $hyperlink->href = $shortLink;
        }
        
    }
    # Removes upper divs, keeping only the relevant content
    function removeBloatDivs ($element){
   
        foreach ($element->find('div.views-row') as $content){
            $content = $content->firstChild();
           
            $myArticle = new article;
            $i=0;
            
            $field = $content->firstChild();
            $articleLink = $content->getAttribute('href');
            #echo $articleLink . '<br>';
            $content = $content->nextSibling();
            while($content != null ){
                
                $field = $content->firstChild();
                if($field){
                    $textArray[$i] = $field->innertext;
                    #echo $textArray[$i] . '<br>' ;
                }
                # Next field containing info about the article
                $content = $content->nextSibling();
                $i = $i+1;    
            }
             
            $myArticle->fillAttributes($textArray);
            $art = $myArticle->getArticleDOM();
            # Array of article objects;
            $articleArray[] = $myArticle;          
        }
        
        return $articleArray;
    }
    
    ini_set('max_execution_time',1000);#Unrealistic but needed for my slow home connection. 
    #base url for the website
    $scienceNewsURL = 'https://www.sciencenews.org/';
    $chosenTopic = chooseTopic();
     #Url containing news about the chosen topic
    $scrapLink = $scienceNewsURL . $chosenTopic;
    
    #Get the file from the web
    $attemptedConnections = 0;
    
    
    $html = file_get_html($scrapLink);

    
    #Check successfull load
    if ($html !== false){
        foreach ($html->find('div') as $element){
            $att = $element->class;
            if($att == 'article-info'){
                replaceLinks($element, $scienceNewsURL);
            echo ('Featured article: ' . $element );    
            }
            # Get the list of news
            if ($att == 'item-list'){
                replaceLinks($element, $scienceNewsURL);
                # Strip elements of the website's divs
                $simplifiedElements = removeBloatDivs($element);
                
                #Loop through articles and show them in rows by Category.
                
                $indexCat = $simplifiedElements[0]->plainTextCategory;
                $allCategories[-1] = $simplifiedElements[0]->getCategory();
                $columns ='';
                $id = 0;
                foreach($simplifiedElements as $article){
                    if($indexCat === $article->plainTextCategory){
                        $columns = $columns . $article->getArticleDOM()->outertext;
                        #echo $indexCat;
                    }else{
                        #echo $article->plainTextCategory;
                        $allCategories[$id] = $article->getCategory();
                        $rows[$id] = '<div class="row">' . $columns . '</div>';
                        $indexCat = $article->plainTextCategory;
                        $id++;
                        $columns = $article->getArticleDOM();
                    }
                    
                }
                $allCategories[$id+1] = $indexCat;
                $rows[$id+1] = '<div class="row">' . $columns . '</div>';
                $mainContainer = '<div class="container">';
                
                $categoryCol = '';
                $i=0;
                foreach($allCategories as $cat){
                    $catColumn = '<div class="twelve columns categoryBox"
                                    >' . $cat . '</div>'; 
                    $categoryRow[$i] = '<div class="row">' . $catColumn . '</div>';                        
                    $i++;
                }
                /* style="display: inline-block;
                                        text-align: center;
                                        background-color: transparent;
                                        border-radius: 4px;
                                        border: 1px solid #bbb;"*/
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
        /*
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        foreach ($dom->getElementsByTagName('div') as $node) {
            if ($node->getAttribute('class') == 'article-info')
                echo $node;
        }*/
    
 ?>
 </body>
</html>