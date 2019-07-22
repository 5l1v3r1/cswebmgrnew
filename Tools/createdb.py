import sqlite3
import os
try:
    os.remove('database.db') 
except:
    print("[!] no exit")
db = sqlite3.connect('database.db')
cursor = db.cursor()
cursor.execute('''
    CREATE TABLE article(aid INTEGER PRIMARY KEY, title TEXT,
                       href TEXT, postdate TEXT, cat_links TEXT, tag_links TEXT,flag INTEGER DEFAULT 0)
''')
db.commit()
cursor.execute('''
    CREATE TABLE cats(cid INTEGER PRIMARY KEY autoincrement, cats TEXT UNIQUE)
''')
db.commit()
cursor.execute('''
    CREATE TABLE tags(tid INTEGER PRIMARY KEY autoincrement, tags TEXT UNIQUE)
''')
db.commit()