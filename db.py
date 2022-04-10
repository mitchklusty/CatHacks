import mysql.connector
import threading
import datetime
import time
from threading import Thread


def do_sql(hours, mins):
	try:
	    connection = mysql.connector.connect(host='mysql.cs.uky.edu',
	                                         database='tajo254',
	                                         user='tajo254',
	                                         password='')

	    sql_query = f"SELECT * FROM employees WHERE hours LIKE {hours} AND minutes LIKE {mins}; "
	    cursor = connection.cursor()
	    cursor.execute(sql_query)
	    # get addresses
	    employees = cursor.fetchall()
	    # selected_addresses in form: [fname, lname, street address, city, state, zip]
	    for row in employees:
	        fname, lname, phone, start_hour, start_min = row[0], row[1], row[2], row[3], row[4]
	        

	except mysql.connector.Error as e:
	    print("Error reading data from SQL table", e)
	finally:
	    if connection.is_connected():
	        connection.close()
	        cursor.close()
	        print("SQL connection is closed")

now = datetime.datetime.now() 
while now.second != 0:
	now = datetime.datetime.now()
while True:
	now = datetime.datetime.now() 
	print(now.hour, now.minute)
	thread = Thread(target = do_sql, args =(now.hour, now.minute,))
	thread.start()
	time.sleep(60)

