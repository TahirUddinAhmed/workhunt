<?php 

namespace App\Controllers;

use App\Models\JobSeeker;
use App\Models\User;
use Framework\Validation;
use Framework\Session;

class JobSeekerController {
    protected $user;
    protected $jobseeker;

    public function __construct()
    {
        $this->user = new User();
        $this->jobseeker = new JobSeeker();
    }


    public function dashboard() {
        $user = $this->jobseeker->getUser();
        $jobseeker = $this->user->getJobSeeker();

        if(!empty($jobseeker->skills)) {
            // convert the coma separated string into an array
            // Use preg_split to match commas outside parentheses
            $jobseeker->skills = preg_split('/,(?![^()]*\))/', $jobseeker->skills);

            // Trim any whitespace around the skills
            $jobseeker->skills = array_map('trim', $jobseeker->skills);
        }

        // inspect($jobseeker);
        loadView('users/jobseeker/dashboard', [
            'user' => $user,
            'jobseeker' => $jobseeker
        ]);
    }
}