<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <?php  
    include_once('simple_html_dom.php');
    include_once('articleClass.php');
    error_reporting(E_ALL ^ E_WARNING);
    set_time_limit(90); #just in case
        
    define('MAIN_CONTAINER','<div class="container">');

    if( isset($_GET['article'])){
        $article = rawurldecode($_GET['article']);
        
        showArticleText($article);
    }else{
        showErrorPage();
    }
    
    function showErrorPage(){
        $errorContainer = MAIN_CONTAINER . '<a href="index.php">'
                                . '<h2> Something went wrong. Return to previous page </h2>'
                                    .'</a>' .   '<\div>';
        echo $errorContainer;
    }
    
    
    function removeNotTextual($fullText){
        $newText ='';
        $domText = str_get_html($fullText);
        foreach($domText->find('p') as $paragraph){
            $newText = $newText . $paragraph->outertext;
        }
        return $newText;
    }
    
    function showArticleText($article){
        
        $title = '';
        $scrapLink = $article;      
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $scrapLink);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
        $str = curl_exec($curl);
        curl_close($curl);
        $html = str_get_html($str);
        $tries = 0;
        while (!$html && $tries<=10){
            $html = file_get_html($scrapLink);
            sleep(1);
            $tries++;
        }
        if($html !== false){
            $myText = '';
            foreach($html->find('h1') as $header){
                if($header->itemprop == 'headline'){
                    $title = $header->plaintext;
                    break;
                }
            }
            foreach($html->find('span') as $spanText){
                if ($spanText->itemprop == 'description'){
                    $myText = $spanText->innertext;
                    $myText = removeNotTextual($myText);  
                    break;
                }
            }  
            $returnButton = '<a href="index.php"> Back </a>';
            $textContainer = MAIN_CONTAINER . $myText . $returnButton . '</div>';
            
            $titleContainer = MAIN_CONTAINER . '<h1>' . $title . '</h1>' . '</div>';
            echo $titleContainer;
            echo $textContainer; 
        }else{
            showErrorPage();
        }
         
    }
    
?>
 </body>
 <footer>
  <p>Eric Zancanaro: ericzanca@gmail.com</p>
</footer>
</html>   