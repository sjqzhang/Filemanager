<?php



require_once('doc2md.php');


function mkdirs($dir){
	return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir,0777));
}


$h2md=new Html2md();


$action= $_REQUEST['action'];

$filepath='/var/www/webtech/doc/';

if($action=='load'){

	$html=$h2md->url_get($_REQUEST['url']);
	$selector=$_REQUEST['selector'];
	$istable=$_REQUEST['istable'];

	$title=$h2md->get_title($html);

    $doc=phpQuery::newDocument($html);

    $codes=pq('.code,.codebody,.bodycode');
    foreach($codes as $code){

        $htmlcode=pq($code)->htmlOuter();
        pq($code)->replaceWith("<pre>".$htmlcode."</pre>");

    }


  //  $html=pq('')->htmlOuter();

   // echo $html;die;

   // phpQuery::unloadDocuments($doc);
   // phpQuery::newDocument($html);

    //echo $html;die;

	$content= pq($selector)->htmlOuter();


    //echo $content;die;

	$md= $h2md->parse($content,$istable);
	$options= $h2md->get_container($html);

	//print_r($selector);



	echo json_encode(array('md'=>$md,'selector'=>$options,'title'=>$filepath.$title));

} else {

	$filename=$_REQUEST['filename'];
	if(!is_dir(dirname($filename))){
		mkdirs(dirname($filename));
	}

	$md=$_REQUEST['md'];

	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$filename=iconv('utf-8//IGNORE','gbk//IGNORE',$filename);
	}

	if(!file_exists($filename)){
		$fp=fopen($filename,'w');
		fwrite($fp,$md);
		fclose($fp);
		echo 1;
	} else {
		echo 0;
	}


}



?>
