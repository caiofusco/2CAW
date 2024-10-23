from utils.database import mysql

class Lab:
    @staticmethod
    def get_all():
        cur = mysql.connection.cursor()
        cur.execute('SELECT * FROM labs')
        labs = cur.fetchall()
        cur.close()
        return labs