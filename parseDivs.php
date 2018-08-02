<?php
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
    
    function getArticlesOnTopic($element){
        #echo $element;
        
        foreach ($element->children() as $content){
            # <a> tag with title and url
            $articleInfo[0] = $content->children(1)->firstChild()->firstChild();
            # <p> tags with introductory paragraphs
            $articleInfo[1] = $content->children(2)->firstChild();
            $articles[]= $articleInfo;
        }    
        return $articles;
    }
    
    function removeBloatDivs2 ($element){
        $articleArray ='';
        foreach ($element->find('li.views-row') as $content){
            $content = $content->firstChild();
            if(!$content){
                continue;
            }
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
 ?>