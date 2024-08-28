<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'name',
        'phone_number',
        'email',
        'date_reservation',
        'guests',
        'spot',
        'comment',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'             => 'required|max_length[255]',
        'phone_number'     => 'required|max_length[20]',
        'email'            => 'required|valid_email|max_length[255]',
        'date_reservation' => 'required|valid_date',
        'guests'           => 'required|is_natural_no_zero',
        'spot'             => 'required|is_natural_no_zero',
        'comment'          => 'permit_empty|string',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'max_length' => 'Name cannot exceed 255 characters',
        ],
        'phone_number' => [
            'required' => 'Phone number is required',
            'max_length' => 'Phone number cannot exceed 20 characters',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Email must be valid',
            'max_length' => 'Email cannot exceed 255 characters',
        ],
        'date_reservation' => [
            'required' => 'Reservation date is required',
            'valid_date' => 'Please enter a valid date',
        ],
        'guests' => [
            'required' => 'Number of guests is required',
            'is_natural_no_zero' => 'Number of guests must be a positive integer',
        ],
        'spot' => [
            'required' => 'Spot is required',
            'is_natural_no_zero' => 'Spot must be a valid spot ID',
        ],
    ];
}
