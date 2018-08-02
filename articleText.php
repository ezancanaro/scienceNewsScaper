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
    
    $scrapLink = 'https://www.sciencenews.org//article/medical-mystery-reveals-new-host-rat-lungworm-parasite';
    $html = file_get_html($scrapLink);
    
    $title = 'Random Title For Now';
    $article = $_GET['article'];
    
    function removeNotTextual($fullText){
        $newText ='';
        $domText = str_get_html($fullText);
        foreach($domText->find('p') as $paragraph){
            $newText = $newText . $paragraph->outertext;
        }
        return $newText;
    }
    $myText = '';
    foreach($html->find('span') as $spanText){
        if ($spanText->itemprop == 'description'){
            $myText = $spanText->innertext;
            $myText = removeNotTextual($myText);  
        }
    }
    $mainContainer = '<div class="container">';
    $textContainer = $mainContainer . $myText . '</div>';
    $titleContainer = $mainContainer . '<h1>' . $title . '</h1>' . '</div>';
    echo $titleContainer;
    echo $textContainer;
?>
 </body>
</html>   