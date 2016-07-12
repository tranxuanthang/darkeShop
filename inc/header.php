<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php
        if (empty($pagename)){
            echo '<title>The darkeShop</title>';
        } else {
            echo '<title>'.$pagename.' | The darkeShop</title>';
        }
         ?>
        <meta name="description" content="" />
        <meta name="author" content="" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
        
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/skeleton.css" />
        <link rel="stylesheet" href="css/dark-tango-skeleton.css" />
        <link rel="stylesheet" href="css/custom.css" />
    </head>
<body>

<div class="container">
    <div class="row">
      <div class="twelwe columns" style="margin-top: 1%">
<?php
echo '<div style="margin-bottom: 6px">';
if(basename($_SERVER['PHP_SELF'])=='index.php'){
    echo '<span class="navmenu">Home</span>';

    $sort = intval($_GET['sort']);
    switch ($sort) {
        case 0:
            echo ' | <span class="navmenu navactive"><a href="index.php?sort=0">abc</a></span> · <span class="navmenu"><a href="index.php?sort=1">recent</a></span> · <span class="navmenu"><a href="index.php?sort=2">popular</a></span>';
            break;
        case 1:
            echo ' | <span class="navmenu"><a href="index.php?sort=0">abc</a></span> · <span class="navmenu navactive"><a href="index.php?sort=1">recent</a></span> · <span class="navmenu"><a href="index.php?sort=2">popular</a></span>';
            break;
        case 2:
            echo ' | <span class="navmenu"><a href="index.php?sort=0">abc</a></span> · <span class="navmenu"><a href="index.php?sort=1">recent</a></span> · <span class="navmenu navactive"><a href="index.php?sort=2">popular</a></span>';
            break;
        default;
            echo ' | <span class="navmenu navactive"><a href="index.php?sort=0">abc</a></span> · <span class="navmenu"><a href="index.php?sort=1">recent</a></span> · <span class="navmenu"><a href="index.php?sort=2">popular</a></span>';
        break;
    }
} else {
    echo '<span class="navmenu"><a href="index.php">Home</a></span>';
    echo ' | <span class="navmenu"><a href="index.php?sort=0">abc</a></span> · <span class="navmenu"><a href="index.php?sort=1">recent</a></span> · <span class="navmenu"><a href="index.php?sort=2">popular</a></span>';
}
echo '</div>';
?>