<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
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
        $url = "http://piaofang.maoyan.com/movie/".$value[1]."/cityBox?date=".$yesterday;
        $result = getResult($url);
        preg_match_all('/var cityBoxData = (.*)/',$result,$json);
        $data = json_decode($json[1][0])->data;
        foreach ($data as $key1 => $value1) {
            foreach ($value1->selectData as $key2 => $value2) {
                $value2 = yi2wan($value2);
                $value2 = str_ireplace('%','',$value2);
                $data[$key1]->selectData[$key2] = str_ireplace('万','',$value2);

            }
        }
        foreach ($data as $key3 =>$value3) {
            mysqli_query($con,"insert into maoyan_city values(
            '{$value[0]}','{$value3->commonName}','{$value3->selectData[0]}','{$value3->selectData[1]}','{$value3->selectData[2]}',
            '{$value3->selectData[4]}','{$value3->selectData[5]}','{$value3->selectData[6]}','{$value3->selectData[3]}',
            '{$value3->selectData[7]}','{$value3->selectData[8]}','{$yesterday}'
            )");
        }
        sleep(3);
    }
    mysqli_close($con);















 ?>
