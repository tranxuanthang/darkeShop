<?php
include 'inc/mysql.php';
include 'inc/func.php';
$id = intval($_GET['id']);
$sql = "SELECT * FROM 3dsgames WHERE `id`='$id'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $title = $result->fetch_assoc();
    $filename = $title['name'].' '.$title['region'];
    $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
    $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
    
    if(!file_exists('funkeycia/funkeycia-'.$title['titleID'].'.py')){
        $file_contents = file_get_contents('FunKeyCIA-template.txt');
        $file_contents = str_replace('[TITLEID_HERE]', $title['titleID'], $file_contents);
        $file_contents = str_replace('[NAME_REGION_HERE]', $filename, $file_contents);
        $file = fopen('funkeycia/funkeycia-'.$title['titleID'].'.py', 'w');
        //echo $file_contents;
        fwrite($file, $file_contents);
        fclose($file);
        header('Location: funkeycia/funkeycia-'.$title['titleID'].'.py');
    } else {
        header('Location: funkeycia/funkeycia-'.$title['titleID'].'.py');
    }
} else {
    echo "cant find anythin";
}
$mysqli->close();
?> 
</body>
</html>