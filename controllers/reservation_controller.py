from flask import Blueprint, request, jsonify
from models.reservation import Reservation
from flask_login import login_required, current_user

reservation = Blueprint('reservation', __name__)

@reservation.route('/api/reserve', methods=['POST'])
@login_required
def make_reservation():
    data = request.json
    success = Reservation.create(
        data['computer_id'],
        current_user.id,
        data['date'],
        data['time_slot']
    )
    return jsonify({'status': 'success' if success else 'error'})