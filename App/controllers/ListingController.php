<?php

namespace App\Controllers;

use Framework\Database;

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

    public function show() {
        $id = $_GET['id'] ?? '';

        $query = 'SELECT * FROM listings WHERE id = :id';
        $params = [
            'id'=> $id
        ];
        
        $listing = $this->db->query($query, $params)->fetch();
        
        
        loadView('listings/show', [
            'listing' => $listing
        ]);
    }
}