from flask_login import UserMixin
from utils.database import mysql

class User(UserMixin):
    def __init__(self, id, name, email, role):
        self.id = id
        self.name = name
        self.email = email
        self.role = role

    @staticmethod
    def get_by_id(user_id):
        cur = mysql.connection.cursor()
        cur.execute('SELECT * FROM users WHERE id = %s', (user_id,))
        user = cur.fetchone()
        cur.close()
        
        if user:
            return User(user[0], user[1], user[2], user[4])
        return None