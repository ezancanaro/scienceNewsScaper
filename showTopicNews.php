<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <a href="fullStructure.php"> Original </a>
 <?php  
    include_once('simple_html_dom.php');
    include_once('articleClass.php');
    include_once('parseDivs.php');
    error_reporting(E_ALL ^ E_WARNING); 
    define('MAIN_CONTAINER','<div class="container">');
    set_time_limit(90); 
    
    
    
    if( isset($_GET['topic'])){
        $topicURL = rawurldecode($_GET['topic']);
        $pt = $_GET['pt'];
        showArticleList($topicURL, $pt);
    }else{
        showArticleList('');
        #showErrorPage();
    }
    
    function showErrorPage(){
        $errorContainer = MAIN_CONTAINER . '<a href="index.php">'
                                . '<h2> Something went wrong. Return to previous page </h2>'
                                    .'</a>' .   '<\div>';
        echo $errorContainer;
    }
    
    function showArticleList($topicURL, $pt){

        $scrapLink = $topicURL; 
        # 'https://www.sciencenews.org//search?tt=78';      
        
        $html = file_get_html($scrapLink);
        $tries = 0;
        while (!$html && $tries<=10){
            $html = file_get_html($scrapLink);
            sleep(1);
            $tries++;
        }
        
        if($html !== false){
            foreach ($html->find('div[id=region-content]') as $mainContent){
                #echo $mainContent->class;
                $lists = $mainContent->find('div[class=view-content]'); #List with images and articles.
                $element = $lists[1]->firstChild()->firstChild();# UnorderedList of articles in category
                #echo $element->outertext;
                # Strip elements of the website's divs
                $articles = getArticlesOnTopic($element);
                if($articles == ''){
                    continue;
                }
                foreach($articles as $article){
                    
                    $title = changeAtag($article[0]);
                    $row = '<div class="row">' . $title
                            . $article[1]->innertext . '</div>';
                    $rows[] = $row;
                }
                    
            }
            $mainContainer = MAIN_CONTAINER;
            foreach($rows as $row){
                $mainContainer = $mainContainer . $row;
            }
            $mainContainer = $mainContainer . '</div>';
            $headCt = MAIN_CONTAINER . '<h4>News on ' . $pt . '   <a href="' . $topicURL . '">(Source)</a>'
                                     . '</h4>' . '</div>';
            
            echo $headCt . $mainContainer;
            }
        }
        
    function changeAtag($element){
        $scienceNewsURL = 'https://www.sciencenews.org/';
        $fullLink = $scienceNewsURL . $element->href;
        $newTag = '<h5>' . '<a href="articleText.php?article=' 
                    . rawurlencode($fullLink) . '">'
                        . $element->plaintext . '</a>' . '</h5>';
        return $newTag;
    }
    
    
    
?>
 </body>
</html>   