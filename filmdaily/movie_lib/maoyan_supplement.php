<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
//比对电影名字，没有的，需要加入maoyan
    $con = getDB("movie");
//采集即将上映的电影
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
        $url = "http://piaofang.maoyan.com/movie/".$value;
        $result = getResult($url);
        preg_match_all('/<p class="info-category">\s*(.*)<!--\s*-->(.*)\s*<\/p>/',$result, $category);
        if ($category[0] == NULL) {
            $category = "NULL";
        }else {
            $category = $category[1][0].$category[2][0];
        }
        $category = explode('/', $category);
        $region = $category[0];
        $category = $category[1];
        //查找daoyan，zhuyan，bianji，zhipian，faxing，jianjie
        exec("python ".__DIR__."/getData.py {$value}",$json);
        $data = json_decode($json[0],true);
        $celebrity = $data["sectionHTMLs"]["celebritySection"]["html"];
        $celebrity = $celebrity.'<div class="category">';
        $company = $data["sectionHTMLs"]["companySection"]["html"];
        $company = $company.'<div class="category">';
        $detail = $data["sectionHTMLs"]["detailSection"]["html"];
        preg_match_all('/导演<\/h2>([\s|\S]*?)<div class="category">/',$celebrity,$daoyan);
        if ($daoyan[1] != NULL) {
            preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$daoyan[1][0],$daoyan);
            $daoyan = $daoyan[1];
        }
        else {
            $daoyan = NULL;
        }
        preg_match_all('/演员<\/h2>([\s|\S]*?)<div class="category">/',$celebrity,$zhuyan);
        if ($zhuyan[1] != NULL) {
            preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$zhuyan[1][0],$zhuyan);
            $zhuyan = $zhuyan[1];
        }
        else {
            $zhuyan = NULL;
        }

        preg_match_all('/制作<\/h2>([\s|\S]*?)<div class="category">/',$company,$zhipian);
        if ($zhipian[1] != NULL) {
            preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
            $zhipian = $zhipian[1];
        }
        else {
            $zhipian = NULL;
        }

        preg_match_all('/发行<\/h2>([\s|\S]*?)<div class="category">/',$company,$faxing);
        if ($faxing[1] != NULL) {
            preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$faxing[1][0],$faxing);
            $faxing = $faxing[1];
        }
        else {
            $faxing = NULL;
        }

        preg_match_all('/<div class="detail-block-content">(.*?)<\/div>/',$detail,$jianjie);
        if ($jianjie[1] != NULL) {
            $jianjie = $jianjie[1][0];
        }
        else {
            $jianjie = NULL;
        }

        //合并数据
        //daoyan
        if ($daoyan != NULL) {
            $temp = NULL;
            foreach ($daoyan as $key2 => $value2) {
                $temp .= $value2.';';
            }
            $daoyan = $temp;
        }
        else {
            $daoyan = "NULL";
        }
        //zhuyan
        if ($zhuyan != NULL) {
            $temp = NULL;
            foreach ($zhuyan as $key2 => $value2) {
                $temp .= $value2.';';
            }
            $zhuyan = $temp;
        }
        else {
            $zhuyan = "NULL";
        }

        //$zhipian
        if ($zhipian != NULL) {
            $temp = NULL;
            foreach ($zhipian as $key2 => $value2) {
                $temp .= $value2.';';
            }
            $zhipian = $temp;
        }
        else {
            $zhipian = "NULL";
        }

        //$faxing
        if ($faxing != NULL) {
            $temp = NULL;
            foreach ($faxing as $key2 => $value2) {
                $temp .= $value2.';';
            }
            $faxing = $temp;
        }
        else {
            $faxing = "NULL";
        }



        var_dump($filmname[$key]);


        //插入数据库

        mysqli_query($con, "insert into maoyan values('{$filmname[$key]}','{$daoyan}','{$zhuyan}','{$zhipian}','{$faxing}',
        '{$jianjie}','{$value}','{$showtime[$key]}','{$category}','{$region}');");
        $json = NULL;
        sleep(3);
    }
    mysqli_close($con);

 ?>
