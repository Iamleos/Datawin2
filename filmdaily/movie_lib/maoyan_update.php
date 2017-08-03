<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());

//获取数据库连接
//filmdaily数据库
    $filmdaily = getDB("filmdaily");
//movie数据库
    $movie = getDB("movie");
//获取即将上映的电影
    $today = date("Y-m-d",time());
    $url = "http://piaofang.maoyan.com/store";
    $result = getResult($url);
    preg_match_all('/<div class="title">.*<\/div>\s*([0-9,-]*).*\s*<p class="lineDot">/', $result, $showtime);
    preg_match_all('/<article class=\"indentInner canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\'\">/', $result, $filmnum);
    preg_match_all('/<div class="title">(.*)<\/div>/',$result,$filmname);
    $filmname = $filmname[1];
    $showtime = $showtime[1];
    $filmnum = $filmnum[1];
    $filmnametemp = NULL;
    $filmnumtemp = NULL;
    $flag = 0;
    foreach ($showtime as $key => $value) {
        $diff = date_diff(date_create($today),date_create($value));
        if($diff->format("%a") <= 30){
            $filmnametemp[$flag] = $filmname[$key];
            $filmnumtemp[$flag] = $filmnum[$key];
            $flag++;
        }
    }
    $filmname = $filmnametemp;
    $filmnum = $filmnumtemp;
//
    foreach ($filmnum as $key => $value) {
            //获取电影咨询
        $former = mysqli_query($movie, "select time from maoyan where movie = '{$filmname[$key]}';");
        $former = mysqli_fetch_all($former, MYSQLI_ASSOC)[0]["time"];
        if ($showtime[$key] == $former) {
            var_dump("1");
            continue;
        }else {
            mysqli_query($movie, "update maoyan set time = '{$showtime[$key]}' where movie = '{$filmname[$key]}';");
            mysqli_query($filmdaily, "insert into film_change values('{$filmname[$key]}', '{$former}', '{$showtime[$key]}', '{$date}');");
        }
    }
?>
