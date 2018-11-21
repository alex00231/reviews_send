<?php 
$servername = "127.0.0.1";
$username = "root";
$password = "";
$BDname = "reviews";

$mysqli = new mysqli($servername, $username, $password, $BDname);

if ($mysqli->connect_error) {
    printf("Соединение не удалось: %s\n", $mysqli->connect_error);
    exit();
}

$date = date("Y-m-d");

$nameInput = $_REQUEST['name'] != "" ? ", '". $_REQUEST['name'] ."'" : "";
$surnameInput = $_REQUEST['surname'] != "" ? ", '". $_REQUEST['surname'] ."'" : "";
$emailInput = $_REQUEST['email'] != "" ? $_REQUEST['email'] : false;
$textInput = $_REQUEST['text'] != "" ? $_REQUEST['text'] : false;

$ifName = $_REQUEST['name'] != "" ? ", `name`" : "";
$ifSurname = $_REQUEST['surname'] != "" ? ", `surname`" : "";

// function send_mail() {

// }

if ($emailInput != false && $textInput != false) {
    $checkEmail = $mysqli -> query("SELECT * FROM `data_users` WHERE `email` = '$emailInput'");
    if ($checkEmail -> num_rows > 0) {
        $arr = $checkEmail -> fetch_assoc();
        $nameArr = $arr['name'];
        $surnameArr = $arr['surname'];
        if ($nameArr !== $nameInput && $nameInput !== "") {
            $updateNameSQL = "UPDATE `data_users` SET `name` = '$nameInput' WHERE `email` = '$emailInput'";
            $mysqli -> query($updateNameSQL);
        }
        if ($surnameArr !== $surnameInput && $surnameInput !== "") {
            $updateSurnameSQL = "UPDATE `data_users` SET `surname` = '$surnameInput' WHERE `email` = '$emailInput'";
            $mysqli -> query($updateSurnameSQL);
        }
        $user_id = $arr['id'];
        $sql = "INSERT INTO `data_reviews` (`review`, `date`, `id_user`) VALUES ('$textInput', '$date', '$user_id')";
        $mysqli -> query($sql);
    } else if ($checkEmail -> num_rows == 0) {
        $userAddInSQL = "INSERT INTO `data_users` (`email`$ifName $ifSurname) VALUES ('$emailInput'$nameInput $surnameInput)";
        $mysqli -> query($userAddInSQL);
        $idNewUserSQL = "SELECT `id` FROM `data_users` WHERE `email` = '$emailInput'";
        $checkID = $mysqli -> query($idNewUserSQL);
        if ($checkID -> num_rows == 1) {
            $user_id = $checkID -> fetch_assoc();
            $ID = $user_id['id'];
            $newReviewSQL = "INSERT INTO `data_reviews` (`review`, `date`, `id_user`) VALUES ('$textInput', '$date', '$ID')";
            $mysqli -> query($newReviewSQL);
        } else {
            echo "Произошла ощибка";
        }
    }
}

// $mysqli -> 


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>">
        <p>Имя: <input type="text" name="name" id=""></p>
        <p>Фамилия: <input type="text" name="surname" id=""></p>
        <p>EMail: <input type="email" name="email" id=""> <samp style="color:red">*</samp></p>
        <textarea name="text" id="" cols="40" rows="5"></textarea> <samp style="color:red">*</samp></p>
        <p><input type="submit" value="Отправить данные" name="doGo"></p>
    </form>
</body>
</html>