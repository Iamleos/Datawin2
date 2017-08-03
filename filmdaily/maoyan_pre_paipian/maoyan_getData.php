<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $tomorrow = date_modify(date_create(date("Y-m-d")),"+1 day");
    $tomorrow = date_format($tomorrow,"Y-m-d");
    $url = "http://piaofang.maoyan.com/show?showDate=".$tomorrow."&periodType=0&showType=2";
    $result = getResult($url);
    $hj_url = "http://piaofang.maoyan.com/show?showDate=".$tomorrow."&periodType=1&showType=2";
    $hj_result = getResult($hj_url);
    preg_match_all('/charset=utf\-8;base64,(.*)\) format/', $result, $ttf);
    preg_match_all('/charset=utf\-8;base64,(.*)\) format/', $hj_result, $hj_ttf);
    if(count($ttf[1])==0){
        shell_exec("php ".__DIR__."/maoyan_getData2.php");
    }
    else{
    //get_original_data
        preg_match_all('/<li class=\'c1 lineDot\'>(.*) <\/li>/',$result,$name);
        preg_match_all('/<li class="c2 red"><i class="cs">(.*)<\/i><\/li>/',$result,$date_rate);
        preg_match_all('/<li class="c3 gray"><i class="cs">(.*)场<\/i><\/li>/',$result,$data_number);
        //convert woff to ttf && get key-value
        $kv= getKV(__DIR__,$ttf[1][0]);
        $date_rate = str_ireplace($kv[0],$kv[1],$date_rate[1]);
        $date_rate = str_ireplace("%","",$date_rate);
        $data_number = str_ireplace($kv[0],$kv[1],$data_number[1]);
        $data_number = str_ireplace(",","",$data_number);
    }
    if(count($hj_ttf[1])==0){
        shell_exec("php ".__DIR__."/maoyan_getData2.php");
    }
    else{
    //get_original_data
        preg_match_all('/<li class="c2 red"><i class="cs">(.*)<\/i><\/li>/',$hj_result,$hj_date_rate);
        preg_match_all('/<li class="c3 gray"><i class="cs">(.*)场<\/i><\/li>/',$hj_result,$hj_data_number);
        //convert woff to ttf && get key-value

        $kv= getKV(__DIR__,$hj_ttf[1][0]);
        $hj_date_rate = str_ireplace($kv[0],$kv[1],$hj_date_rate[1]);
        $hj_date_rate = str_ireplace("%","",$hj_date_rate);
        $hj_data_number = str_ireplace($kv[0],$kv[1],$hj_data_number[1]);
        $hj_data_number = str_ireplace(",","",$hj_data_number);
    }
    foreach ($name[1] as $key => $value) {
        mysqli_query($con, "insert into maoyan_pre_paipian values(
        '{$value}', '{$date_rate[$key]}','{$data_number[$key]}','{$hj_date_rate[$key]}','{$hj_data_number[$key]}','{$tomorrow}','{$date}'
        );");
    }

























 ?>
