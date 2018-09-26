<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 25.10.2017
 * Time: 13:20
 */



$id = $_GET['id'];
// do some validation here to ensure id is safe

echo $id;






$link = mysqli_connect("localhost", "root", "");
mysqli_select_db("uczniowie");





$sql = "SELECT zdjecie FROM zdjecia WHERE id=$id";
$result = mysqli_query("$sql");
$row = mysqli_fetch_assoc($result);
mysqli_close($link);

header("Content-type: image/jpeg");
echo $row['zdjecie'];