<?php
include 'inc/mysql.php';
include 'inc/func.php';
$sonews = 100;



if(isset($_GET['keyword'])){
    $tukhoa = stripslashes(htmlspecialchars($_GET['keyword']));
    $keyword = $mysqli->real_escape_string($_GET['keyword']);
    if ($tukhoa ==NULL or strlen($tukhoa)<4 OR strlen($tukhoa)>60 ){
        include 'inc/header.php';
echo search($tukhoa);
        echo '<p>keyword is too short (4 - 60 chars)!</p>';
        include 'inc/footer.php';
        exit;
    }
include 'inc/header.php';
$sql = "SELECT id,titleID,name,gdesc,region FROM `3dsgames` WHERE MATCH (`name`,`gdesc`) AGAINST (CONVERT('$keyword' USING utf8)) AND (type='eshop' OR type='dlc' OR type='update' OR type='dsiw')";
$list = $mysqli->query($sql);

$tongsodong = $list->num_rows;
$tongsotrang = ceil($tongsodong / $sonews);
//echo $tongsotrang;
if(isset($_GET["page"])){
    if($_GET['page'] > $tongsotrang) $_GET['page'] = $tongsotrang;
    $p = intval($_GET["page"]) ;
}else{
    $p =1;
}
$x = ($p-1) * $sonews;

$sql = "SELECT id,name,gdesc,region,type,imgur_iconURL FROM `3dsgames` WHERE MATCH (`name`,`gdesc`) AGAINST (CONVERT('$keyword' USING utf8)) AND (type='eshop' OR type='dlc' OR type='update' OR type='dsiw') limit $x,$sonews";
$result = $mysqli->query($sql);
echo search($tukhoa);
if ($result->num_rows > 0) {
    // output data of each row
    echo '<div class="row"><div class="nine columns">';
    while($row = $result->fetch_assoc()) {
        if(!empty($row['imgur_iconURL']) && $row['imgur_iconURL']!='none')$imgur_iconURL = $row['imgur_iconURL']; else $imgur_iconURL = 'img/questionmark.png';
        echo '<div class="row listing"><div class="eight columns"><span style="margin-right: 4px;"><img style="vertical-align:top" src="'.$imgur_iconURL.'" width="32" alt="'.$row['name'].'" /></span><a href="title.php?id='.$row['id'].'">'.$row['name'].'</a></div><div class="two columns"><span style="color: green;">['.$row['region'].']</span></div><div class="two columns"> <span style="color: blue;">['.$row['type'].']</span></div></div>';
    }
    echo '</div><div class="three columns">';
    if($p > 1){
        $page = $p - 1;
        $prev = '<a class="button button-small button-primary" href="?keyword='.$tukhoa.'&page='.$page.'">« Prev</a> ';
    }else{
        $prev  = '<button class="button button-small" disabled>« Prev</button> ';
    }
    if ($p < $tongsotrang){
        $page = $p + 1;
        $next = ' <a class="button button-small button-primary" href="?keyword='.$tukhoa.'&page='.$page.'">Next »</a> ';   
    } else{
        $next = ' <button class="button button-small" disabled>Next »</button>';
    }
    
    if($_GET['page'] < 1 || $_GET['page'] == '') $_GET['page'] = 1;
    echo "<div class=\"menu\">$prev $next</div>";
    chiatrang($tongsotrang,$p,'?keyword='.$tukhoa.'&page=');
    echo '<p>found '.$tongsodong.' results.</p>';
    echo '</div></div>';
} else {
    echo "0 results";
}
} else {
    include 'inc/header.php';
    echo search();
    include 'inc/footer.php';
}

$mysqli->close();
?>