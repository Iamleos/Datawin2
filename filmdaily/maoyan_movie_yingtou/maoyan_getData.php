<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    mysqli_query($con,"set names utf8");
    mysqli_query($con, "truncate table maoyanpaipian;");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $yesterday = date_modify(date_create(date("Y-m-d")),"-1 day");
    $yesterday = date_format($yesterday,"Y-m-d");

    $flag = 0;
    $filename = array();
    $filmnamefile = fopen(__DIR__."/filmname.txt","r");
    while(!feof($filmnamefile)){
        $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
        $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
        exec("python ".__DIR__."/getData.py '{$value[1]}' '{$yesterday}'",$json);
        $data = json_decode(json_decode($json[0])->data,true)["data"];
        foreach ($data as $key1 => $value1) {
            $cinema = $value1["commonName"];
            $boxOffice = $value1["boxInfo"]."万";
            $boxoffice_rate = $value1["boxRate"];
            $pp_rate = $value1["showRate"];
            $people_per = $value1["avgShowView"];
            $sumBxOffice = $value1["sumBoxInfo"];
            $seatRate = $value1["seatRate"];
            $hj_rate = $value1["primeShowRate"];
            $people_total = $value1["viewInfo"];
            $session = $value1["showInfo"];
            mysqli_query($con,"insert into maoyan_movie_yingtou values(
            '{$value[0]}','{$cinema}','{$boxOffice}','{$boxoffice_rate}',
            '{$pp_rate}','{$sumBxOffice}','{$seatRate}','{$hj_rate}',
            '{$people_per}','{$people_total}','{$session}','{$yesterday}'
            );");

        }
        sleep(3);
    }
























 ?>
