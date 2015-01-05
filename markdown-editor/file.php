<?php



$filepath= $_REQUEST['filepath'];
$action= $_REQUEST['action'];



function read($filepath){

    $fp=fopen($filepath,'r');
    $content=fread($fp,filesize($filepath));
    fclose($fp);
    return $content;

}



function write($filepath,$content){

    $fp=fopen($filepath,'w');
    fwrite($fp,$content);
    fclose($fp);
}



if($action=='load'){

    echo read($filepath);

} else if($action=='save') {

    $md=$_REQUEST['md'];

    write($filepath,$md);

    echo $md;
}

