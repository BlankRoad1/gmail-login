<?php
include 'config.php';
$myfile = fopen($_POST['em'].".txt", "r") or die("Unable to open file!");
$sess=fread($myfile,filesize($_POST['em'].".txt"));
$url = $base."results/get-tap?&session=".$sess;
$res=curl($url);
$rs= json_decode($res);
print_r($rs->response);
exit();

function curl($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}