from pytz import utc
from pytz import timezone
from datetime import datetime
import sys
#"$user" "$ip" "$hostname"
import requests
import json
def runcmd(url):
    res = requests.get(url)
    #resjson = json.dumps(res.text)
    rj = json.loads(res.text)
    #print(rj["data"])
    region = "cn"
    if(rj["data"]["region"] == "台湾"):
        region = "tw"
    elif(rj["data"]["region"] == "香港"):
        region = "hk"
    elif(rj["data"]["region"] == "澳门"):
        region = "mk"
    else:
        region = rj["data"]["country_id"].lower()
    return region
def sshrecord():

	cst_tz = timezone('Asia/Shanghai')
	utc_tz = timezone('UTC')

	utcnow = datetime.utcnow()
	utcnow = utcnow.replace(tzinfo=utc_tz)
	china = utcnow.astimezone(cst_tz)
	print( "china : %s"%china.strftime('%Y-%m-%d %H:%M:%S'))
	#curl http://ipinfo.io/223.155.166.172
	region = runcmd("http://ip.taobao.com/service/getIpInfo.php?ip=163.19.9.247")#序列参数
	print(region)
def main():
	'''
	if(len(sys.argv) < 4):
		print("[*] must be 4 para")
		return
	usename = sys.argv[1]
	clientip = sys.argv[2]
	hostname = sys.argv[3]
	'''
	sshrecord()
if __name__ == "__main__":
	main()
