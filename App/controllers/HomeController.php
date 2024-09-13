<?php

namespace App\Controllers;

use Framework\Database;

class HomeController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the latest Listings
     *
     * @return void
     */
    public function index() {
        $query = 'SELECT * FROM listings';
        $listings = $this->db->query($query)->fetchAll();

        loadView('home', [
            'listings' => $listings
        ]);
    }
}