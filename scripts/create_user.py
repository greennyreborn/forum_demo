import mysql.connector

import config
import user

conn = mysql.connector.connect(**config.db)


def insert_user_data(data):
    cursor = conn.cursor()

    sql_insert = "insert into `user` (uid, username, password, avatar, created_at, updated_at) values (%(uid)s, %(username)s, %(password)s, %(avatar)s, %(created_at)s, %(updated_at)s)"

    cursor.executemany(sql_insert, data)
    conn.commit()
    cursor.close()


user_data = user.generate_users()
insert_user_data(user_data)
conn.close()

print 'create user success!'
