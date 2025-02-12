<?php

namespace App\Controllers;

use App\Models\JobSeeker;
use App\Models\JobTypes;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

use App\Models\Listing;
use App\Models\User;
use App\Models\Application;
use App\Models\Employer;

class ListingController {
    protected $listings;
    protected $jobTypes;
    private $jobSeeker;
    private $user;
    private $employer;
    private $application;

    public function __construct()
    {
        $this->listings = new Listing();
        $this->jobTypes = new JobTypes();
        $this->jobSeeker = new JobSeeker();
        $this->user = new User();
        $this->employer = new Employer();
        $this->application = new Application();
    }

    /**
     * Show All Listings
     *
     * @return void
     */
    public function index() {
        $listings = $this->listings->findAll();
        
        foreach($listings as $listing) {
            $listing->job_type = $this->listings->jobType($listing->job_type_id);
        }

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
        if(Authorization::isJobSeeker()) {
            Session::setFlashMessage('error_message', 'To list a job, first become a employer');
            return redirect('/listings');
        }

        $job_types = $this->jobTypes->findAll();

        
        loadView('listings/create', [
            'job_types' => $job_types
        ]);
    }

    /**
     * Show single Listing
     *
     * @param array $params
     * @return void
     */
    public function show($params) {
        $id = $params['id'] ?? '';

        $listing = $this->listings->find($id);
        $listing->job_type = $this->listings->jobType($listing->job_type_id);
        $listing->application_count = $this->application->countAppWithListingId($listing->id);
        $company = $this->employer->companyDetails($listing->user_id);
        
        // inspect($listing);

        if(!$listing) {
            ErrorController::notFound('Listing Not Found');
            return;
        }
        
        loadView('listings/show', [
            'listing' => $listing,
            'company' => $company
        ]);
    }

