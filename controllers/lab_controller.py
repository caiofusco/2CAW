from flask import Blueprint, jsonify
from models.computer import Computer
from flask_login import login_required

lab = Blueprint('lab', __name__)

@lab.route('/api/computers/<int:lab_id>')
@login_required
def get_computers(lab_id):
    computers = Computer.get_by_lab(lab_id)
    return jsonify(computers)