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
            exec("python /root/Datawin/zhishu/jrtt/relate/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true)['related_keywords'];
        $data = array_pop($data)["relates"];
        if(count($data) == 0){
            $json = NULL;
            continue;
        }
        $relate_data = array();
        for ($i=0; $i < 10; $i++) {
            $max = 0;
            $index = 0;
            for ($j=0; $j < 15; $j++) {
                if($data[$j]["distance"] > $max){
                    $max = $data[$j]["distance"];
                    $relate_data[$i] = $data[$j];
                    $index = $j;
                }
            }
            $data[$index]["distance"] = 0;
        }

        for ($i=0; $i < 10; $i++) {
            $rank = $i+1;
            mysqli_query($con, "insert into jrtt_yiren_relatedRank values(
            '{$name}',{$rank},'{$relate_data[$i]["name"]}','{$relate_data[$i]["distance"]}','{$now_info_time}','{$time}'
            );");
            mysqli_query($con, "insert into jrtt_yiren_hotRank values(
            '{$name}',{$rank},'{$data[$i]["name"]}','{$data[$i]["score"]}','{$now_info_time}','{$time}'
            );");


        }
        $json = NULL;
        var_dump($name);
        sleep(1);
    }

//tv
    foreach ($tvname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/relate/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true)['related_keywords'];
        $data = array_pop($data)["relates"];
        if(count($data) == 0){
            $json = NULL;
            continue;
        }
        $relate_data = array();
        for ($i=0; $i < 10; $i++) {
            $max = 0;
            $index = 0;
            for ($j=0; $j < 15; $j++) {
                if($data[$j]["distance"] > $max){
                    $max = $data[$j]["distance"];
                    $relate_data[$i] = $data[$j];
                    $index = $j;
                }
            }
            $data[$index]["distance"] = 0;
        }

        for ($i=0; $i < 10; $i++) {
            $rank = $i+1;
            mysqli_query($con, "insert into jrtt_tv_relatedRank values(
            '{$name}',{$rank},'{$relate_data[$i]["name"]}','{$relate_data[$i]["distance"]}','{$now_info_time}','{$time}'
            );");
            mysqli_query($con, "insert into jrtt_tv_hotRank values(
            '{$name}',{$rank},'{$data[$i]["name"]}','{$data[$i]["score"]}','{$now_info_time}','{$time}'
            );");


        }
        $json = NULL;
        var_dump($name);
        sleep(1);
    }

//film
    foreach ($filmname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/relate/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true)['related_keywords'];
        $data = array_pop($data)["relates"];
        if(count($data) == 0){
            $json = NULL;
            continue;
        }
        $relate_data = array();
        for ($i=0; $i < 10; $i++) {
            $max = 0;
            $index = 0;
            for ($j=0; $j < 15; $j++) {
                if($data[$j]["distance"] > $max){
                    $max = $data[$j]["distance"];
                    $relate_data[$i] = $data[$j];
                    $index = $j;
                }
            }
            $data[$index]["distance"] = 0;
        }

        for ($i=0; $i < 10; $i++) {
            $rank = $i+1;
            mysqli_query($con, "insert into jrtt_film_relatedRank values(
            '{$name}',{$rank},'{$relate_data[$i]["name"]}','{$relate_data[$i]["distance"]}','{$now_info_time}','{$time}'
            );");
            mysqli_query($con, "insert into jrtt_film_hotRank values(
            '{$name}',{$rank},'{$data[$i]["name"]}','{$data[$i]["score"]}','{$now_info_time}','{$time}'
            );");


        }
        $json = NULL;
        var_dump($name);
        sleep(1);
    }

//zy
    foreach ($zyname as $key => $value) {
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/relate/getData.py '{$name}' '{$last_info_time}' '{$now_info_time}'",$json);
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
        $data = json_decode($json[0],true)['related_keywords'];
        $data = array_pop($data)["relates"];
        if(count($data) == 0){
            $json = NULL;
            continue;
        }
        $relate_data = array();
        for ($i=0; $i < 10; $i++) {
            $max = 0;
            $index = 0;
            for ($j=0; $j < 15; $j++) {
                if($data[$j]["distance"] > $max){
                    $max = $data[$j]["distance"];
                    $relate_data[$i] = $data[$j];
                    $index = $j;
                }
            }
            $data[$index]["distance"] = 0;
        }

        for ($i=0; $i < 10; $i++) {
            $rank = $i+1;
            mysqli_query($con, "insert into jrtt_zy_relatedRank values(
            '{$name}',{$rank},'{$relate_data[$i]["name"]}','{$relate_data[$i]["distance"]}','{$now_info_time}','{$time}'
            );");
            mysqli_query($con, "insert into jrtt_zy_hotRank values(
            '{$name}',{$rank},'{$data[$i]["name"]}','{$data[$i]["score"]}','{$now_info_time}','{$time}'
            );");


        }
        $json = NULL;
        var_dump($name);
        sleep(1);
    }




 ?>
