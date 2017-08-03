<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    mysqli_query($con,"set names utf8");
    mysqli_query($con, "truncate table maoyanpaipian;");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $flag = 0;
    $filename = array();
    $filmnamefile = fopen(__DIR__."/filmname.txt","r");
    while(!feof($filmnamefile)){
        $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
        $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
        $url = "http://piaofang.maoyan.com/movie/".$value[1]."/boxshow";
        $result = getResult($url);
        preg_match_all('/charset=utf\-8;base64,(.*)\) format/', $result, $ttf);
        if(count($ttf[1])==0){
            shell_exec("php ".__DIR__."/maoyan_getData2.php");
        }
        else{
            //get_original_data
            preg_match_all('/<div class="t-col"><span.*<b>(.*)<\/b>/',$result,$index);
            preg_match_all('/<div class="t-col"><i class="cs">(.*)<\/i><\/div>/',$result,$data);
            foreach ($index[1] as $key1 => $value1) {
                if ($value1 == $date) {
                    $data_index = $key1;
                    break;
                }else {
                    continue;
                }
            }
            $changci = $data[1][13*$data_index+7];
            $ppzb = $data[1][13*$data_index+3];
            $zw = $data[1][13*$data_index+9];
            $pzzb = $data[1][13*$data_index+8];
            $hj = $data[1][13*$data_index+6];
            //convert woff to ttf && get key-value
            $kv= getKV(__DIR__,$ttf[1][0]);
            $changci = str_ireplace($kv[0],$kv[1],$changci);
            $ppzb = str_ireplace($kv[0],$kv[1],$ppzb);
            $zw = str_ireplace($kv[0],$kv[1],$zw);
            $pzzb = str_ireplace($kv[0],$kv[1],$pzzb);
            $hj = str_ireplace($kv[0],$kv[1],$hj);
            //

            mysqli_query($con, "insert into maoyanpaipian values(
            '{$value[0]}','{$changci}','{$ppzb}','{$zw}','{$pzzb}','{$hj}','{$date}'
            );");
            mysqli_query($con, "insert into maoyan_ri_paipian values(
            '{$value[0]}','{$changci}','{$ppzb}','{$zw}','{$pzzb}','{$hj}','{$date}'
            );");
            var_dump($value[0]);
            sleep(3);
        }
  }
























 ?>
