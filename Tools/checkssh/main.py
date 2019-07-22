from pytz import utc
from pytz import timezone
from datetime import datetime
import sys
import sqlite3
import os
#"$user" "$ip" "$hostname"
import requests
import json

SQLITE_FILE = "database.db"
def geninsertsql(tname,items):
	insert_sql = "insert into {0}({1}) values ({2})".format(tname, 
																','.join(items),
																', '.join(['?'] * len(items)))
	return insert_sql
def sshrecord(usename,clientip,hostname):
	cst_tz = timezone('Asia/Shanghai')
	utc_tz = timezone('UTC')
	utcnow = datetime.utcnow()
	utcnow = utcnow.replace(tzinfo=utc_tz)
	cdate = utcnow.astimezone(cst_tz)
	print( "china : %s"%cdate.strftime('%Y-%m-%d %H:%M:%S'))
	#curl http://ipinfo.io/223.155.166.172  163.19.9.247
	url = "http://ip.taobao.com/service/getIpInfo.php?ip="+str(clientip)
	region = "cn"
	flag = 0
	while flag <= 3:
		try:
			res = requests.get(url)
			#resjson = json.dumps(res.text)
			rj = json.loads(res.text)
			#print(rj["data"])	
			if(rj["data"]["region"] == "台湾"):
				region = "tw"
			elif(rj["data"]["region"] == "香港"):
				region = "hk"
			elif(rj["data"]["region"] == "澳门"):
				region = "mk"
			else:
				region = rj["data"]["country_id"].lower()
			break
		except:
			flag = flag + 1
			continue
	print(region)
	conn = sqlite3.connect(SQLITE_FILE)
	cur =  conn.cursor()
	items= ['usename','clientip','hostname','region','postdate']
	insert_sql = geninsertsql("sshrecord",items)
	cur.execute(insert_sql, (usename,clientip,hostname,region,cdate.strftime('%Y-%m-%d %H:%M:%S')))
	conn.commit()
	#print(insert_sql)
	
def main():
	if(len(sys.argv) < 4):
		print("[*] must be 4 para")
		return
	usename = sys.argv[1]
	clientip = sys.argv[2]
	hostname = sys.argv[3]
	sshrecord(usename,clientip,hostname)
if __name__ == "__main__":
	main()
