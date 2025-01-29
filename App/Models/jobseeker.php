<?php 
namespace App\Models;

use Framework\Model;
use Framework\Session;
class JobSeeker extends Model {
    protected $table = "jobseeker";

    /**
     * Get the user associated with this table id
     * 
     * @return object|bool
     */
    public function getUser() {
        $userId = Session::get('user')['id'];

        $params = [
            'id' => $userId
        ];

        $query = "SELECT * FROM users WHERE id = :id";

        return $this->db->query($query, $params)->fetch();
    }

    /**
     * Check if a user exists in the job seeker table 
     * 
     * @param int $userId
     * @return bool
     */
    public function isJobseeker($userId) {
        $jobSeekerCount = $this->withCount($userId);

        return $jobSeekerCount > 0;
    }
}