<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class ListingController {
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show All Listings
     *
     * @return void
     */
    public function index() {
        $query = 'SELECT * FROM listings ORDER BY id DESC';
        $listings = $this->db->query($query)->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);

    }

    /**
     * Show the Create Listing Form
     *
     * @return void
     */
    public function create() {
        loadView('listings/create');
    }

    /**
     * Show single Listing
     *
     * @param array $params
     * @return void
     */
    public function show($params) {
        $id = $params['id'] ?? '';

        $query = 'SELECT * FROM listings WHERE id = :id';
        $params = [
            'id'=> $id
        ];
        
        $listing = $this->db->query($query, $params)->fetch();
        
        if(!$listing) {
            ErrorController::notFound('Listing Not Found');
            return;
        }
        
        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Store data in database 
     * 
     * @return void
     */
    public function store() {
        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags' , 'company', 'address', 'city', 'state', 'phone', 'email'];
        
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = Session::get('user')['id'];

        $newListingData = array_map('sanitize', $newListingData);
        
        $requiredFields = ['title', 'description', 'email', 'city', 'state', 'salary'];
        
        $errors = [];

        foreach($requiredFields as $fields) {
            if(empty($newListingData[$fields]) || !Validation::string($newListingData[$fields])) {
                $errors[$fields] = ucfirst($fields) . ' is required'; 
            }
            // inspact($newListingData[$fields]);
        }
    

        if(!empty($errors)) {
            // Reload views with errors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            // Submit data
            $fields = [];
            foreach($newListingData as $field => $value) {
                $fields[] = $field;
            }

            // Convert the array intro string 
            $fields = implode(', ', $fields);

            // inspactAndDie($fields);

            $values = [];

            foreach($newListingData as $field => $value) {
                // Convert empty string to null
                if($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            // convert array into string 
            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES({$values})";
           
            $this->db->query($query, $newListingData);

            // $_SESSION['success_message'] = 'Listings added successfully';
            Session::set('success_message', 'listing added successfully');

            redirect('/listings');
        }
    }

    /**
     * Delete a listings
     * 
     * @param array $params
     * @return void
     */
    public function destroy($params) {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Authorization
        if(!Authorization::isOwner($listing->user_id)) {
            // $_SESSION['error_message'] = 'You are not authorized to delete this listing';
            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
            return redirect('/listings/'. $listing->id);
        }

        // Delete listing
        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        // Set flash message 
        // $_SESSION['success_message'] = 'Listing deleted successfully';
        Session::set('success_message', 'Listing deleted successfully');

        redirect('/listings');

    }

    /**
     * Show the Edit listing Form 
     * 
     * @param array $params
     * @return void
     */
    public function edit($params) {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        // fetch the listings 
        $query = 'SELECT * FROM listings WHERE id = :id';
        $listing = $this->db->query($query, $params)->fetch();

        // if listing not exits 
        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if(!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to edit this listings');
            return redirect('/listings/' . $listing->id);
        }
        

        // show the edit listing page 
        loadView('listings/edit', [
            'listing' => $listing
        ]);

    }

    /**
     * Update the listings 
     * 
     * @param array $params
     * @return void
     */
    public function update($params) {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $query = 'SELECT * FROM listings WHERE id = :id';
        $listing = $this->db->query($query, $params)->fetch();

        // Check if listing exits
        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }


        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email', 'tags'];

        $UpdateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $UpdateValues['user_id'] = Session::get('user')['id'];
        $UpdateValues['id'] = $id;

        $UpdateValues = array_map('sanitize', $UpdateValues);

        $requiredFields = ['title', 'salary', 'description', 'email', 'city', 'state'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($UpdateValues[$field]) || !Validation::string($UpdateValues[$field])) {
                $errors[$field] = ucfirst($field . ' is required');
            }
        }

        if(!empty($errors)) {
            loadView('/listings/edit', [
                'errors' => $errors,
                'listing' => $listing
            ]);
        } else {
            if(!Authorization::isOwner($listing->user_id)) {
                Session::setFlashMessage('error_message', 'Your are not authorized to update this listing');
                return redirect('/listings/' . $listing->id);
            }
            // update the listings 
            $Updatefields = [];

            foreach($UpdateValues as $field => $value) {
                $Updatefields[] = "{$field} = :{$field}";
            }

            // convert the array into string
            $Updatefields = implode(', ', $Updatefields);

            $query = "UPDATE listings SET {$Updatefields} WHERE id = :id";

            $this->db->query($query, $UpdateValues);
            // inspectAndDie($UpdateValues);

            // $_SESSION['success_message'] = 'Listing Updated Successfully';
            Session::getFlashMessage('success_message', 'Listing updated successfully');

            redirect('/listings');
        }



        
    }

    /**
     * Search listings by keywords/location
     * 
     * @return void
     */
    public function search() {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND (city LIKE :location OR state LIKE :location)";

        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $params)->fetchAll();
        
        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}