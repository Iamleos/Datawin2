<?php
    include "../lib_function/function.php";
    $con = getDB("filmdaily");
    $result = mysqli_query($con,"select mainname from dianyingzh;");
    $file = fopen(__DIR__."/dianyingzh.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }

    mysqli_select_db($con,"movie");
    $result = mysqli_query($con,"select movie from yien;");
    $file = fopen(__DIR__."/yien.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }

    $result = mysqli_query($con,"select movie from maoyan;");
    $file = fopen(__DIR__."/maoyan.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }



?>
