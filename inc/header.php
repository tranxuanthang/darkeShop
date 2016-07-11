<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <?php
        if (empty($pagename)){
            echo '<title>3ds games</title>';
        } else {
            echo '<title>'.$pagename.' | 3ds games</title>';
        }
         ?>
        <meta name="description" content="">
        <meta name="author" content="">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
        
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/skeleton.css">
        <link rel="stylesheet" href="css/custom.css">
    </head>
<body>

<div class="container">
    <div class="row">
      <div class="twelwe columns" style="margin-top: 1%">
<?php
if(basename($_SERVER['PHP_SELF'])=='index.php'){
    echo '<span style="margin: 6px; font-weight:bold; font-size:small; color: black">Home</span>';
} else {
    echo '<span style="margin: 6px; font-weight:bold; font-size:small"><a href="index.php">Home</a></span>';
}
?>