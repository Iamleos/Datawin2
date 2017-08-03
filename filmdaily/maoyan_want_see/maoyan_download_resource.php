<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    date_default_timezone_set("Asia/Shanghai");
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
    $showtimetemp = NULL;
    $flag = 0;
    foreach ($showtime as $key => $value) {
        $diff = date_diff(date_create($today),date_create($value));
        if($diff->format("%a") <= 30){
            $filmnametemp[$flag] = $filmname[$key];
            $filmnumtemp[$flag] = $filmnum[$key];
            $showtimetemp[$flag] = $showtime[$key];
            $flag++;
        }
    }
    $filmname = $filmnametemp;
    $filmnum = $filmnumtemp;
    $showtime = $showtimetemp;
    $file = fopen(__DIR__."/filmname.txt","w");
    foreach ($filmnum as $key => $value) {
    fwrite($file, $filmname[$key]." ".$value." ".$showtime[$key]."\n");
    }


 ?>
