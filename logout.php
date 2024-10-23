<?php

// start session
session_start();

// session for 
$_SESSION = array();


// session destroy here
session_destroy();


// redirect ho gya index.php
header("location: /index.php");

?>