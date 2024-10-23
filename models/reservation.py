from utils.database import mysql
from datetime import datetime

class Reservation:
    @staticmethod
    def create(computer_id, user_id, date, time_slot):
        cur = mysql.connection.cursor()
        try:
            cur.execute('''
                INSERT INTO reservations (computer_id, user_id, date, time_slot)
                VALUES (%s, %s, %s, %s)
            ''', (computer_id, user_id, date, time_slot))
            mysql.connection.commit()
            return True
        except Exception as e:
            print(e)
            mysql.connection.rollback()
            return False
        finally:
            cur.close()