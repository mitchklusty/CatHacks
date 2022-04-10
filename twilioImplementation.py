import os
from twilio.rest import Client
import mysql.connector
import threading
import datetime
import time
from threading import Thread
import receiveMessage

admin_phone_num = '+19377642249'
account_sid = 'AC13798fe920d3a1e041b56b5cd6b66ea5'
auth_token = '2c5b41a4b407ec8c723a9d557bc0e509'
client = Client(account_sid, auth_token)




def do_sql(hours, mins):
    try:
        connection = mysql.connector.connect(host='mysql.cs.uky.edu',
                                             database='tajo254',
                                             user='tajo254',
                                             password='90179T aj!')

        sql_query = f"SELECT * FROM employees WHERE hours LIKE {hours} AND minutes LIKE {mins}; "
        cursor = connection.cursor()
        cursor.execute(sql_query)
        # get addresses
        employees = cursor.fetchall()
        for row in employees:
            fname, lname, phone, start_hour, start_min = row[0], row[1], row[2], row[3], row[4]
            message = client.messages.create(
                body= fname + '. It is time to clock in! Text \'here\' to clock in!',
                from_= admin_phone_num,
                to='+' + phone
            )
        sql_query = f"SELECT * FROM employees WHERE out_hours LIKE {hours} AND out_minutes LIKE {mins}; "
        cursor = connection.cursor()
        cursor.execute(sql_query)
        # get addresses
        employees = cursor.fetchall()
        for row in employees:
            fname, lname, phone, start_hour, start_min = row[0], row[1], row[2], row[3], row[4]
            message = client.messages.create(
                body=fname + '. It is time to clock out! Text \'leave\' to clock out!',
                from_=admin_phone_num,
                to='+' + phone
            )


    except mysql.connector.Error as e:
        print("Error reading data from SQL table", e)
    finally:
        if connection.is_connected():
            connection.close()
            cursor.close()
            print("SQL connection is closed")


now = datetime.datetime.now()
# print(now.second, type(now.second))
while now.second != 0:
    # print(now.second)
    now = datetime.datetime.now()
while True:
    now = datetime.datetime.now()
    print(now.hour, now.minute)
    thread = Thread(target=do_sql, args=(now.hour, now.minute,))
    thread.start()
    time.sleep(60)


