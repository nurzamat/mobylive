<?php // rnheader.php
include 'functions.php';
session_start();
$loggedin = FALSE;

if (isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin = TRUE;
}
else $user = null;

?>