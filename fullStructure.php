<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <?php  
    include_once('simple_html_dom.php');
    
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
        
        $list = str_get_html('<div><\div>');
        $list->innertext = '<ul>';

        foreach ($element->find('span.field-content') as $content){
            if($content->find('a')){
                $content->outertext = '<li>' . $content->outertext . '</li>';   
                $list->innertext = $list->innertext . $content->outertext;
            }
        }
        foreach ($element->find('div.field-content') as $content){
            $content->outertext = '<li>' . $content->outertext . '</li>';
            
            $list->innertext = $list->innertext . $content->outertext;
        }
        
        
        
        $list->innertext = $list->innertext . '</ul>';
        return $list;
    }
    
    #base url for the website
    $scienceNewsURL = 'https://www.sciencenews.org/';
    $chosenTopic = chooseTopic();
     #Url containing news about the chosen topic
    $scrapLink = $scienceNewsURL . $chosenTopic;
    
    #Get the file from the web
    $html = file_get_html($scrapLink);
    #Check success
    
    if ($html !== false){
        foreach ($html->find('div') as $element){
            $att = $element->class;
            if($att == 'article-info'){
                replaceLinks($element, $scienceNewsURL);
            echo ('Featured article: ' . $element );    
            }
            if ($att == 'item-list'){
                replaceLinks($element, $scienceNewsURL);    
                $simplifiedElement = removeBloatDivs($element);
              
                echo $simplifiedElement->innertext;
                echo $element;
            }
                
            
        }
        
        /*
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        foreach ($dom->getElementsByTagName('div') as $node) {
            if ($node->getAttribute('class') == 'article-info')
                echo $node;
        }*/
    }
 ?>
 </body>
</html>