# run.py
from flask import Flask, render_template
from flask_login import LoginManager, login_required
from config.config import Config
from utils.database import mysql
from controllers.auth_controller import auth
from controllers.lab_controller import lab
from controllers.reservation_controller import reservation
from models.user import User
from models.lab import Lab
from datetime import datetime

app = Flask(__name__)
app.config.from_object(Config)

# Inicializar extensões
mysql.init_app(app)
login_manager = LoginManager()
login_manager.init_app(app)
login_manager.login_view = 'auth.login'

@login_manager.user_loader
def load_user(user_id):
    return User.get_by_id(user_id)

# Registrar blueprints
app.register_blueprint(auth)
app.register_blueprint(lab)
app.register_blueprint(reservation)

@app.route('/')
@login_required
def index():
    labs = Lab.get_all()
    today = datetime.now().strftime('%Y-%m-%d')
    return render_template('index.html', labs=labs, today=today)

if __name__ == '__main__':
    app.run(debug=True)