<?php

namespace App\Models;

use CodeIgniter\Model;

class CampsiteSpotModel extends Model
{
    protected $table = 'campsite_spots';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'price',
        'number_of_guests',
        'name',
        'type',
    ];

    protected $useTimestamps = false;
}