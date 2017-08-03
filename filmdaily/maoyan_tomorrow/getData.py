#coding=utf-8
import sys
reload(sys)
import requests
import urllib
import json
from xml.dom.minidom import parse
import xml.dom.minidom
import random
sys.setdefaultencoding('utf8')
class xl():
        def pc(self,cookie,url):
            headers={
            'Host': 'piaofang.maoyan.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': '*/*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'X-Requested-With': 'XMLHttpRequest',
            'Uid': '9643321857998416fd6092742b0568ef282cc16d',
            'Referer': 'http://piaofang.maoyan.com/show',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            r=requests.get(url,headers=headers)
            return r.text

x=xl()
tomorrow = sys.argv[1]
cookie = '_lxsdk=1587192734419-09beebaa5e45ee-6d2d7243-1fa400-15871927345c8; __mta=41254301.1479374173391.1500029676300.1500029794523.811; __utma=17099173.1422519454.1479374174.1499933510.1500027351.73; __utmz=17099173.1479374174.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); _lx_utm=; _lx_utm=; __mta=216774953.1492607820320.1492607820320.1492607820320.1; _bsin_=[1,10,11,12,13,2,3,4,5,6,7,8,9]; wantindex-city={"city_tier":0,"city_id":0,"cityName":"%E5%85%A8%E5%9B%BD"}; _lxsdk_s=52a00da3bc2244f4a8a0cb5f588f%7C%7C34; __utmb=17099173.19.9.1500028025429; __utmc=17099173; theme=moviepro'
url = 'http://piaofang.maoyan.com/show?showDate='+tomorrow+'&periodType=0&showType=2'
#print url
print x.pc(cookie,url)
