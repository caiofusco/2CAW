from utils.database import mysql

class Computer:
    @staticmethod
    def get_by_lab(lab_id):
        cur = mysql.connection.cursor()
        cur.execute('''
            SELECT c.*, COALESCE(r.id, 0) as reserved 
            FROM computers c 
            LEFT JOIN reservations r ON c.id = r.computer_id 
            WHERE c.lab_id = %s
        ''', (lab_id,))
        computers = cur.fetchall()
        cur.close()
        return computers