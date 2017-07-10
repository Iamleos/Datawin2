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
        def pc(self,name,cookie,url):
            url_name=urllib.quote(name)
            headers={
            'Host': 'index.toutiao.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': '*/*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate,br',
            'X-Requested-With': 'XMLHttpRequest',
            'Referer': 'https://index.toutiao.com/keyword/related?keywords%5B%5D='+url_name,
            'Cookie': cookie,
            'Connection': 'keep-alive',
            'Cache-Control': 'max-age=0',
            }
            r=requests.get(url,headers=headers)
            return r.text

x=xl()
name = sys.argv[1]
last_date = sys.argv[2]
now_date = sys.argv[3]
cookie = 'tt_webid=60720995311; UM_distinctid=15d1cb513a636e-0b452e1b2f8904-6d2d7243-1fa400-15d1cb513a7918; uuid="w:4d6375633f8b4ec4b8e432579d6c6ef1"; _ga=GA1.2.1067342748.1499425218; _gid=GA1.2.2133898228.1499425218; XSRF-TOKEN=eyJpdiI6InN0M0JrTzNhdEN6bWlya3E5N2F3T3c9PSIsInZhbHVlIjoiUnZ2NW9mRnhRaU02djl2N2crNHUycU1JQ1FhTmtLTnNqSmNyVjNpU0g0OHkrMXBPSW12c2EwY3l5ekZxS2dTZGx3Qk9aOEJ0YVhQTDVlcVFBRmhJZHc9PSIsIm1hYyI6ImNhNDAxZTg3NWRmMDA0NmYzZmM2MmVkMGExYmRlYzIwODEyMWFjODNlNDA5NjNhMjFlZDk0MWEwNmNiZTM0MjAifQ%3D%3D; laravel_session=eyJpdiI6IlV4U2p4aytwMERcL1hxaUlDTjc3RmdBPT0iLCJ2YWx1ZSI6InpYbVNzdXlZaGRpMkNPejFvcDI3TGRLMUJGYXdCK1FUNjlDYjdMYXMwbEhnYlwvSUhEWW05VGtvV3MyNjFNUjVSTjhNbjFvZHo0aVhZOE92ODZLbHFyQT09IiwibWFjIjoiODk5OGRkZTY5MzIzYmExYmM4YmE1MmNkYjYzYzNlMGZlODllMGY0Y2Y4N2I2NDg4OTFiNWEyMzhlYmI2MDFhZSJ9; _ba=BA0.2-20170707-5156e-WvNTCTVXqVVQDHufSLNB; _gat=1'
url = 'https://index.toutiao.com/api/keyword/related/keywords?region=0&category=0&keyword='+urllib.quote(name)+'&start='+last_date+'&end='+now_date+'&is_hourly=0'
#print url
print x.pc(name,cookie,url)
