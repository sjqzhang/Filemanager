<?php



$filepath= $_REQUEST['filepath'];
$action= $_REQUEST['action'];



function read($filepath){

    $fp=fopen($filepath,'r');
    $content=fread($fp,filesize($filepath));
    fclose($fp);
    return $content;

}

function mkdirs($dir){
        return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir,0777));
}

function write($filepath,$content){



   $dir=  dirname($filepath);

   if(is_dir($dir)){
     mkdirs($dir);
   }

    $fp=fopen($filepath,'w');
    return fwrite($fp,$content);
    fclose($fp);
}


function export($filepath, $html){
    $dir= dirname(__FILE__);
    $tmpfile=tmpfile();
    $tpl= read($dir."/../export.html");
    $tpl= preg_replace('/\{html\}/',$html,$tpl);
    Header("Content-type: application/octet-stream");
    Header("Accept-Ranges: bytes");
    Header("Accept-Length: ".strlen($tpl));
    //Header("Content-Disposition: attachment; filename=".basename($filepath));
    Header("Content-Disposition: attachment; filename="."导出.html");

    echo $tpl;

}


if($action=='load'){

    echo read($filepath);

} else if($action=='save') {

    $md=$_REQUEST['md'];

   $wc=write($filepath,$md);

   echo json_encode(array('md'=>$md,'wc'=>$wc));

} else if($action=='export'){



    $html=$_REQUEST['html'];

    export($filepath,$html);


}

