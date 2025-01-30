<?php 

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use Framework\Session;

class User extends Model {
    protected $table = "users";

    /**
     * Get jobseeker associated with this user
     * 
     * @return object|bool
     */
    public function getJobSeeker() {
        $jobSeekerId = Session::get('user')['id'];

        $params = [
            'id' => $jobSeekerId
        ];

        $query = "SELECT * FROM jobseeker WHERE id = :id";

        return $this->db->query($query, $params)->fetch();
    }

    /**
     * Get employer associated with this user 
     * 
     * @return object|bool
     */
    public function getEmployer() {
        $employerId = (int) Session::get('user')['id'];

        $params = [
            'id' => $employerId
        ];

        $query = "SELECT * FROM employer WHERE id = :id";

        return $this->db->query($query, $params)->fetch();
    }

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