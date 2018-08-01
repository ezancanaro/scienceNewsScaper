<html>
 <head>
  <title>Teste PHP</title>
  <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
  </head>
 <body>
 <a href="fullStructure.php"> Original </a>
 <?php  
    include_once('simple_html_dom.php');
    
    class article
    {
        // declaração de propriedade
        private $url = ''; # Link to the full article
        private $category = ''; # Link to category search on the website
        private $subCategory =''; # A string classifying the article
        private $title = ''; # Array with title string.
        private $author = ''; # <a> tag with link to author's search in website
        private $datePublished = ''; # Date of publication
        public $plainTextCategory =''; #Category in plain text, for indexing purposes
        
        public function setArticle($itsTitle, $itsAuthor, $itsCategory, $itsDate){
            $this->title = $itsTitle;
            $this->author = $itsAuthor;
            $this->category = $itsCategory;
            $this->datePublished = $itsDate;
        }
        
        #generate HTML item to be printed
        # I realize I'm rebuilding tags I've destroyed before...
        public function toString(){
            $showCategory = '<a href="' . $this->category . '">' . $this->plainTextCategory .'</a>';
            $showAutor = 'By ' . $this->author;
            $showTitle = '<a href="' . $this->url . '">' . $this->title . '</a>';
            $articleDiv = '<div class="articleBox">' . $showTitle . 
                            '<br>' . $showAutor . '|' . $this->datePublished .
                                '<br>' . $showCategory . '<br>' 
                                    . '</div>';
            
            return $articleDiv;
        }
        
        public function getArticleDOM (){
            return str_get_html($this->toString());
        }
        
        # Divides subcategory and category string, returning the latter as plainText for array Indexing.
        # This 
        private function explodeCategorySubcategory($cat){
            $fullCat = explode('|', $cat);
            $this->subCategory = $fullCat[0];
            
            $categoryAtag = str_get_html($fullCat[1]);
            $this->category = $categoryAtag->href; #Link for the category search
            $this->plainTextCategory = $categoryAtag->plaintext; #Indexing string

        }
        
        private function setTitleURL($titleDate){
            $tit = str_get_html($titleDate);
            $this->url = $tit->href;
            $this->title = $tit->plaintext;
        }
        
        private function setAuthorDate($authorDate){
           $trimBy = implode(explode('By ',$authorDate));
           $littleArray = explode('|',$trimBy);
           $authorAtag = str_get_html($littleArray[0]);
           $this->datePublished = $littleArray[1];
           $this->author = $authorAtag;
        }
        
        # Fills the attributes of an Article object through an array of html innerText
        public function fillAttributes($textArray){
            
           $this->explodeCategorySubcategory($textArray[0]);
           $this->setTitleURL($textArray[1]);
           $this->setAuthorDate($textArray[2]);
            
        }
        
        public function setField($intId, $cont){
            switch ($intId){
                case 0: $this->url = $cont;
                        return;
                case 1: $this->category = $cont;
                        return;
                case 2: $this->title = $cont;
                        return;
                case 3: $this->author = $cont;
                        return;

            }
        }
        
    // declaração de método
        public function displayVar() {
            echo $this->var;
        }
    }
    
    
    
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
            echo $articleLink . '<br>';
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
            echo $art;
          
        }
        
        
        
        #$list->innertext = $list->innertext . '</ul>';
        #return $list;
    }
    
    ini_set('max_execution_time',1000);#Unrealistic for my slow home connection. 
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
            if ($att == 'item-list'){
                replaceLinks($element, $scienceNewsURL);    
                $simplifiedElement = removeBloatDivs($element);
              
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