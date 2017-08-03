<?php
//http://piaofang.maoyan.com/company/cinema?date=2016-12-21&webCityId=0&cityTier=1&page=1&cityName=%E4%B8%80%E7%BA%BF%E5%9F%8E%E5%B8%82
    include "/root/Datawin/filmdaily/lib_function/function.php";
$field = array(
    "全国"=>array(
        "webCityId"=>'0',
        "cityTier"=>'1',
        "page"=>'1',
        "cityName"=>'',
    ),
    "一线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'1',
        "page"=>'1',
        "cityName"=>'',
    ),
    "二线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'2',
        "page"=>'1',
        "cityName"=>'',
    ),
    "三线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'3',
        "page"=>'1',
        "cityName"=>'',
    ),
    "北京"=>array(
        "webCityId"=>'1',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "上海"=>array(
        "webCityId"=>'10',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "广州"=>array(
        "webCityId"=>'20',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "深圳"=>array(
        "webCityId"=>'30',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "重庆"=>array(
        "webCityId"=>'45',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "杭州"=>array(
        "webCityId"=>'50',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "南京"=>array(
        "webCityId"=>'55',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "武汉"=>array(
        "webCityId"=>'57',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "成都"=>array(
        "webCityId"=>'59',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "苏州"=>array(
        "webCityId"=>'80',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
);
    date_default_timezone_set("Asia/Shanghai");
    $yesterday = date_modify(date_create(date("Y-m-d")),"-1 day");
    $yesterday = date_format($yesterday,"Y-m-d");
    $con = getDB("filmdaily");
//采集全国，一线，二线，三线城市数据
    $qg_url = "http://piaofang.maoyan.com/company/cinema?date=".$yesterday."&webCityId=0&cityTier=5&page=1&cityName=%E5%85%A8%E5%9B%BD";
    $yx_url = "http://piaofang.maoyan.com/company/cinema?date=".$yesterday."&webCityId=0&cityTier=1&page=1&cityName=%E4%B8%80%E7%BA%BF%E5%9F%8E%E5%B8%82";
    $ex_url = "http://piaofang.maoyan.com/company/cinema?date=".$yesterday."&webCityId=0&cityTier=2&page=1&cityName=%E4%BA%8C%E7%BA%BF%E5%9F%8E%E5%B8%82";
    $sx_url = "http://piaofang.maoyan.com/company/cinema?date=".$yesterday."&webCityId=0&cityTier=3&page=1&cityName=%E4%B8%89%E7%BA%BF%E5%9F%8E%E5%B8%82";

    $qg_result = getResult($qg_url);
    $yx_result = getResult($yx_url);
    $ex_result = getResult($ex_url);
    $sx_result = getResult($sx_url);
var_dump($qg_result);
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
    mysqli_query($con,"insert into maoyan_cinema values(
        '全国','0','{$qg_data[0]}','{$qg_data[1]}','{$qg_data[2]}','{$qg_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_cinema values(
        '一线城市','0','{$yx_data[0]}','{$yx_data[1]}','{$yx_data[2]}','{$yx_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_cinema values(
        '二线城市','0','{$ex_data[0]}','{$ex_data[1]}','{$ex_data[2]}','{$ex_data[3]}','{$yesterday}'
    );");
    mysqli_query($con,"insert into maoyan_cinema values(
        '三线城市','0','{$sx_data[0]}','{$sx_data[1]}','{$sx_data[2]}','{$sx_data[3]}','{$yesterday}'
    );");

//通过xhr采集全国范围内前50影院数据
    exec("python /root/Datawin/filmdaily/maoyan_cinema/getData.py 1 {$yesterday}",$json);
    $data = json_decode($json[0],true)["cinemaList"];
    preg_match_all('/<td>(.*)<\/td>/',$data,$data);
    $data = $data[1];
    for ($i=0; $i < count($data)/6; $i++) {
        $data[$i*6+2] = toWan($data[$i*6+2]);
        mysqli_query($con,"insert into maoyan_cinema values(
            '{$data[$i*6+1]}','{$data[$i*6+0]}','{$data[$i*6+2]}','{$data[$i*6+3]}','{$data[$i*6+4]}','{$data[$i*6+5]}','{$yesterday}'
        );");
    }















 ?>
