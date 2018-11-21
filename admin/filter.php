<?php
include_once "../connectMysql.php";
include_once "./outputReview.php";

function generationArrForCheck () {
    $ifName = $_REQUEST['name'] != '' ? true : false;
    $ifSurname = $_REQUEST['surname'] != '' ? true : false;
    $ifEmail = $_REQUEST['email'] != '' ? true : false; 
    $arr;

    if ($ifName == 'true') {
        $arr['name'] = $_REQUEST['name'];
    }
    if ($ifSurname == 'true') {
        $arr['surname'] = $_REQUEST['surname'];
    }
    if ($ifEmail == 'true') {
        $arr['email'] = $_REQUEST['email'];
    } 

    return $arr;
}

function generationSQL ($mySQL) {
    if (isset($_REQUEST['doGo'])) {
        $arr = generationArrForCheck();
        $countArr = count($arr);
        $i = 0;
        $sqlUser = "SELECT * FROM `data_users` WHERE";
        foreach ($arr as $key => $value) {
            if ($i == $countArr - 1) {
                $sqlUser .= " `$key` = '$value'";
            } else {
                $sqlUser .= " `$key` = '$value' &&";
            }
            ++$i;
        }
        $dateDesc = $_REQUEST['date'] == 'on' ? 'DESC' : '';
        $sqlReviews ="SELECT * FROM `data_reviews` ORDER BY `date` $dateDesc";
        $resUser = $mySQL -> query($sqlUser);
        $resReviews = $mySQL -> query($sqlReviews);
        return array($resUser, $resReviews);
    }
    
}

function rowUserCount ($resUser,  $mysqli) {
    while ($rowUser = $resUser -> fetch_assoc()) {
        $arr[] = array('id' => $rowUser['id'], 'name' => $rowUser['name'], 'surname' => $rowUser['surname'], 'email' => $rowUser['email']);
    }
    return $arr;
}

function outputFilter ($arrRes, $mySQL) {
    $arrUser = rowUserCount ($arrRes[0], $mysqli);
    while($rowReview = $arrRes[1] -> fetch_assoc()) {
        for ($i = 0; $i < count($arrUser); ++$i) {
            if($arrUser[$i]['id'] == $rowReview['id_user']) {
                outReview($rowReview, $arrUser[$i]);
            }
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="./index.php">Все отзывы</a></li>
                <li><a href="./filter.php">фильтр</a></li>
            </ul>
        </nav>
    </header>
    <form action="<?= $_SERVER['SCRIPT_NAME']?>">
        <p>Имя: <input type="text" name="name" id=""> Фамилия:<input type="text" name="surname" id=""></p>
        <p>Еmail: <input type="text" name="email" id=""></p>
        <p>По времени^<input type="checkbox" name="date" id=""></p>
        <input type="submit" name="doGo" value="Применить">
    </form>
    <?php 
    if(isset($_REQUEST['doGo'])) {
        outputFilter(generationSQL($mysqli), $mysqli);
    } 
    ?>
</body>
</html>