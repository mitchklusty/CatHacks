from flask import Flask, request, redirect
from twilio.twiml.messaging_response import MessagingResponse
import os
from twilio.rest import Client
import mysql.connector
import threading
import datetime
import time
from threading import Thread
MINMUM_WAGE = 8

app = Flask(__name__)

@app.route("/sms", methods=['GET', 'POST'])
def incoming_sms():
    """Send a dynamic reply to an incoming text message"""
    # Get the message the user sent our Twilio number
    now = datetime.datetime.now()
    phone = request.values.get('From', None)
    phone = phone[1:]
    body = request.values.get('Body', None).lower()

    # Start our TwiML response
    resp = MessagingResponse()

    # Determine the right reply for this message
    if 'quit' in body or 'leaving' in body or 'leave' in body:
        times = clock_out_sql(phone, now.hour, now.minute)
        format_float = "{:.2f}".format(MINMUM_WAGE*times)
        resp.message(f"Hate to see you go. You made ${format_float}")

    elif 'pay' in body:
        resp.message("You'll get your money, when you fix this darn door!")
    elif 'here' in body:
        clock_in_sql(phone, now.hour, now.minute)
    else:
        resp.message("bruh what does that mean")

    return str(resp)

def clock_in_sql(phone, hours, mins):
    try:
        connection = mysql.connector.connect(host='mysql.cs.uky.edu',
                                             database='tajo254',
                                             user='tajo254',
                                             password='90179T aj!',
                                             autocommit=True)
        sql_query = f"UPDATE employees SET is_Clocked = True, Clock_In_Hours = {hours}, Clock_In_Minutes = {mins} WHERE phone LIKE '{phone}'; "
        print(sql_query)
        cursor = connection.cursor()
        cursor.execute(sql_query)
        employees = cursor.fetchall()

        sql_query = f"SELECT * FROM employees WHERE phone LIKE {phone}; "
        cursor = connection.cursor()
        cursor.execute(sql_query)
        # get addresses
        employees = cursor.fetchall()
        # selected_addresses in form: [fname, lname, street address, city, state, zip]
        for row in employees:
            print(row)
    except mysql.connector.Error as e:
        print("Error reading data from SQL table", e)
    finally:
        if connection.is_connected():
            connection.close()
            cursor.close()
            print("SQL connection is closed")

def clock_out_sql(phone, hours, mins):
    try:
        connection = mysql.connector.connect(host='mysql.cs.uky.edu',
                                             database='tajo254',
                                             user='tajo254',
                                             password='90179T aj!',
                                             autocommit=True)
        sql_query = f"UPDATE employees SET is_Clocked = False, Clock_Out_Hours = {hours}, Clock_Out_Minutes = {mins} WHERE phone LIKE {phone}; "
        print(sql_query)
        cursor = connection.cursor()
        cursor.execute(sql_query)

        sql_query = f"SELECT * FROM employees WHERE phone LIKE {phone}; "
        cursor = connection.cursor()
        cursor.execute(sql_query)
        # get addresses
        employees = cursor.fetchall()
        # selected_addresses in form: [fname, lname, street address, city, state, zip]

        for row in employees:
            print(row)
            in_hours, in_mins, out_hours, out_mins = row[9], row[8], row[10], row[11]
            return out_hours-in_hours+((out_mins-in_mins)%60)/60
    except mysql.connector.Error as e:
        print("Error reading data from SQL table", e)
    finally:
        if connection.is_connected():
            connection.close()
            cursor.close()
            print("SQL connection is closed")


if __name__ == "__main__":
    app.run(debug=True)
