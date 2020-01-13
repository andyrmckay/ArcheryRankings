<?php
$serverName = "";
$userName = "";
$password = "";
$dbName = "rankings";
$con = new mysqli($serverName, $userName, $password, $dbName);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
