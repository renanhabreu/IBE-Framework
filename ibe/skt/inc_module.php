<?php

$path = 'application';

$zip = new ZipArchive;
if ($zip->open($path.'.zip') === true) {
    $dirname = 'tmp/'.date('dmyHis').'/';
    $zip->extractTo($dirname, array('IBE.ini'));

    $paths = parse_ini_file($dirname . 'IBE.ini');
    $not_paths = array();
    foreach($paths as $key=>$value){
            $ph = explode('.', $key);
            if($value != 'path'){
                $ph[sizeof($ph)- 1] .= '.'.$value;
            }else{
                $ph[sizeof($ph)- 1] .= '/';
            }
            $not_paths[] = $path.'/'.implode('/',$ph);
    }
    $all_paths = array();
    echo '<pre>';
    var_dump($not_paths);
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        echo '<b  style="color:blue">verificando: </b>'.$name.'<br />';
        if(array_search($name,$not_paths) && $name != "IBE.ini"){
            echo '<b   style="color:green">extraido: </b>'.$name.'<br />';
            $zip->extractTo('../../../' , array($name));
        }else{
            echo '<b   style="color:red">nao extraido: </b>'.$name.'<br />';
        }
    }
    $zip->close();
    unlink($dirname.'IBE.ini');
    rmdir($dirname);
}