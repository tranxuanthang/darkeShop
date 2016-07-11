<?php
include 'inc/mysql.php';
include 'inc/func.php';
include 'inc/header.php';
$curl = curl_init();
$options = array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_VERBOSE => 1);
curl_setopt_array($curl, $options);
$url = 'https://3ds.titlekeys.com/json';
curl_setopt($curl, CURLOPT_URL, $url);
$jsonlist = curl_exec($curl);
curl_close($curl);
//$jsonlist = file_get_contents('cron.txt');
$titlelist = json_decode($jsonlist, true);
$added = 0;
foreach ($titlelist as $value) {
	$titleID = $mysqli->real_escape_string($value['titleID']);
	$serial = $mysqli->real_escape_string($value['serial']);
	$name = $mysqli->real_escape_string($value['name']);
	$region = $mysqli->real_escape_string($value['region']);
	$size = $mysqli->real_escape_string($value['size']);

$sql = "SELECT titleID FROM 3dsgames WHERE `titleID`='".$titleID."'";


$result = $mysqli->query($sql);


if ($result->num_rows == 0 and !empty($name) and !empty($region)) {


    $types = array('eshop', 'sapp', 'sda', 'sapplet', 'smod', 'sfirm', 'down', 'dsisa', 'dsisda', 'dsiw', 'update', 'demo', 'dlc');
    $typeslower = array(
        array('00040000'), 
        array('00040010'), 
        array('0004001B', '000400DB', '0004009B'), 
        array('00040030'), 
        array('00040130'), 
        array('00040138'), 
        array('00040001'), 
        array('00048005'), 
        array('0004800F'), 
        array('00048000', '00048004'), 
        array('0004000E'), 
        array('00040002'), 
        array('0004008C')
    );
    for ($i=0; $i<count($types); $i++){
        if (in_array(strtoupper(substr($titleID, 0, 8)), $typeslower[$i] )){
            $type=$types[$i];
            break;
        }
    }

	#####
        $rgcode = array('USA'=>'US','JPN'=>'JP','EUR'=>'GB','ALL'=>'US');
        
        $curl = curl_init();
        $options = array(CURLOPT_SSLCERT => 'ctr-common-1.pem',
        CURLOPT_SSLKEY => 'ctr-common-1.key',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_VERBOSE => 1);
        curl_setopt_array($curl, $options);
        $url = 'https://ninja.ctr.shop.nintendo.net/ninja/ws/titles/id_pair?title_id[]='.$titleID ;
        curl_setopt($curl, CURLOPT_URL, $url);
        $xml = curl_exec($curl);
        //echo $xml;
        curl_close($curl);
        preg_match('/<ns_uid>(.*)?<\/ns_uid>/', $xml, $match);
        $contentID=$match[1];
        
        
        preg_match('/<code>(.*)?<\/code>/', $xml, $match2);
        if(empty($contentID) and $match2[1]=='5998'){
            echo 'eshop is maintaining';
            exit;
        }
        if(empty($match[1]))$contentID="none";
        $curl = curl_init();
        $options = array(CURLOPT_SSLCERT => 'ctr-common-1.pem',
        CURLOPT_SSLKEY => 'ctr-common-1.key',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_VERBOSE => 1);
        curl_setopt_array($curl, $options);
        $url = 'https://samurai.ctr.shop.nintendo.net/samurai/ws/'.$rgcode[$region].'/title/'.$contentID.'?shop_id=1';
        
        curl_setopt($curl, CURLOPT_URL, $url);
        $xml2 = curl_exec($curl);
        
        $metadata = XMLtoArray($xml2);
        
        
        $iconURL = $mysqli->real_escape_string($metadata['ESHOP']['TITLE']['ICON_URL']);
        $description = $mysqli->real_escape_string($metadata['ESHOP']['TITLE']['DESCRIPTION']);
        $screenshot = $metadata['ESHOP']['TITLE']['SCREENSHOTS']['SCREENSHOT'];
        $thumbnail = $mysqli->real_escape_string($metadata['ESHOP']['TITLE']['THUMBNAILS']['THUMBNAIL']['URL']);
        $countscrsht = count($screenshot);
        $stt = 0;
        for ($i = 0; $i <= $countscrsht-1; $i++) {
        
        $scrtop = $mysqli->real_escape_string($metadata['ESHOP']['TITLE']['SCREENSHOTS']['SCREENSHOT'][$i]['IMAGE_URL'][$stt]['content']);
        $scrbottom = $mysqli->real_escape_string($metadata['ESHOP']['TITLE']['SCREENSHOTS']['SCREENSHOT'][$i]['IMAGE_URL'][$stt+1]['content']);
        
        $mysqli->query("INSERT INTO `screenshots`(`idgame`, `urltop`, `urlbottom`,`stt`) VALUES ('".$titleID."','".$scrtop."','".$scrbottom."','".$i."')");
        $stt=$stt+2;
        }
        #####
        if($countscrsht==0){
        $scrcount=-1;
        }else {
        $scrcount = $countscrsht;
        }
        if(empty($iconURL))$iconURL='none';
        if(empty($description))$description='none';
        if(empty($thumbnail))$thumbnail='none';

        if(!empty($iconURL) and $iconURL!='none')$urlicon = getimgurimg($iconURL); else $urlicon='none';
        
        if(!empty($thumbnail) and $thumbnail!='none')$urlthumb = getimgurimg($thumbnail); else $urlthumb='none';
        
        if(empty($region))$region='unk';
        //echo $urlicon.'<br>'.$urlthumb.'<br>';
        $time = date("Y-m-d H:i:s");
        $query = "INSERT INTO `3dsgames`(`titleID`, `serial`, `name`, `region`, `size`, `contentID`, `iconURL`, `gdesc`, `thumb`,`type`,`scrcount`,`imgur_iconURL`,`imgur_thumb`,`time`) VALUES ('".$titleID."','".$serial."','".$name."','".$region."','".$size."','".$contentID."','".$iconURL ."','".$description ."','".$thumbnail."','".$type."','".$scrcount."','".$urlicon."','".$urlthumb."','".$time."')";
        $mysqli->query($query);
        if($mysqli->affected_rows==1)
        echo 'added: '.$titleID.' '.$name.'<br>';
        else
        echo 'failed: '.$titleID.' '.$name.'<br>';
        $added++;
        if ($added>=11){
            echo 'inc/footer.php';
            exit;
        }
    
    }
//sleep(0.5);
}

echo 'DONE';
echo 'inc/footer.php';
?> 