<?php

function filterName ($row) {
    $arr = array(
        'name' => $row['name'] != '' ? $row['name'] : "Имя не записана",
        'surname' => $row['surname'] != '' ? $row['surname'] : "Фамилия не записана"
    );
    return $arr;
}

function outReview ($rowReview, $rowUser) {
    $arrUserName = filterName($rowUser);
    echo "Имя: ". $arrUserName['name'] ."; Фамилия: ". $arrUserName['surname'] ."; Email: ". $rowUser['email'] .";<br>
          Отзыв: ". $rowReview['review'] ."<br>
          Дата: ". $rowReview['date'] ."; ID: ". $rowReview['id'] ."<hr>";
}

function rowUser ($row, $mySQL) {
    $ID = $row['id_user'];
    $sqlUser = "SELECT * FROM `reviews`.`data_users` WHERE `id` = $ID";
    $resUserSQL = $mySQL -> query($sqlUser);
    $User = $resUserSQL -> fetch_assoc();
    return $User;
}

function output ($resSQL, $mySQL) {
    if ($resSQL -> num_rows > 0) {
        while($rowReview = $resSQL -> fetch_assoc()) {
            $rowUser = rowUser($rowReview, $mySQL);
            outReview($rowReview, $rowUser);
        } 
    }
}