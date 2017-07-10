<?php
    //倒入自定义库
    include "/root/Datawin/zhishu/jrtt/function_lib/lib.php";
    //获取采集源
    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname;");
    $yirenname = mysqli_fetch_all($yirenname);
    mysqli_close($yirencon);
    $tvcon = DataWin\getDB("TV");
    $tvname = mysqli_fetch_all(mysqli_query($tvcon,"select name from search_list;"));
    mysqli_close($tvcon);
    $zycon = DataWin\getDB("zhishu");
    $zyname = mysqli_query($zycon,"select name from linshi_word;");
    $zyname = mysqli_fetch_all($zyname);
    mysqli_close($zycon);

    $con = Datawin\getDB('zhishu');
    //设置采集时间戳

    $time = DataWin\getDate();
    $info_time = date_create(date("Ymd"));
    $now_info_time = date_modify($info_time,"-1 day");
    $now_info_time = date_format($now_info_time,"Ymd");
    $info_time = date_format($info_time,"Y-m-d");

    //yiren
    foreach ($yirenname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/age/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
            if(count($json) == 0 && $flag < 3){
                $flag++;
                continue;
            }
            elseif (count($json) != 0 || $flag >2 ) {
                break;
            }
        } while (0);
        if ($json[0] == '{"keyword":["validation.app_keyword"]}'){
            $json = NULL;
            continue;
        }

        $data = json_decode($json[0],true)['age'];

        if (count($data) == 0) {
            $json = NULL;
            continue;
        }
        if(count($data) < 7){
            echo "string";
            $slot = count($data);
            for ($i=0; $i < 7-count($data); $i++) {
                $data[$slot++]["v"] = 0;
            }
        }
        $age18_23 = $data[2]["v"];
        $age24_30 = $data[3]["v"];
        $age31_40 = $data[4]["v"];
        $age41_50 = $data[5]["v"];
        $age50_ = $data[6]["v"];
        mysqli_query($con,"insert into jrtt_yiren_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$age18_23}','{$age24_30}','{$age31_40}','{$age41_50}','{$age50_}'
        )");
        $json = NULL;
        var_dump($value[0]);
        sleep(1);
    }

    //tv
    foreach ($tvname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/age/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
            if(count($json) == 0 && $flag < 3){
                $flag++;
                continue;
            }
            elseif (count($json) != 0 || $flag >2 ) {
                break;
            }
        } while (0);
        if ($json[0] == '{"keyword":["validation.app_keyword"]}'){
            $json = NULL;
            continue;
        }

        $data = json_decode($json[0],true)['age'];

        if (count($data) == 0) {
            $json = NULL;
            continue;
        }
        if(count($data) < 7){
            echo "string";
            $slot = count($data);
            for ($i=0; $i < 7-count($data); $i++) {
                $data[$slot++]["v"] = 0;
            }
        }

        $age18_23 = $data[2]["v"];
        $age24_30 = $data[3]["v"];
        $age31_40 = $data[4]["v"];
        $age41_50 = $data[5]["v"];
        $age50_ = $data[6]["v"];
        mysqli_query($con,"insert into jrtt_tv_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$age18_23}','{$age24_30}','{$age31_40}','{$age41_50}','{$age50_}'
        )");
        $json = NULL;
        var_dump($value[0]);
        sleep(1);
    }


    //film
    foreach ($filmname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/age/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
            if(count($json) == 0 && $flag < 3){
                $flag++;
                continue;
            }
            elseif (count($json) != 0 || $flag >2 ) {
                break;
            }
        } while (0);
        if ($json[0] == '{"keyword":["validation.app_keyword"]}'){
            $json = NULL;
            continue;
        }

        $data = json_decode($json[0],true)['age'];

        if (count($data) == 0) {
            $json = NULL;
            continue;
        }
        if(count($data) < 7){
            echo "string";
            $slot = count($data);
            for ($i=0; $i < 7-count($data); $i++) {
                $data[$slot++]["v"] = 0;
            }
        }

        $age18_23 = $data[2]["v"];
        $age24_30 = $data[3]["v"];
        $age31_40 = $data[4]["v"];
        $age41_50 = $data[5]["v"];
        $age50_ = $data[6]["v"];
        mysqli_query($con,"insert into jrtt_film_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$age18_23}','{$age24_30}','{$age31_40}','{$age41_50}','{$age50_}'
        )");
        $json = NULL;
        var_dump($value[0]);
        sleep(1);
    }


    //zy
    foreach ($zyname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/age/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
            if(count($json) == 0 && $flag < 3){
                $flag++;
                continue;
            }
            elseif (count($json) != 0 || $flag >2 ) {
                break;
            }
        } while (0);
        if ($json[0] == '{"keyword":["validation.app_keyword"]}'){
            $json = NULL;
            continue;
        }

        $data = json_decode($json[0],true)['age'];

        if (count($data) == 0) {
            $json = NULL;
            continue;
        }
        if(count($data) < 7){
            echo "string";
            $slot = count($data);
            for ($i=0; $i < 7-count($data); $i++) {
                $data[$slot++]["v"] = 0;
            }
        }

        $age18_23 = $data[2]["v"];
        $age24_30 = $data[3]["v"];
        $age31_40 = $data[4]["v"];
        $age41_50 = $data[5]["v"];
        $age50_ = $data[6]["v"];
        mysqli_query($con,"insert into jrtt_zy_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$age18_23}','{$age24_30}','{$age31_40}','{$age41_50}','{$age50_}'
        )");
        $json = NULL;
        var_dump($value[0]);
        sleep(1);
    }










 ?>
