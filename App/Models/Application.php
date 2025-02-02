<?php 

namespace App\Models;

use Framework\Model;

class Application extends Model {
    protected $table = "applications";


    /**
     * Get the listings associated with this application
     * 
     * @param int $listingId
     * @return object|null
     */
    public function getApplication($listingId) {
        $query = "SELECT * FROM applications WHERE listings_id = :listings_id";

        $params = [
            'listings_id' => $listingId
        ];

        $applications = $this->db->query($query, $params)->fetchAll();

        foreach($applications as $application) {
            $application->listing = $this->getListing($listingId);
            $application->jobSeeker = $this->getJobseeker($application->job_seeker_id);
            $application->user = $this->getUser($application->job_seeker_id);
        }
        // $application->jobSeeker = $this->db->query($query, $params)->fetch();
        // $application->user = $this->db->query($query, $params)->fetch();
        // get job seeker data associated with this application 
        // $job_seeker_id = $application->job_seeker_id;

        // $params = [
        //     'id' => $job_seeker_id
        // ];

        // $query = "SELECT * FROM jobseeker WHERE id = :id";
        // $application->jobSeeker = $this->db->query($query, $params)->fetch();

        // $query = "SELECT * FROM users WHERE id = :id";
        // $application->user = $this->db->query($query, $params)->fetch();

        // $application->listing = $this->getListing($listingId);

        return $applications;
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
        
        return $this->db->query($query, $params)->fetch();
    }
}