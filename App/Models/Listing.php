<?php 

namespace App\Models;

use Framework\Model;


class Listing extends Model{
    protected $table = "listings";

    /**
     * get application associated with a listing
     *
     * @param obj $listings
     * @return object|null
     */
    public function getApplications($listingId) {
        $query = "SELECT * FROM applications WHERE listings_id = :listings_id";

        $params = [
            'listings_id' => $listingId
        ];

        return $this->db->query($query, $params)->fetchAll();
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

    /**
     * Search listings by keyword and location
     * 
     * @param String $keywords
     * @param String $location
     * @return object
     */
    public function searchListing($keywords, $location) {
        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND (city LIKE :location OR state LIKE :location)";

        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        return $this->db->query($query, $params)->fetchAll();
    }
}