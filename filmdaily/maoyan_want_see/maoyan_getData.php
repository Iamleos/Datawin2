<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    date_default_timezone_set("Asia/Shanghai");
    $date = (string)date("Y-m-d",time());
    $flag = 0;
    $filename = array();
    $filmnamefile = fopen(__DIR__."/filmname.txt","r");
    while(!feof($filmnamefile)){
        $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
        $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
        $url = "http://piaofang.maoyan.com/movie/".$value[1]."?_v_=yes";
        $result = getResult($url);
        preg_match('/charset=utf\-8;base64,(.*)\) format/', $result, $ttf);
        if(count($ttf)!= 0){
            preg_match_all('/<span class="wish-num ">\s*<i class="cs">(.*)<\/i>/' ,$result, $wantSee);
            preg_match_all('/<span class="topic-value">(.*)<\/span>/',$result,$weibo_dis);
            $weibo_dis = $weibo_dis[1][0];
            $wantSee = $wantSee[1];
            if(strstr("万",$weibo_dis)){
                $weibo_dis = str_replace("万","",$weibo_dis);
                $weibo_dis = (string)($weibo_dis*10000);
            }
            $KV = getKV(__DIR__,$ttf[1]);
            $wantSee = (int)(str_ireplace($KV[0], $KV[1], $wantSee)[0]);
        }
        else {
            $wantSee = "fail";
            $weibo_dis = "fail";
        }
        //预售
        $url = "http://piaofang.maoyan.com/movie/".$value[1]."/boxshow";
        $result = getResult($url);
        //判断是否加密
        if(count($ttf)!=0){
            preg_match_all('/var boxData =(.*)/',$result,$presell);
            preg_match_all('/<div class="t-col"><span(.*)<\/div>/', $result, $dianying);
            $dianying = $dianying[0];
            $index = array();
            foreach ($dianying as $key1 => $value1) {
                if (strstr($value1, '点映')){
                    array_push($index, $key1);
                }
                else {
                    continue;
                }
            }
            $presell = $presell[1][0];
            $presell = json_decode($presell,true)["data"];
            $sum = 0;
            $dysum = 0;
            foreach ($presell as $key1 => $value1) {
                if($value1["boxInfo"] != "--"){
                    $sum += $value1["boxInfo"];
                }
                else {
                    continue;
                }
            }
            foreach ($index as $key1 => $value1) {
                $dysum += $presell[$value1]["boxInfo"];
            }
            $dianying = $dysum;
            $presell = $sum;
        }
        else {
            $presell = "fail";
        }
        $weibo_dis = toOne($weibo_dis);
        mysqli_query($con, "insert into maoyan_want_see values('{$value[0]}','{$wantSee}','{$date}','{$presell}','{$weibo_dis}','{$dianying}');");
        sleep(3);
    }
    mysqli_close($con);
 ?>
