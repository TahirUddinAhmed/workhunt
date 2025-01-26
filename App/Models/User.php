<?php 

declare(strict_types=1);

namespace App\Models;

use Framework\Model;

class User extends Model {
    protected $table = "users";

    /**
     * Find a user by email
     * 
     * @param string $email
     * @return object|bool
     */
    public function findByEmail(string $email) : object|bool {
        $params = [
            'email' => $email
        ];

        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->query($query, $params)->fetch();
    }

    /**
     * Verify Password 
     *
     * @param string $password
     * @param string $hash
     * @return Bool
     */
    public function verifyPassword(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }
}