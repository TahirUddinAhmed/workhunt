<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
        inspectAndDie(Validation::match('test', 'test'));
        $query = 'SELECT * FROM listings';
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
        // $id = $_GET['id'] ?? '';
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
        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = 1;

        $newListingData = array_map('sanitize', $newListingData);


        inspectAndDie($newListingData);
    }
}