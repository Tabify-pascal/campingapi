<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiKeyModel extends Model
{
    protected $table = 'api_keys';
    protected $primaryKey = 'id';

    protected $allowedFields = ['api_key', 'user_id', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'api_key' => 'required|is_unique[api_keys.api_key]',
        'user_id' => 'permit_empty|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'api_key' => [
            'required' => 'API key is required',
            'is_unique' => 'API key must be unique',
        ],
        'user_id' => [
            'is_natural_no_zero' => 'User ID must be a positive integer',
        ],
    ];
}
