<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthIdentitiesModel extends Model
{
    protected $table = 'auth_identities'; // The table name
    protected $primaryKey = 'id'; // Primary key of the table
    protected $allowedFields = ['user_id', 'type', 'secret', 'secret2', 'expires', 'extra', 'force_reset', 'last_used', 'created_at', 'updated_at'];
    protected $useTimestamps = true; // Auto-manage created_at and updated_at fields

}