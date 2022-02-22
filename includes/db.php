<?php
session_start();

$configdb = parse_ini_file('db.ini');
$db = mysqli_connect('localhost', $configdb['username'], $configdb['password'], $configdb['db']);
if($db->connect_errno){
    die('Failed to connect to Database "proLearn"!');
    exit();
}
?>