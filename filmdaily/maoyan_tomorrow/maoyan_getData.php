<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $tomorrow = date_modify(date_create(date("Y-m-d")),"+1 day");
    $tomorrow = date_format($tomorrow,"Y-m-d");
    $con = getDB("filmdaily");
    mysqli_query($con, "truncate table maoyan_tomorrow;");

//通过xhr采集全国范围内前50影院数据

    exec("python ".__DIR__."/getData.py '{$tomorrow}'",$hj_json);
    $data = json_decode($hj_json[0])->data;
    preg_match_all('/<li class=\'c1 lineDot\'>(.*) <\/li>/',$data,$name);
    preg_match_all('/<li class="c2 red">(.*)<\/li>/',$data,$rate);
    $name = $name[1];
    $rate = $rate[1];
    for ($i=0; $i < count($name); $i++) {
        $rate[$i] = str_ireplace("%","",$rate[$i]);
        mysqli_query($con,"insert into maoyan_tomorrow values(
        '{$name[$i]}',{$rate[$i]},'{$date}'
        );");
    }















 ?>
