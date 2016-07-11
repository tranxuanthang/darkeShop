<?php
include 'inc/mysql.php';
include 'inc/func.php';
$sonews = 100;
$list = $mysqli->query("SELECT id FROM 3dsgames WHERE type='eshop' OR type='dlc' OR type='update' OR type='dsiw'");  
$tongsodong = $list->num_rows;
$tongsotrang = ceil($tongsodong / $sonews);
if(isset($_GET["page"])){
    if($_GET['page'] > $tongsotrang) $_GET['page'] = $tongsotrang;
    $p = intval($_GET["page"]) ;
}else{
    $p =1;
}
$x = ($p-1) * $sonews;

include 'inc/header.php';

$sql = "SELECT id, name, titleID, region, type, imgur_iconURL FROM 3dsgames WHERE type='eshop' OR type='dlc' OR type='update' OR type='dsiw' ORDER BY name ASC limit $x,$sonews";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo search();
    // output data of each row
    echo '<div class="row"><div class="nine columns">';
    while($row = $result->fetch_assoc()) {
        if(!empty($row['imgur_iconURL']) && $row['imgur_iconURL']!='none')$imgur_iconURL = $row['imgur_iconURL']; else $imgur_iconURL = 'img/questionmark.png';
        echo '<div class="row listing"><div class="eight columns"><span style="margin-right: 4px;"><img style="vertical-align:top" src="'.$imgur_iconURL.'" width="32" alt="'.$row['name'].'" /></span><a href="title.php?id='.$row['id'].'">'.$row['name'].'</a></div><div class="two columns"> <span style="color: green;">['.$row['region'].']</span></div><div class="two columns"> <span style="color: blue;">['.$row['type'].']</span></div></div>';
    }
    echo '</div><div class="three columns">';
    
    if($p > 1){
        $page = $p - 1;
        $prev = '<a class="button button-small button-primary" href="?page='.$page.'">« Prev</a> ';
    }else{
        $prev  = '<button class="button button-small" disabled>« Prev</button> ';
    }
    if ($p < $tongsotrang){
        $page = $p + 1;
        $next = ' <a class="button button-small button-primary" href="?page='.$page.'">Next »</a> ';   
    } else{
        $next = ' <button class="button button-small" disabled>Next »</button>';
    }
    
    if($_GET['page'] < 1 || $_GET['page'] == '') $_GET['page'] = 1;
    echo "<div class=\"menu\">$prev $next<br />";
    
    chiatrang($tongsotrang,$p,'?page=');
    echo '<p>'.$tongsodong.' titles in db<br>';
    $timequery = "SELECT time FROM 3dsgames ORDER BY time DESC LIMIT 1";
    $timequeryexecute = $mysqli->query($timequery);
    $timerow = $timequeryexecute->fetch_assoc();
    $time = strtotime($timerow['time']);
    $timeconvert = date("m/d/Y g:i A", $time);
    echo 'Latest title updated: '.$timeconvert.'</p>';
    echo '</div></div>';
} else {
    echo "0 results";
}



$mysqli->close();
include 'inc/footer.php';
?>