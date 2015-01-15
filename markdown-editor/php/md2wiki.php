<?php


class Md2Wiki{

    function parse_block($match){

        return '<pre>'.$match[2].'</pre>';

    }

    function parse_img($match){

        return preg_replace('/^\s*\!/','',$match[0])."\r\n";

    }

    function parse_table($match){
        $lines= preg_split('/[\r\n]+/',$match[0]);
        $table="\r\n{|style=\"BORDER-COLLAPSE: collapse\" border=1px borderColor=#000000";
        for($i=0;$i<count($lines);$i++){

            if($i!=2&&trim($lines[$i])!=''){
                $table.="\r\n|-----------------------------------------------------------------------";
                $lines[$i]=preg_replace(
                    array(
                        '/\|$/',
                        '/\|/',
                        '/^\|\|/',
                    ),
                    array(
                        '',
                        '||',
                        '| ',
                    ),$lines[$i]
                );
                $table.="\r\n".$lines[$i];

            }

        }
        $table.="\r\n|}\r\n";
        return $table;
    }

    function trim_space($md){
        $lines= preg_split('/[\r\n]/',$md);
        $txt='';
        foreach($lines as $line){
            $line=trim($line);
            if($line==''){
                $txt.="\r\n";
            } else {
                $txt.=trim($line)."\r\n";
            }
        }
        return preg_replace('/(\r\n){4,}/',"\r\n\r\n",$txt);
    }

    function parse($md){

        $md= preg_replace('/^(#{1,6})([\s\S]*?)[\r\n]$/m',"=== $2 ===",$md);
        $md= preg_replace_callback('/[\r\n]{1,}(?:^\|[\s\S]+?\|[\r\n]$){1,}[\r\n]{2,}/m',array($this,'parse_table'),$md);
        $md= preg_replace_callback('/^\s*!\[[^\)]+\)/m',array($this,'parse_img'),$md);
        $md= preg_replace_callback('/(```)([\s\S]*?)(```)/',array($this,'parse_block'),$md);
        $md=$this->trim_space($md);
        return $md;

    }

}




?>

