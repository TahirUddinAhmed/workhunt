<?php 

namespace App\Models;

use Framework\Model;


class Listing extends Model{
    protected $table = "listings";

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