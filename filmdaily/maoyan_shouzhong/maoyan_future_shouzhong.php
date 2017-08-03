<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("filmdaily");
    mysqli_query($con,"set names utf8");

    //获取每部电影的编号
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $today = date("Y-m-d",time());
    $url = "http://piaofang.maoyan.com/store";
    $result = getResult($url);
    preg_match_all('/<div class="title">.*<\/div>\s*([0-9,-]*).*\s*<p class="lineDot">/', $result, $showtime);
    preg_match_all('/<article class=\"indentInner canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\'\">/', $result, $filmnum);
    preg_match_all('/<div class="title">(.*)<\/div>/',$result,$filmname);
    $filmname = $filmname[1];
    $showtime = $showtime[1];
    $filmnum = $filmnum[1];
    foreach ($showtime as $key => $value) {
        $diff = date_diff(date_create($today),date_create($value));
        if($diff->format("%a") <= 30){
            $url = "http://piaofang.maoyan.com/movie/".$filmnum[$key]."/wantindex";
            $result = getResult($url);
            preg_match_all('/<script id="pageData" type="application\/json">\s*(.*)\s*<\/script>/',$result,$data);
            preg_match_all('/<div class="stackcolumn-bar left" style="width:(.*)"><\/div>/',$result, $left);
            preg_match_all('/<div class="stackcolumn-bar right" style="width:(.*)"><\/div>/',$result, $right);
            preg_match_all('/<div class="linebars-desc">(.*)<\/div>\s*<div class="linebars-value">(.*)<\/div>/',$result, $city);
            $city_data = array("","","","");
            foreach($city[1] as $key2 => $value2){
                switch ($value2){
                      case "一线城市":
                              $city_data[0] = str_replace('%','',$city[2][$key2]);
                              break;
                      case "二线城市":
                              $city_data[1] = str_replace('%','',$city[2][$key2]);
                              break;
                      case "三线城市":
                              $city_data[2] = str_replace('%','',$city[2][$key2]);
                              break;
                      case "四线城市":
                              $city_data[3] = str_replace('%','',$city[2][$key2]);
                              break;
                }

            }
            if($left[0]!=NULL){
                $man = str_replace('%','',$left[1][0]);
                $woman = str_replace('%','',$right[1][0]);
                $bachelor_or_above = str_replace('%','',$left[1][1]);
                $bachelor_below = str_replace('%','',$right[1][1]);
                $data = $data[1][0];
                $data = json_decode($data,true);
                $ageData = $data["ageRatesChart"]["series"][0]["points"];
                $occupation = $data["occupChart"]["series"][0]["points"];
                $interest = $data["interestChart"]["series"][0]["points"];
                //数据类型转换
                foreach ($ageData as $key1 => $value1) {
                    $ageData[$key1]["yPercent"] = (string)$value1["yPercent"];
                }
                foreach ($occupation as $key1 => $value1) {
                    $occupation[$key1]["yPercent"] = (string)$value1["yPercent"];
                }
                foreach ($interest as $key1 => $value1) {
                    $interest[$key1]["yPercent"] = (string)$value1["yPercent"];
                }
                    mysqli_query($con, "insert into maoyan_shouzhong values('{$filmname[$key]}','0','{$ageData[0]['yPercent']}','{$ageData[1]['yPercent']}','{$ageData[2]['yPercent']}',
                   '{$ageData[3]['yPercent']}','{$ageData[4]['yPercent']}','{$ageData[5]['yPercent']}','{$man}','{$woman}','{$date}','{$bachelor_or_above}','{$bachelor_below}','{$city_data[0]}',
                      '{$city_data[1]}','{$city_data[2]}','{$city_data[3]}','{$occupation[2]['yPercent']}','{$occupation[1]['yPercent']}','{$occupation[0]['yPercent']}','{$interest[3]['yPercent']}',
                      '{$interest[0]['yPercent']}','{$interest[1]['yPercent']}','{$interest[2]['yPercent']}','{$interest[7]['yPercent']}','{$interest[4]['yPercent']}','{$interest[8]['yPercent']}',
                      '{$interest[10]['yPercent']}','{$interest[11]['yPercent']}','{$interest[5]['yPercent']}','{$interest[6]['yPercent']}','{$interest[9]['yPercent']}');");
            }
            else {
                continue;
            }
        }
        else {
            break;
        }
        sleep(3);
    }

    //var_dump($showtime);

 ?>
