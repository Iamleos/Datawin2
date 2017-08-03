<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    mysqli_query($con, "truncate table maoyan_yingtou;");

    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $yesterday = date_modify(date_create(date("Y-m-d")),"-1 day");
    $yesterday = date_format($yesterday,"Y-m-d");

    $qg_url = "http://piaofang.maoyan.com/company/cinema?date=".$yesterday."&webCityId=0&cityTier=5&page=1&cityName=%E5%85%A8%E5%9B%BD";
    $yx_url = "http://piaofang.maoyan.com/company/invest?date=".$yesterday."&webCityId=0&cityTier=1&page=1&cityName=%E4%B8%80%E7%BA%BF%E5%9F%8E%E5%B8%82";
    $ex_url = "http://piaofang.maoyan.com/company/invest?date=".$yesterday."&webCityId=0&cityTier=2&page=1&cityName=%E4%BA%8C%E7%BA%BF%E5%9F%8E%E5%B8%82";
    $sx_url = "http://piaofang.maoyan.com/company/invest?date=".$yesterday."&webCityId=0&cityTier=3&page=1&cityName=%E4%B8%89%E7%BA%BF%E5%9F%8E%E5%B8%82";

    $qg_result = getResult($qg_url);
    $yx_result = getResult($yx_url);
    $ex_result = getResult($ex_url);
    $sx_result = getResult($sx_url);

    preg_match('/<tr data-linkId="">([\s\S]*?)<\/tr>/',$qg_result,$qg_data);
    preg_match('/charset=utf\-8;base64,(.*)\) format/', $qg_result, $qg_woff);

    preg_match('/<tr data-linkId="">([\s\S]*?)<\/tr>/',$yx_result,$yx_data);
    preg_match('/charset=utf\-8;base64,(.*)\) format/', $yx_result, $yx_woff);

    preg_match('/<tr data-linkId="">([\s\S]*?)<\/tr>/',$ex_result,$ex_data);
    preg_match('/charset=utf\-8;base64,(.*)\) format/', $ex_result, $ex_woff);

    preg_match('/<tr data-linkId="">([\s\S]*?)<\/tr>/',$sx_result,$sx_data);
    preg_match('/charset=utf\-8;base64,(.*)\) format/', $sx_result, $sx_woff);

    preg_match_all('/<td><i class="cs">(.*)<\/i><\/td>/',$qg_data[1],$qg_data);
    preg_match_all('/<td><i class="cs">(.*)<\/i><\/td>/',$yx_data[1],$yx_data);
    preg_match_all('/<td><i class="cs">(.*)<\/i><\/td>/',$ex_data[1],$ex_data);
    preg_match_all('/<td><i class="cs">(.*)<\/i><\/td>/',$sx_data[1],$sx_data);

    $qg_KV = getKV(__DIR__,$qg_woff[1]);
    $qg_data = str_ireplace($qg_KV[0],$qg_KV[1],$qg_data[1]);
    $yx_KV = getKV(__DIR__,$yx_woff[1]);
    $yx_data = str_ireplace($yx_KV[0],$yx_KV[1],$yx_data[1]);
    $ex_KV = getKV(__DIR__,$ex_woff[1]);
    $ex_data = str_ireplace($ex_KV[0],$ex_KV[1],$ex_data[1]);
    $sx_KV = getKV(__DIR__,$sx_woff[1]);
    $sx_data = str_ireplace($sx_KV[0],$sx_KV[1],$sx_data[1]);


    $qg_data[0] = toWan($qg_data[0]);
    $yx_data[0] = toWan($yx_data[0]);
    $ex_data[0] = toWan($ex_data[0]);
    $sx_data[0] = toWan($sx_data[0]);

    $qg_data[1] = toOne($qg_data[1]);
    $yx_data[1] = toOne($yx_data[1]);
    $ex_data[1] = toOne($ex_data[1]);
    $sx_data[1] = toOne($sx_data[1]);

    mysqli_query($con,"insert into maoyan_yingtou values(
        '全国','0','{$qg_data[0]}','{$qg_data[1]}','{$qg_data[2]}','{$qg_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_yingtou values(
        '一线城市','0','{$yx_data[0]}','{$yx_data[1]}','{$yx_data[2]}','{$yx_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_yingtou values(
        '二线城市','0','{$ex_data[0]}','{$ex_data[1]}','{$ex_data[2]}','{$ex_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_yingtou values(
        '三线城市','0','{$sx_data[0]}','{$sx_data[1]}','{$sx_data[2]}','{$sx_data[3]}','{$yesterday}'
    );");

    $url = "http://piaofang.maoyan.com/company/invest";
    $result = getResult($url);
    preg_match_all('/<tr data-linkId=".+">([\s\S]*?)<\/tr>/',$result,$data);
    preg_match('/charset=utf\-8;base64,(.*)\) format/', $result, $woff);
    $KV = getKV(__DIR__,$woff[1]);

    foreach ($data[1] as $key => $value) {
        preg_match_all('/<td>(.*)<\/td>/',$value,$yingtou);
        $rank = $yingtou[1][0];
        $yintou_name = $yingtou[1][1];
        preg_match_all('/<td><i class="cs">(.*)<\/i><\/td>/',$value,$yingtou);
        $yingtou[1][0] = str_ireplace($KV[0],$KV[1],$yingtou[1][0]);
        $yingtou[1][1] = str_ireplace($KV[0],$KV[1],$yingtou[1][1]);
        $yingtou[1][2] = str_ireplace($KV[0],$KV[1],$yingtou[1][2]);
        $yingtou[1][3] = str_ireplace($KV[0],$KV[1],$yingtou[1][3]);
        $yingtou[1][0] = toWan($yingtou[1][0]);
        $yingtou[1][1] = toOne($yingtou[1][1]);

        mysqli_query($con,"insert into maoyan_yingtou values(
            '{$yintou_name}','{$rank}','{$yingtou[1][0]}','{$yingtou[1][1]}','{$yingtou[1][2]}','{$yingtou[1][3]}','{$yesterday}'
        );");



    }






















 ?>
