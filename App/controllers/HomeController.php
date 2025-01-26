<?php

namespace App\Controllers;

use App\Models\Listing;

class HomeController {
    protected $listings;

    public function __construct() {
        $this->listings = new Listing();
    }

    /**
     * Show the latest Listings
     *
     * @return void
     */
    public function index() {
        $listings = $this->listings->findAll();

        foreach($listings as $listing) {
            $listing->job_type = $this->listings->jobType($listing->job_type_id);
        }

        loadView('home', [
            'listings' => $listings
        ]);
    }
}