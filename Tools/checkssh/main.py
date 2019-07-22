from pytz import utc
from pytz import timezone
from datetime import datetime
import sys
#"$user" "$ip" "$hostname"
import subprocess
def runcmd(command):
    ret = subprocess.run(command,shell=True,stdout=subprocess.PIPE,stderr=subprocess.PIPE,timeout=1)
    if ret.returncode == 0:
        print("success:",ret)
    else:
        print("error:",ret)
def sshrecord():
 
	cst_tz = timezone('Asia/Shanghai')
	utc_tz = timezone('UTC')

	utcnow = datetime.utcnow()
	utcnow = utcnow.replace(tzinfo=utc_tz)
	china = utcnow.astimezone(cst_tz)
	print( "china : %s"%china.strftime('%Y-%m-%d %H:%M:%S'))
	#curl http://ipinfo.io/223.155.166.172
	runcmd(["ls","-a"])#序列参数
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