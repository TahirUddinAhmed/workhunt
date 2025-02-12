<?php 
namespace App\Models;

use Framework\Model;
use Framework\Session;

class Employer extends Model {
    protected $table = "employer";


    /**
     * Check if a user exists in the employer table 
     *   
     * @param int $userId
     * @return bool
     */
    public function isEmployer($userId) {
        $employerCount = $this->withCount($userId);

        return $employerCount > 0;
    }

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
     * Get the listings associated with this table id
     * 
     * @return object|bool
     */
    public function getListings() {
        $userId = Session::get('user')['id'];

        $params = [
            'user_id' => $userId
        ];

        $query = "SELECT * FROM listings WHERE user_id = :user_id";

        return $this->db->query($query, $params)->fetchAll();
    }

    /**
     * Get company details 
     * 
     * @param int $id
     * @return object|bool
     */
    public function companyDetails(int $id) {
        $query = "SELECT * FROM users u
                  JOIN employer e WHERE u.id = e.id AND u.id = :id";
        $params = [
            'id' => $id
        ];

        return $this->db->query($query, $params)->fetch();
    }

    /**
     * get number of records from the database 
     * 
     * @param int $id 
     * @return object|bool
     */
    public function countJobs($id)
    {
        $params = [
            'user_id' => $id
        ];
        return $this->getCount($params, 'listings');

       
    }
}