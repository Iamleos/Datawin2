<?php
    //倒入自定义库
    include "/root/Datawin/zhishu/jrtt/function_lib/lib.php";
    $province = simplexml_load_file("/root/Datawin/zhishu/jrtt/region/region.xml");
    $province = json_decode(json_encode($province),true);

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
        $province_copy = $province;
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/region/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
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
            $data = NULL;

            continue;
        }
        $data = json_decode($json[0],true)['region_heat'];
        $data = array_pop($data)["provinces"];
        if (count($data) == 0) {
            $json = NULL;
            $data = NULL;

            continue;
        }
        foreach ($data as $key1 => $value1) {
            $a = array_search($data[$key1]["regionId"],$province["key"]);
            $index[$key1] = $province["value"][$a];
            $province_copy["value"][$a] = "0";
        }
        if (count($data) != 31) {
            $start = count($data);
            foreach ($province_copy["value"] as $key2 => $value2) {
                if ($value2 != "0") {
                    array_push($index,$value2);
                    $data[$start++]["score"] = 0;
                }
            }
        }

        mysqli_query($con ,"insert into jrtt_yiren_region(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$data[0]["score"]}','{$data[1]["score"]}','{$data[2]["score"]}','{$data[3]["score"]}','{$data[4]["score"]}','{$data[5]["score"]}','{$data[6]["score"]}','{$data[7]["score"]}',
        '{$data[8]["score"]}','{$data[9]["score"]}','{$data[10]["score"]}','{$data[11]["score"]}','{$data[12]["score"]}','{$data[13]["score"]}','{$data[14]["score"]}','{$data[15]["score"]}',
        '{$data[16]["score"]}','{$data[17]["score"]}','{$data[18]["score"]}','{$data[19]["score"]}','{$data[20]["score"]}','{$data[21]["score"]}','{$data[22]["score"]}','{$data[23]["score"]}',
        '{$data[24]["score"]}','{$data[25]["score"]}','{$data[26]["score"]}','{$data[27]["score"]}','{$data[28]["score"]}','{$data[29]["score"]}','{$data[30]["score"]}'
        );");
        var_dump($value[0]);
        $json = NULL;
        $data = NULL;
        $index = NULL;
        sleep(1);
    }

    //film
    foreach ($filmname as $key => $value) {
        $province_copy = $province;
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/region/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
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
            $data = NULL;

            continue;
        }
        $data = json_decode($json[0],true)['region_heat'];
        $data = array_pop($data)["provinces"];
        if (count($data) == 0) {
            $json = NULL;
            $data = NULL;

            continue;
        }
        foreach ($data as $key1 => $value1) {
            $a = array_search($data[$key1]["regionId"],$province["key"]);
            $index[$key1] = $province["value"][$a];
            $province_copy["value"][$a] = "0";
        }
        if (count($data) != 31) {
            $start = count($data);
            foreach ($province_copy["value"] as $key2 => $value2) {
                if ($value2 != "0") {
                    array_push($index,$value2);
                    $data[$start++]["score"] = 0;
                }
            }
        }

        mysqli_query($con ,"insert into jrtt_film_region(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$data[0]["score"]}','{$data[1]["score"]}','{$data[2]["score"]}','{$data[3]["score"]}','{$data[4]["score"]}','{$data[5]["score"]}','{$data[6]["score"]}','{$data[7]["score"]}',
        '{$data[8]["score"]}','{$data[9]["score"]}','{$data[10]["score"]}','{$data[11]["score"]}','{$data[12]["score"]}','{$data[13]["score"]}','{$data[14]["score"]}','{$data[15]["score"]}',
        '{$data[16]["score"]}','{$data[17]["score"]}','{$data[18]["score"]}','{$data[19]["score"]}','{$data[20]["score"]}','{$data[21]["score"]}','{$data[22]["score"]}','{$data[23]["score"]}',
        '{$data[24]["score"]}','{$data[25]["score"]}','{$data[26]["score"]}','{$data[27]["score"]}','{$data[28]["score"]}','{$data[29]["score"]}','{$data[30]["score"]}'
        );");
        var_dump($value[0]);
        $json = NULL;
        $data = NULL;
        $index = NULL;
        sleep(1);
    }


    //zy
    foreach ($zyname as $key => $value) {
        $province_copy = $province;
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/region/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
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
            $data = NULL;

            continue;
        }
        $data = json_decode($json[0],true)['region_heat'];
        $data = array_pop($data)["provinces"];
        if (count($data) == 0) {
            $json = NULL;
            $data = NULL;

            continue;
        }
        foreach ($data as $key1 => $value1) {
            $a = array_search($data[$key1]["regionId"],$province["key"]);
            $index[$key1] = $province["value"][$a];
            $province_copy["value"][$a] = "0";
        }
        if (count($data) != 31) {
            $start = count($data);
            foreach ($province_copy["value"] as $key2 => $value2) {
                if ($value2 != "0") {
                    array_push($index,$value2);
                    $data[$start++]["score"] = 0;
                }
            }
        }

        mysqli_query($con ,"insert into jrtt_zy_region(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$data[0]["score"]}','{$data[1]["score"]}','{$data[2]["score"]}','{$data[3]["score"]}','{$data[4]["score"]}','{$data[5]["score"]}','{$data[6]["score"]}','{$data[7]["score"]}',
        '{$data[8]["score"]}','{$data[9]["score"]}','{$data[10]["score"]}','{$data[11]["score"]}','{$data[12]["score"]}','{$data[13]["score"]}','{$data[14]["score"]}','{$data[15]["score"]}',
        '{$data[16]["score"]}','{$data[17]["score"]}','{$data[18]["score"]}','{$data[19]["score"]}','{$data[20]["score"]}','{$data[21]["score"]}','{$data[22]["score"]}','{$data[23]["score"]}',
        '{$data[24]["score"]}','{$data[25]["score"]}','{$data[26]["score"]}','{$data[27]["score"]}','{$data[28]["score"]}','{$data[29]["score"]}','{$data[30]["score"]}'
        );");
        var_dump($value[0]);
        $json = NULL;
        $data = NULL;
        $index = NULL;
        sleep(1);
    }


    //tv
    foreach ($tvname as $key => $value) {
        $province_copy = $province;
        $name = $value[0];
        $flag = 0;
        do {
            exec("python /root/Datawin/zhishu/jrtt/region/getData.py '{$value[0]}' '{$now_info_time}' '{$info_time}'",$json);
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
            $data = NULL;

            continue;
        }
        $data = json_decode($json[0],true)['region_heat'];
        $data = array_pop($data)["provinces"];
        if (count($data) == 0) {
            $json = NULL;
            $data = NULL;

            continue;
        }
        foreach ($data as $key1 => $value1) {
            $a = array_search($data[$key1]["regionId"],$province["key"]);
            $index[$key1] = $province["value"][$a];
            $province_copy["value"][$a] = "0";
        }
        if (count($data) != 31) {
            $start = count($data);
            foreach ($province_copy["value"] as $key2 => $value2) {
                if ($value2 != "0") {
                    array_push($index,$value2);
                    $data[$start++]["score"] = 0;
                }
            }
        }

        mysqli_query($con ,"insert into jrtt_tv_region(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$data[0]["score"]}','{$data[1]["score"]}','{$data[2]["score"]}','{$data[3]["score"]}','{$data[4]["score"]}','{$data[5]["score"]}','{$data[6]["score"]}','{$data[7]["score"]}',
        '{$data[8]["score"]}','{$data[9]["score"]}','{$data[10]["score"]}','{$data[11]["score"]}','{$data[12]["score"]}','{$data[13]["score"]}','{$data[14]["score"]}','{$data[15]["score"]}',
        '{$data[16]["score"]}','{$data[17]["score"]}','{$data[18]["score"]}','{$data[19]["score"]}','{$data[20]["score"]}','{$data[21]["score"]}','{$data[22]["score"]}','{$data[23]["score"]}',
        '{$data[24]["score"]}','{$data[25]["score"]}','{$data[26]["score"]}','{$data[27]["score"]}','{$data[28]["score"]}','{$data[29]["score"]}','{$data[30]["score"]}'
        );");
        var_dump($value[0]);
        $json = NULL;
        $data = NULL;
	$index = NULL;
        sleep(1);
    }






 ?>
