<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // your database table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ✅ Find user by email
    public function findUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    // ✅ Create new account with password hashing
    public function createAccount(array $data)
    {
        if (empty($data['password'])) {
            return ['Password is required'];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        try {
            $this->insert($data);
            return true;
        } catch (\Exception $e) {
            return ['Database error: ' . $e->getMessage()];
        }
    }
}
