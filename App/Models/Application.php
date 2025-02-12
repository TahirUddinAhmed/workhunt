<?php 

namespace App\Models;

use Framework\Model;
use Framework\Session;

class Application extends Model {
    protected $table = "applications";


    /**
     * Count application by listingsid
     * 
     * @param int $listingId
     * @return void
     */
    public function countAppWithListingId($listingId) {
        // $userId = Session::get('user')['id'];


        $query = "SELECT COUNT(*) AS total_applications
                FROM applications a
                JOIN listings l ON a.listings_id = l.id
                WHERE a.listings_id = :listings_id;
                ";
        
        $params = [
            // 'user_id' => $userId,
            'listings_id' => $listingId
        ];

        return $this->db->query($query, $params)->fetchColumn();
    }

    /**
     * Count Application for employer 
     * 
     * @return void
     */
    public function countApplications() {
        $userId = Session::get('user')['id'];

        $query = "SELECT COUNT(*) AS total_applications
                FROM applications a
                JOIN listings l ON a.listings_id = l.id
                WHERE l.user_id = :user_id;
                ";
        
        $params = [
            'user_id' => $userId
        ];

        return $this->db->query($query, $params)->fetchColumn();
    }

    /**
     * Get count
     */
    public function countInterview() {
        $userId = Session::get('user')['id'];

        $query = "SELECT COUNT(*) AS total_applications
                  FROM applications a
                  JOIN listings l ON a.listings_id = l.id
                  WHERE l.user_id = :user_id AND a.status = :status";
        
        $params = [
            'user_id' => $userId,
            'status' => 'accepted'
        ];

        return $this->db->query($query, $params)->fetchColumn();
    }

    /**
     * Filter by field
     *
     * @param string $status
     * @return void
     */
    public function filterByStatus($status) {
        $userId = Session::get('user')['id'];

        $selectClouse = $this->getClause();
        $query = "SELECT {$selectClouse}
                FROM applications a 
                JOIN listings l ON a.listings_id = l.id 
                JOIN users seeker ON a.job_seeker_id = seeker.id
                JOIN jobseeker j ON a.job_seeker_id = j.id
                WHERE l.user_id = :user_id AND a.status = :status";

        $params = [
            'user_id' => $userId,
            'status' => $status
        ];

        return $this->db->query($query, $params)->fetchAll();
    }

    /**
     * get clause 
     *
     * @return string
     */
    public function getClause() {

        $tableAlias = [
            'a' => 'applications',
            'l' => 'listings',
            'seeker' => 'users',
            'j' => 'jobseeker'
        ];
        foreach($tableAlias as $alias => $table) {
            $columns = $this->getTableColumns($table);

            foreach($columns as $column) {
                $select[] = "$alias.$column AS {$alias}_$column";
            }
        }

        $selectClouse = implode(", ", $select);

        return $selectClouse;
    }
    /**
     * Get the listings associated with this application
     * 
     * @return object|null
     */
    public function getApplications() {
        $userId = Session::get('user')['id'];
        $selectClouse = $this->getClause();
        $query = "SELECT {$selectClouse} 
                FROM applications a 
                JOIN listings l ON a.listings_id = l.id 
                JOIN users seeker ON a.job_seeker_id = seeker.id
                JOIN jobseeker j ON a.job_seeker_id = j.id
                WHERE l.user_id = :user_id";

        $params = [
            'user_id' => $userId,
        ];

        // return $query;
        return $this->db->query($query, $params)->fetchAll();
    }

    /**
     * Get user 
     * 
     * @param int $userId
     * @return object|null
     */
    public function getUser($userId) {
        $params = [
            'id' => $userId
        ];

        $query = "SELECT * FROM users WHERE id = :id";
        return $this->db->query($query, $params)->fetch();
    }

    /**
     * get jobseeker
     *
     * @param [type] $jobseekerid
     * @return void
     */
    public function getJobseeker($jobseekerid) {
        $params = [
            'id' => $jobseekerid
        ];

        $query = "SELECT * FROM jobseeker WHERE id = :id";
        return $this->db->query($query, $params)->fetch();
    }
    /**
     * get listings
     * 
     * @param int $listingId
     * @return object|null
     */
    public function getListing($listingId) {
        $query = "SELECT * FROM listings WHERE id = :id";

        $params = [
            'id' => $listingId
        ];
        
        $listing = $this->db->query($query, $params)->fetch();

        return $listing;
    }

    /**
     * Get the job type associated with this listing 
     * 
     * @param int $jobTypeId
     * @return object|null
     */
    public function jobType($jobTypeId) {
        $query = "SELECT * FROM job_types WHERE id = :id";

        $params = [
            'id' => $jobTypeId
        ];

        return $this->db->query($query, $params)->fetch();
    }
}