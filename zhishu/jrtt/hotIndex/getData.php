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
    $last_info_time = date_modify($info_time,"-6 days");
    $last_info_time = date_format($info_time,"Ymd");

    //yiren
    foreach ($yirenname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/hotIndex/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true);
        $total_index = (string)(array_pop(array_pop($data['trends_increase'])));
        $trend_index = (string)(array_pop(array_pop($data['trends'])));
        mysqli_query($con, "insert into jrtt_yiren_hotIndex values(
        '{$name}','{$now_info_time}','{$time}','{$trend_index}','{$total_index}'
        );");
        $json = NULL;

        var_dump($name);
        sleep(1);
    }

    foreach ($tvname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/hotIndex/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true);
        $total_index = (string)(array_pop(array_pop($data['trends_increase'])));
        $trend_index = (string)(array_pop(array_pop($data['trends'])));
        mysqli_query($con, "insert into jrtt_tv_hotIndex values(
        '{$name}','{$now_info_time}','{$time}','{$trend_index}','{$total_index}'
        );");
        $json = NULL;

        var_dump($name);
        sleep(1);
    }

    foreach ($zyname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/hotIndex/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true);
        $total_index = (string)(array_pop(array_pop($data['trends_increase'])));
        $trend_index = (string)(array_pop(array_pop($data['trends'])));
        mysqli_query($con, "insert into jrtt_zy_hotIndex values(
        '{$name}','{$now_info_time}','{$time}','{$trend_index}','{$total_index}'
        );");
        $json = NULL;

        var_dump($name);
        sleep(1);
    }

    foreach ($filmname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/hotIndex/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true);
        $total_index = (string)(array_pop(array_pop($data['trends_increase'])));
        $trend_index = (string)(array_pop(array_pop($data['trends'])));
        mysqli_query($con, "insert into jrtt_film_hotIndex values(
        '{$name}','{$now_info_time}','{$time}','{$trend_index}','{$total_index}'
        );");
        $json = NULL;
        var_dump($name);
        sleep(1);
    }


 ?>
