<?php 
include_once "../connectMysql.php";
include_once "./outputReview.php";

$sqlReviews = "SELECT * FROM `data_reviews` ORDER BY `date` DESC";
$resReviewsSQL = $mysqli -> query($sqlReviews);

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
            <li><a href="./index.php">Основное</a></li>
            <li><a href="./filter.php">фильтр</a></li>
        </ul>
    </nav>
</header>

<?php 
output($resReviewsSQL, $mysqli);
?>
</body>
</html>