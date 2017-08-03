<?php
    include "/root/Datawin/filmdaily/lib_function/function.php";
    $con = getDB("movie");
    $url = "http://www.cbooo.cn/movies";
    $result = getResult($url);
    preg_match_all('/<h5 style="width:158px;" title="(.*)">/',$result,$name);
    preg_match_all('/<a target="_blank" href="(.*)" style/',$result,$href);
    foreach ($name[1] as $key => $value) {
        $url = $href[1][$key];
        $result = getResult($url);
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
        //电影name--"&#183;"转换成　“·”
        $value = str_replace("&#183;","·",$value);
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
        mysqli_query($con, "insert into yien(movie,daoyan,zhuyan,zhipian,faxing,id) values('{$value}','{$daoyan}','{$zhuyan}','{$zhizuo}','{$faxing}',1);");
        sleep(3);

    }
 ?>
