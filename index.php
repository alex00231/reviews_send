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

$ifUser = array(
    'name' => $_REQUEST['name'] != "" ? ", `name`" : "",
    'surname' => $_REQUEST['surname'] != "" ? ", `surname`" : ""
);

$arrInputUser = array(
    'name' => $_REQUEST['name'] != "" ? $_REQUEST['name'] : false,
    'surname' => $_REQUEST['surname'] != "" ? $_REQUEST['surname'] : false,
);

$arrInputSet = array(
    'email' => $_REQUEST['email'] != "" ? $_REQUEST['email'] : false,
    'text' => $_REQUEST['text'] != "" ? $_REQUEST['text'] : false
);
// 'email' => $_REQUEST['email'] != "" ? $_REQUEST['email'] : false,
// 'text' => $_REQUEST['text'] != "" ? $_REQUEST['text'] : false

print_r($arrInput);

function generationSQL ($ifUser, $arrInputUser, $arrInputSet) {
    $countArr = count($arrInputUser);
    $sqlUser = "INSERT INTO `data_users` (`email`{$ifUser['name']} {$ifUser['surname']}) VALUES ('{$arrInputSet["email"]}'";
    foreach ($arrInputUser as $key => $value) {
        if ($value != false) {
            $sqlUser .= ", '$value'";
        }
        ++$i;
    }
    $sqlUser .= ")";
    return $sqlUser;
}

if ($arrInputSet['email'] != false && $arrInputSet['text'] != false) {
    $checkEmail = $mysqli -> query("SELECT * FROM `data_users` WHERE `email` = '{$arrInputSet["email"]}'");
    if ($checkEmail -> num_rows > 0) {
        $arr = $checkEmail -> fetch_assoc();
        $nameArr = $arr['name'];
        $surnameArr = $arr['surname'];
        if ($nameArr != $nameInput && $arrInputUser['name'] != false) {
            $updateNameSQL = "UPDATE `data_users` SET `name` = '{$arrInputUser["name"]}' WHERE `email` = '{$arrInputSet["email"]}'";
            $mysqli -> query($updateNameSQL);
        }
        if ($surnameArr !== $surnameInput && $arrInputUser['surname'] != false) {
            $updateSurnameSQL = "UPDATE `data_users` SET `surname` = '{$arrInputUser["surname"]}' WHERE `email` = '{$arrInputSet["email"]}'";
            $mysqli -> query($updateSurnameSQL);
        }
        $user_id = $arr['id'];
        $sql = "INSERT INTO `data_reviews` (`review`, `date`, `id_user`) VALUES ('{$arrInputSet["text"]}', '$date', '$user_id')";
        $mysqli -> query($sql);
    } else if ($checkEmail -> num_rows == 0) {
        $userAddInSQL = generationSQL($ifUser, $arrInputUser, $arrInputSet);
        $mysqli -> query($userAddInSQL);
        $idNewUserSQL = "SELECT `id` FROM `data_users` WHERE `email` = '{$arrInputSet["email"]}'";
        $checkID = $mysqli -> query($idNewUserSQL);
        if ($checkID -> num_rows == 1) {
            $user_id = $checkID -> fetch_assoc();
            $ID = $user_id['id'];
            $newReviewSQL = "INSERT INTO `data_reviews` (`review`, `date`, `id_user`) VALUES ('{$arrInputSet["text"]}', '$date', '$ID')";
            $mysqli -> query($newReviewSQL);
        } else {
            echo "Произошла ощибка";
        }
    }
}

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