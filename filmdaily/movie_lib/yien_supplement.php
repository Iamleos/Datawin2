<?php
    include "../lib_function/function.php";
//从文件中读取dianyingzh名字

    $dianyingzhFilmName = array();
    $filmnamefile = fopen(__DIR__."/dianyingzh.txt","r");
    $flag = 0;
    while(!feof($filmnamefile)){
      $dianyingzhFilmName[$flag] = explode(" ",str_replace("\r\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($dianyingzhFilmName);
    fclose($filmnamefile);

    //从文件中读取yien名字
    $yienFilmName = array();
    $filmnamefile = fopen(__DIR__."/yien.txt","r");
    $flag = 0;
    while(!feof($filmnamefile)){
      $yienFilmName[$flag] = explode(" ",str_replace("\r\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($yienFilmName);
    fclose($filmnamefile);

//比对电影名字，没有的，需要加入yien
    $con = getDB('movie');
    foreach ($dianyingzhFilmName as $key => $value) {
        $flag = 0;
        //对比dianyingzh和yien相似度分析
        foreach ($yienFilmName as $key1 => $value1) {
            similar_text($value[0],$value1[0],$percent);
            if($percent >= 80){
                $flag = 1;
                break;
            }
        }

        if($flag == 0){
            //在艺恩搜索影片
            $url = "http://www.cbooo.cn/search?k=".urlencode("喵星人");
            //$url = "http://www.cbooo.cn/search?k=".urlencode($value[0]);
            $result = getResult($url);
            //一次清洗匹配数据
            preg_match_all('/<div class="titlea title03 title04"><h3>影片<\/h3><span><\/span><\/div>\s*<ul class="ulzx03">([\s|\S]*)<\/ul>\s*<div class="titlea title03 title04"><h3>影人<\/h3><span><\/span><\/div>/', $result, $href);
            if(count($href[1]) == 0 || !strstr($href[1][0],"http")){
                continue;
            }

            //二次清洗匹配数据
            $href = $href[1][0];
            preg_match_all('/<li><a target="_blank" href=.* title="(.*)">.*<\/span><\/li>\s*/',$href,$name);
            preg_match_all('/<li><a target="_blank" href="(.*)<\/span><\/li>\s*/',$href,$year);
            $year = $year[1];
            preg_match_all('/<li><a target="_blank" href="(.*)" title=".*">.*<\/span><\/li>\s*/',$href,$href);
            $href = $href[1];
            $name = $name[1];

            //相似度匹配，选择最相似的那个电影获取连接；
            $mostSimilar = array(0,0);
            foreach ($name as $key2 => $value2) {
                similar_text($value[0],$value2,$percent);
                if($percent>$mostSimilar[1] && strstr($year[$key2],'2017年')){
                    $mostSimilar[0] = $key2;
                    $mostSimilar[1] = $percent;
                }
                else {
                    continue;
                }
            }
            $href = $href[$mostSimilar[0]];
            //获取相应影片的信息
            $result = getResult($href);
            //一次清洗数据
            preg_match_all('/<dt>导演：<\/dt>\s*<dd>\s*<p><a target="_blank" href=.*title=.*>(.*)<\/a><span><\/span><\/p>/',$result, $daoyan);
            preg_match_all('/<dt>制作公司：<\/dt>\s*<dd>([\s|\S]*)<\/dd>\s*<dt>发行公司：<\/dt>/',$result, $zhizuo);
            preg_match_all('/<dt>发行公司：<\/dt>\s*<dd>([\s|\S]*)<\/dd>\s*<\/dl>\s*<\/div>\s*<div/',$result, $faxing);
            preg_match_all('/<dt>主演：<\/dt>\s*<dd>([\s|\S]*)<\/dd>\s*<dt>制作公司：<\/dt>/',$result, $zhuyan);
            $daoyan = $daoyan[1];
            $zhizuo = $zhizuo[1][0];
            $faxing = $faxing[1][0];
            $zhuyan = $zhuyan[1][0];
            //二次清洗数据
            preg_match_all('/<p><a target="_blank" href=.*">(.*)<\/a><\/p>/',$zhizuo,$zhizuo);
            preg_match_all('/<p><a target="_blank" href=.*">(.*)<\/a><\/p>/',$faxing,$faxing);
            preg_match_all('/<p>\s*<a target="_blank" href=.*">\s*(.*)\s*<\/a><span><\/span>/',$zhuyan,$zhuyan);
            $zhizuo = $zhizuo[1];
            $faxing = $faxing[1];
            $zhuyan = $zhuyan[1];

            //"&#183;"转换成　“·”　---daoyan
            if(count($daoyan)!=0){
                foreach ($daoyan as $key2 => $value2) {
                    $daoyan[$key2] = str_replace("&#183;","·",$value2);
                }
            }
            //"&#183;"转换成　“·”　---zhuyan
            if(count($zhuyan)!=0){
                foreach ($zhuyan as $key2 => $value2) {
                    $zhuyan[$key2] = str_replace("&#183;","·",$value2);
                }
            }
            //转换成16进制去空格--zhuyan
            if(count($zhuyan)!=0){
                foreach ($zhuyan as $key2=> $value2) {
                    $zhuyan[$key2] = hex2bin(str_replace('20','',bin2hex($value2)));
                }
            }
            //转换成16进制去空格--daoyan
            if(count($daoyan)!=0){
                foreach ($daoyan as $key2 => $value2) {
                    $daoyan[$key2] = hex2bin(str_replace('20','',bin2hex($value2)));
                }
            }
            //转换成16进制去空格--zhizuo
            if(count($zhizuo)!=0){
                foreach ($zhizuo as $key2 => $value2) {
                    $zhizuo[$key2] = hex2bin(str_replace('20','',bin2hex($value2)));
                }
            }
            //转换成16进制去空格--faxing
            if(count($faxing)!=0){
                foreach ($faxing as $key2 => $value2) {
                    $faxing[$key2] = hex2bin(str_replace('20','',bin2hex($value2)));
                }
            }

            //导演---去英文
            if(count($daoyan)!=0){
                foreach ($daoyan as $key2 => $value2) {
                    preg_match('/([^a-zA-Z0-9]*)?/', $value2, $daoyan[$key2]);
                    $daoyan[$key2] = $daoyan[$key2][1];
                }
            }
            //zhuyan---去英文
            if(count($zhuyan)!=0){
                foreach ($zhuyan as $key2 => $value2) {
                    preg_match('/([^a-zA-Z0-9]*)?/', $value2, $zhuyan[$key2]);
                    $zhuyan[$key2] = $zhuyan[$key2][1];
                }
            }
            //zhizuo---去英文
            if(count($zhizuo)!=0){
                foreach ($zhizuo as $key2 => $value2) {
                    preg_match('/([^a-zA-Z0-9]*)?/', $value2, $zhizuo[$key2]);
                    $zhizuo[$key2] = $zhizuo[$key2][1];
                }
            }
            //faxing---去英文
            if(count($faxing)!=0){
                foreach ($faxing as $key2 => $value2) {
                    preg_match('/([^a-zA-Z0-9]*)?/', $value2, $faxing[$key2]);
                    $faxing[$key2] = $faxing[$key2][1];
                }
            }
            //个属性数据合并--daoyan
            if(count($daoyan)!=0){
                $str = $daoyan[0];
                for($i = 1 ; $i<count($daoyan) ; $i++){
                    $str .= ";".$daoyan[$i];
                }
            }
            $daoyan = $str;
            //个属性数据合并--$zhuyan
            if(count($zhuyan)!=0){
                $str = $zhuyan[0];
                for($i = 1 ; $i<count($zhuyan) ; $i++){
                    $str .= ";".$zhuyan[$i];
                }
                $zhuyan = $str;
            }

            //个属性数据合并--$zhizuo
            if(count($zhizuo)!=0){
                $str = $zhizuo[0];
                for($i = 1 ; $i<count($zhizuo) ; $i++){
                    $str .= ";".$zhizuo[$i];
                }
                $zhizuo = $str;
            }
            //个属性数据合并--faxing
            if(count($faxing)!=0){
                $str = $faxing[0];
                for($i = 1 ; $i<count($faxing) ; $i++){
                    $str .= ";".$faxing[$i];
                }
                $faxing = $str;
            }
            var_dump($value);
            mysqli_query($con, "insert into yien(movie,daoyan,zhuyan,zhipian,faxing,id) values('喵星人','{$daoyan}','{$zhuyan}','{$zhizuo}','{$faxing}',1);");
            sleep(3);
        }
        else {
            continue;
        }
    }

 ?>
