<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $con = getDB("filmdaily");
    mysqli_query($con, "truncate table maoyanhuangjinpaipian;");

//通过xhr采集全国范围内前50影院数据

    exec("python ".__DIR__."/getData.py 1",$hj_json);
    $data = json_decode($hj_json[0])->data;
    preg_match_all('/<li class=\'c1 lineDot\'>(.*) <\/li>/',$data,$name);
    preg_match_all('/<li class="c2 red">(.*)<\/li>/',$data,$rate);
    preg_match_all('/<li class="c3 gray">(.*)<\/li>/',$data,$session);
    $name = $name[1];
    $rate = $rate[1];
    $session = $session[1];
    for ($i=0; $i < count($name); $i++) {
        $session[$i] = str_ireplace("场","",$session[$i]);
        $session[$i] = str_ireplace(",","",$session[$i]);
        $rate[$i] = str_ireplace("%","",$rate[$i]);
        mysqli_query($con,"insert into maoyanhuangjinpaipian values(
        '{$name[$i]}',{$rate[$i]},{$session[$i]},'{$date}',1
        );");
    }















 ?>
