<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$BDname = "reviews";

$mysqli = new mysqli($servername, $username, $password, $BDname);

if ($mysqli -> connect_error) {
    printf("Соединение не удалось: %s\n", $mysqli->connect_error);
    exit();
}

?>