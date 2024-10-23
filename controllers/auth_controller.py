# controllers/auth_controller.py
from flask import Blueprint, request, render_template, redirect, url_for, flash
from flask_login import login_user, logout_user, login_required
from models.user import User
from utils.database import mysql
from werkzeug.security import generate_password_hash, check_password_hash

auth = Blueprint('auth', __name__)

@auth.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form.get('email')
        password = request.form.get('password')
        
        cur = mysql.connection.cursor()
        cur.execute('SELECT * FROM users WHERE email = %s', (email,))
        user = cur.fetchone()
        cur.close()

        if user and check_password_hash(user[3], password):
            user_obj = User(user[0], user[1], user[2], user[4])
            login_user(user_obj)
            return redirect(url_for('index'))
        
        flash('Por favor, verifique suas credenciais e tente novamente.')
    return render_template('auth/login.html')

@auth.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        name = request.form.get('name')
        email = request.form.get('email')
        password = request.form.get('password')
        confirm_password = request.form.get('confirm_password')
        
        if password != confirm_password:
            flash('As senhas não coincidem.')
            return render_template('auth/register.html')
        
        cur = mysql.connection.cursor()
        
        # Verificar se o email já existe
        cur.execute('SELECT id FROM users WHERE email = %s', (email,))
        if cur.fetchone():
            cur.close()
            flash('Este email já está registrado.')
            return render_template('auth/register.html')
        
        # Criar novo usuário
        hashed_password = generate_password_hash(password)
        try:
            cur.execute(
                'INSERT INTO users (name, email, password, role) VALUES (%s, %s, %s, %s)',
                (name, email, hashed_password, 'student')
            )
            mysql.connection.commit()
            flash('Registro realizado com sucesso! Faça login para continuar.')
            return redirect(url_for('auth.login'))
        except Exception as e:
            mysql.connection.rollback()
            flash('Erro ao realizar o registro. Por favor, tente novamente.')
        finally:
            cur.close()
            
    return render_template('auth/register.html')

@auth.route('/logout')
@login_required
def logout():
    logout_user()
    flash('Você foi desconectado com sucesso.')
    return redirect(url_for('auth.login'))