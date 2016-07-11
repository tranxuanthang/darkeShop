<?php
include 'inc/mysql.php';
include 'inc/func.php';

$id = intval($_GET['id']);
$sql = "SELECT * FROM 3dsgames WHERE `id`='$id'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    
    $title = $result->fetch_assoc();
    $pagename = $title['name'];
    include 'inc/header.php';
    echo search();
    echo '<h1>'.$title['name'].'</h1>';
    echo '<div class="row"><div class="eight columns">';
    echo '<h4>region:</h4> <p>'.$title['region'].'</p>';
    echo '<h4>description:</h4> <p>'.$title['gdesc'].'</p>';
    echo '<h4>titleID:</h4> <p>'.$title['titleID'].'</p>';
    echo '<h4>contentID (for grab metadata):</h4> <p>'.$title['contentID'].'</p>';
    echo '<h4>type:</h4> <p>'.$title['type'].'</p>';
    echo '<h4>size:</h4> <p>'.kichthuoc($title['size']).'</p>';
    
    
    echo '<h4>icon:</h4>';
        if(empty($title['imgur_iconURL'])){
                if($title['iconURL']!='none')$urlicon = getimgurimg($title['iconURL']); else $urlicon='none';
                $mysqli->query("UPDATE `3dsgames` SET `imgur_iconURL`='".$urlicon."' WHERE `id`='".$id."'");
                if(empty($urlicon) || $urlicon=='none') {
                    echo '<p>none</p>';
                } else {
                echo ' <p><img src="'.$urlicon.'" alt="'.$title['name'].'" /></p>';
                }
            }elseif ( $title['imgur_iconURL']=='none'){
                echo '<p>none</p>';
            }else{
                echo ' <p><img src="'.$title['imgur_iconURL'].'" alt="'.$title['name'].'" /></p>';
            }
    echo '<h4>thumb:</h4>';

        if(empty($title['imgur_thumb'])){
            if($title['thumb']!='none')$urlthumb = getimgurimg($title['thumb']); else $urlthumb='none';
            $mysqli->query("UPDATE `3dsgames` SET `imgur_thumb`='".$urlthumb."' WHERE `id`='".$id."'");
            if(empty($urlthumb) || $urlthumb=='none') {
                echo '<p>none</p>';
            } else {
            echo ' <p><img src="'.$urlthumb.'" alt="'.$title['name'].'" /></p>';
            }
        }elseif ( $title['imgur_thumb']=='none'){
            echo '<p>none</p>';
        }else{
            echo ' <p><img src="'.$title['imgur_thumb'].'" alt="'.$title['name'].'" /></p>';
        }

    
    
    echo '</div><div class="four columns">';
    $filename = $title['name'].' '.$title['region'];
    $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
    $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
    echo '<h4>download (custom funkeycia) (windows only):</h4><p><a href="funkeycia.php?id='.$title['id'].'">funkeycia-'.$title['titleID'].'.py</a></p>
    <p>You need to download <a href="https://www.python.org/">python 2.7</a> first, then open .py file with <b><i>C:\python27\python.exe</i></b> by default. The second time just double click .py file to run. The script will delete unnecessary directories (raw folder) and files (make_cdn_cia.exe, enctitlekeys.bin, include .py file itself) after finished. Downloaded and builded cia file is inside "cia/" folder with filename: <b><i>'.$filename.' ('.$title['titleID'].').cia</i></b>.</p>';
    $qrurl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode('https://3ds.titlekeys.com/ticket/'.$title['titleID']);
    echo '<h4>download (qrcode for fbi):</h4><p><img src="'.$qrurl.'" alt="'.$title['name'].'" style="max-width: 100%;height: auto;" /></p>';
    echo '</div></div>';        

    echo '<h4>screenshots:</h4>';
    if($title['scrcount']==-1 || $title['scrcount']==0){echo '<p>none</p>';} else {
        echo '<p><div class="row">';
        $sql = "SELECT * FROM screenshots WHERE `idgame`='".$title['titleID']."'";
        $scrresult = $mysqli->query($sql);
        while($scrsht = $scrresult->fetch_assoc()) {
            
            if(empty($scrsht['imgur_urltop']) || empty($scrsht['imgur_urlbottom'])){
            if($scrsht['urltop']!='none')$urltop = getimgurimg($scrsht['urltop']); else $urltop='none';
            if($scrsht['urlbottom']!='none')$urlbottom = getimgurimg($scrsht['urlbottom']); else $urlbottom='none';
            $mysqli->query("UPDATE `screenshots` SET `imgur_urltop`='".$urltop."', `imgur_urlbottom`='".$urlbottom."' WHERE `id`='".$scrsht['id']."'");
                if(empty($urltop) || empty($urlbottom) || $urltop=='none' || $urlbottom=='none') {
                    echo '<p>none</p>';
                } else {
                    echo '<div class="one-third column" style="text-align: center;"><img src="'.$urltop.'" alt="'.$title['name'].'" style="max-width: 100%;height: auto;" /><br><img src="'.$urlbottom.'" alt="'.$title['name'].'" style="max-width: 80%;height: auto;" /></div>';
                }
            }elseif ( $scrsht['imgur_urltop']=='none' || $scrsht['imgur_urlbottom']=='none'){
                echo '<p>none</p>';
            }else{
                echo '<div class="one-third column" style="text-align: center;"><img src="'.$scrsht['imgur_urltop'].'" alt="'.$title['name'].'" style="max-width: 100%;height: auto;" /><br><img src="'.$scrsht['imgur_urlbottom'].'" alt="'.$title['name'].'" style="max-width: 80%;height: auto;" /></div>';
            }
            
            
        }
        echo '</div></p>';
    }
    
    $count = $title['count'];
    $updatecount = $count+1;
    $mysqli->query("UPDATE `3dsgames` SET `count`='".$updatecount."' WHERE `id`='".$id."'");
} else {
    include 'inc/header.php';
    echo "0 results";
}
$mysqli->close();
include 'inc/footer.php';
?>