    /**
     * Store 
     * 
     * @return void
     */
    public function store() {
        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags' , 'job_type_id', 'remote', 'address', 'city', 'state', 'zip_code', 'company', 'company_description', 'company_website', 'phone', 'email'];
        
        $newDataListing = array_intersect_key($_POST, array_flip($allowedFields));

        // User Id 
        $newDataListing['user_id'] = Session::get('user')['id'];

        // Sanitize the user input 
        $sanitizeData = [];
        foreach($newDataListing as $key => $data) {
            $sanitizeData[$key] = sanitize($data);
        }
        $newDataListing = $sanitizeData;

        // required Fields
        $requiredFields = ['title', 'description', 'company', 'phone', 'email', 'city', 'state', 'salary', 'job_type_id'];
        
        $errors = [];
        
        foreach($requiredFields as $field) {
            if(empty($newDataListing[$field]) && !Validation::string($newDataListing[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }

        // Verify Image 
        if($_FILES['company_logo']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['company_logo'] = 'Company Logo is required';
        }
        
        // fetch job types from db 
        $job_types = $this->jobTypes->findAll();
        
        if(!empty($errors)) {
            // inspectAndDie($errors);
            loadView('/listings/create', [
                'errors' => $errors,
                'listings' => $newDataListing,
                'job_types' => $job_types
            ]);
            exit;
        } else {
            $allowed_ext = ['png', 'jpg', 'jpeg'];

            $company_logo = $_FILES['company_logo']['name'];
            $image_size = $_FILES['company_logo']['size'];
            $image_temp = $_FILES['company_logo']['tmp_name'];

            $newDataListing['company_logo'] = 'workhunt_company-' . uniqid() . $company_logo;
            
            $target_dir = basePath("public/images/company-logo/{$newDataListing['company_logo']}");

            // inspect($target_dir);
            
            $image_ext = explode('.', $newDataListing['company_logo']);
            $image_ext = strtolower(end($image_ext));

            // Validation
            $check = getimagesize($image_temp);

            // inspect($check);

            if($check == false) {
                loadView('/listings/create', [
                    'errors' => 'File is not a valid image',
                    'listings' => $newDataListing
                ]);
                exit;
            }


            if(in_array($image_ext, $allowed_ext)) {
                // check image size
                $maxFileSize = 1 * 1024 * 1024; // 1MB

                if($image_size > $maxFileSize) {
                    $errors['image_size'] = 'File is too large, Maximum file size is 1MB';
                    loadView('/listings/create', [
                        'errors' => $errors,
                        'listings' => $newDataListing
                    ]);
                    exit;
                } else {
                    if(move_uploaded_file($image_temp, $target_dir)) {
                        // insert 
                        $this->listings->insert($newDataListing);
                        
                        

                        // message 
                        Session::setFlashMessage('success_message', 'Listing created successfully');

                        redirect('/listings');
                    }
                }

                
            }

            
        }
        
        
        // inspectAndDie($errors);



    }

    /**
     * Delete a listings
     * 
     * @param array $params
     * @return void
     */
    public function destroy($params) {
        $id = $params['id'];

        $listing = $this->listings->find($id);

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

        // Path to the logo file 
        $logoPath = basePath("public/images/company-logo/{$listing->company_logo}");

        if(file_exists($logoPath)) {
            unlink($logoPath);
        }

        // Delete listing
        $this->listings->delete($listing->id);

        // Set flash message 
        // $_SESSION['success_message'] = 'Listing deleted successfully';
        Session::setFlashMessage('success_message', 'Listing deleted successfully');

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

        $listing = $this->listings->find($id);

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

        $listing = $this->listings->find($id);

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
            
            $this->listings->update($listing->id, $UpdateValues);
            // inspectAndDie($UpdateValues);

            // $_SESSION['success_message'] = 'Listing Updated Successfully';
            Session::setFlashMessage('success_message', 'Listing updated successfully');

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

        $listings = $this->listings->searchListing($keywords, $location);
        
        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }

    /**
     * Apply for job application 
     * 
     * @param array $params
     * @return void
     */
    public function apply($params) {
        $id = $params['id'] ?? '';

        $listing = $this->listings->find($id);

        $listing->job_type = $this->listings->jobType($listing->job_type_id);

        // Only job seekers can apply for job
        if(!Authorization::isJobSeeker()) {
            Session::setFlashMessage('error_message', 'Only Job Seeker can apply for jobs');
            redirect('/listings');
        }
        
        loadView('/listings/apply', [
            'listing' => $listing,
            'job_seeker' => $this->user->getJobSeeker(),
            'user' => $this->jobSeeker->getUser()
        ]);
    }

    /**
     * Submit job application
     * 
     * @param array $params 
     * @return void
     */
    public function application($params) {
        $listing_id = $params['id'];

        $listing = $this->listings->find($listing_id);

        $listing->job_type = $this->listings->jobType($listing->job_type_id);

        // Check if listing exits
        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // authorization 
        if(!Authorization::isJobSeeker()) {
            Session::setFlashMessage('error_message', 'You are not authorized to access this resource');
            return redirect('/');
        }
        $user = $this->jobSeeker->getUser();

        // form 
        $allowedFields = ['name', 'qualification', 'years_of_exp', 'contact', 'skills', 'cover_letter'];

        $UpdateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $requiredFields = ['name', 'qualification', 'years_of_exp', 'contact', 'skills'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($UpdateValues[$field]) && !Validation::string($UpdateValues[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }

        if(!empty($errors)) {
            loadView('/listings/apply', [
                'errors' => $errors,
                'listing' => $listing,
                'job_seeker' => $this->user->getJobSeeker(),
                'user' => $this->jobSeeker->getUser()
            ]);
            exit;
        }
        if(isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            // upload the resume 
            $UpdateValues['resume'] = $this->uploadResume($_FILES['resume'], $user->email, $listing, ['pdf'], 3); 
        } else {
          $UpdateValues['resume'] = $this->user->getJobSeeker()->resume;   
        }

        // user data 
        $UsersData = ['name', 'email', 'city', 'state'];
        $userUpdateValues = array_intersect_key($UpdateValues, array_flip($UsersData));
        // Update data 
        $this->user->update($user->id, $userUpdateValues);
        // Job seeker data 
        $jobSeeker = $this->user->getJobSeeker();
        $seekerData = ['contact', 'qualification', 'skills', 'resume', 'experience'];
        $jobSeekerUpdateValues = array_intersect_key($UpdateValues, array_flip($seekerData));
        // Update data 
        $this->jobSeeker->update($jobSeeker->id, $jobSeekerUpdateValues);

        // application data 
        $applyData = ['listings_id', 'job_seeker_id', 'resume', 'cover_letter'];
        $applyUpdateValues = array_intersect_key($UpdateValues, array_flip($applyData));
        $applyUpdateValues['listings_id'] = $listing->id;
        $applyUpdateValues['job_seeker_id'] = $jobSeeker->id;

        // Insert data into application tabel 
        $this->application->insert($applyUpdateValues);


       // message 
       Session::setFlashMessage('success_message', 'Congrats, You have applies for a job');

       redirect('/listings');
    }

    /**
     * upload Resume 
     * 
     * @param object $fileObj
     * @param array $allowed_ext 
     * @param string $filename
     * @param int $maxSize
     * @return void
     */
    public function uploadResume($fileObj,  $filename, $listings, $allowed_ext = ['pdf'], $maxSize = '1', ) {
        $resume_name = $fileObj['name'];
        $resume_size = $fileObj['size'];
        $resume_temp = $fileObj['tmp_name'];

        $ext = pathinfo($resume_name, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_ext)) {
                $errors['resume'] = "Only PDF are allowed";
            } else {

                // size 
                $maxSize = 3 * 1024 * 1024;

                if($resume_size > $maxSize) {
                    $errors['resume_size'] = "File size exceeds 3MB";
                    loadView('/listings/apply', [
                        'errors' => $errors,
                        'listing' => $listings,
                        'job_seeker' => $this->user->getJobSeeker(),
                        'user' => $this->jobSeeker->getUser()
                    ]);
                    exit;
                }
                // inspectAndDie($errors);
                $resume = 'workhunt-' . $filename . '-resume' . '.' .$ext;
                $target_dir = basePath("public/uploads/resumes/");
                if(!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_path = $target_dir . $resume;
                move_uploaded_file($resume_temp, $target_path);

                return $resume;
            }

    }
}