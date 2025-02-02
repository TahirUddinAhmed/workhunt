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

        // return $this->db->query("SELECT COUNT(*) FROM listings WHERE user_id = :user_id", $params)->fetchColumn();
    }
    /**
     * get number of records from the database 
     * 
     * @param int $id 
     * @return object|bool
     */
    public function countApplication()
    {
        $myListings = $this->getListings();

        inspect($myListings);
        $countListings = $this->countJobs(Session::get('user')['id']);


        // // inspect($countListings);
        if($countListings > 0) {
            // extract the listing id 
            // $listing_id = $myListings->id;

            $params = [
                'listings_id' => $myListings->id
            ];

            $count_application = $this->getCount($params, 'applications');

            return $count_application;
        }
        
        // $count = [];
        // foreach($myListings as $listing => $value) {
        //     // $listing_id = $listing->id;
        //     // $coun
        //     // inspect($listing);  
        //     inspect($listing);
        // }


        // return $this->db->query("SELECT COUNT(*) FROM listings WHERE user_id = :user_id", $params)->fetchColumn();
    }
}