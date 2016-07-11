<?php
include 'config.php';
// Create connection
$mysqli = new mysqli($servername, $username, $password);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$mysqli->query("SET NAMES 'utf8'");

/* change db to world db */
$mysqli->select_db($dbname);
?>