import sqlite3
import os
#"$time" "$user" "$ip" "$hostname"
try:
    os.remove('database.db')
except:
    print("[!] no exit")
db = sqlite3.connect('database.db')
cursor = db.cursor()
cursor.execute('''
    CREATE TABLE sshrecord(sid INTEGER PRIMARY KEY autoincrement,usename TEXT,  clientip TEXT, hostname TEXT, region TEXT, postdate TEXT)
''')
db.commit()
exit(0)
cursor.execute('''
    CREATE TABLE cats(cid INTEGER PRIMARY KEY autoincrement, cats TEXT UNIQUE)
''')
db.commit()
cursor.execute('''
    CREATE TABLE tags(tid INTEGER PRIMARY KEY autoincrement, tags TEXT UNIQUE)
''')
db.commit()
