<?php
class article
    {
        // declaração de propriedade
        private $url = ''; # Link to the full article
        private $category = '1'; # Link to category search on the website
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
        
        public function getCategory(){
            return $this->category;
        }
        
        #generate HTML item to be printed
        # I realize I'm rebuilding tags I've destroyed before...
        public function toString(){
            $showCategory = '<a href="' . $this->category . '">' . $this->plainTextCategory . '</a>' ;
            $showAutor = $this->author;
            $info = array ($this->title, $this->url);
            $thisObjectSerial = serialize($info);
            # Passing through javascript would probably be better than using GET values here.
            $showTitle = '<a href="articleText.php?article=' . $thisObjectSerial . '">' . $this->title . '</a>';
            $articleDiv = '<div class="articleBox four columns">' . $showTitle . 
                            '<br>' . $showAutor . '|' . $this->datePublished .
                                '<br>'
                                  . '</div>';
            
            return $articleDiv;
        }
        
        public function getArticleDOM (){
            return str_get_html($this->toString());
        }
        
        # Get href value from an aTag.
        public function retrieveHref($aTag){
            $text = $aTag->outertext;
            $str1 = explode('href="',$text);
            $str2 = explode ('"',$str1[1]);
            return $str2[0];
        }
        
        # Divides subcategory and category string, returning the latter as plainText for array Indexing.
        # This 
        private function explodeCategorySubcategory($cat){
            $fullCat = explode('|', $cat);
            $this->subCategory = $fullCat[0];
            
            $categoryAtag = str_get_html($fullCat[1]);
            $this->category = $this->retrieveHref($categoryAtag); #Link for the category search
            $this->plainTextCategory = $categoryAtag->plaintext; #Indexing string

        }
        
        private function setTitleURL($titleDate){
            #echo $titleDate;
            $tit = str_get_html($titleDate);
            #echo $tit; 
            $this->url = $this->retrieveHref($tit);
            #echo $this->url;
            $this->title = $tit->plaintext;
            #echo $this->title;
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
       }

?>

