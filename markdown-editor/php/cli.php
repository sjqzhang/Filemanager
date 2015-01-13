<?php



include_once(dirname(__FILE__).'/doc2md.php');


$options= get_argv();


var_dump($options);


function get_argv(){

    $ops="u:c:s:d:t:";
    $options= getopt($ops,array());
    return $options;

}



function help(){

    $help=<<<eot

u: url
c: url container
s: content selector

eot;

    echo $help;

}


function get_urls($options){
    $urls=array();
    $md=new Html2md();
    $html= $md->url_get($options['u']);
    phpQuery::newDocument($html);
    foreach( pq($options['c'])->find("a") as $a){
       // echo pq($a)->attr("href");
       $href= pq($a)->attr("href");
       $title= pq($a)->text();
       array_push($urls,array('href'=>$href,'title'=>$title));
    }
    return $urls;
}



$urls=get_urls($options);


function download($url, $options){
    print_r($url);
    $md=new Html2md();
    $html= $md->url_get($url['href']);
    phpQuery::newDocument($html);

    $codes=pq('.code,.codebody,.bodycode,.example_code');
    foreach($codes as $code){

        $htmlcode=pq($code)->htmlOuter();
        pq($code)->replaceWith("<pre>".$htmlcode."</pre>");

    }

    $content= pq($options['s'])->htmlOuter();

    if(isset($options['t'])){
        $istable=true;
    } else {
        $istable=false;
    }
    $markdown= $md->parse($content,$istable);

    if(isset( $options['d'])){

      $filename=$options['d'].'/'.trim($url['title']).".md";


       $fp= fopen($filename,"w");

       fwrite($fp,$markdown);

       fclose($fp);
    }

    //echo $markdown;
}



foreach($urls as $url){

    download($url,$options);

}



?>
