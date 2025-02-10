<?php 

namespace App\Controllers;

use App\Models\Application;
use App\Models\Employer;
use App\Models\Listing;
use Framework\Authorization;
use Framework\Session;
use Framework\Validation;
use App\Models\User;

class EmployerController {
    private $user;
    private $employer;
    private $application;
    private $listings;

    public function __construct()
    {
        $this->user = new User();
        $this->employer = new Employer();
        $this->application = new Application();
        $this->listings = new Listing();

        if(!Authorization::isEmployer()) {
            Session::setFlashMessage('error_message', 'You are not authorized to access this resource');
            return redirect('/');
        }
    }

    /**
     * Get Employer data 
     * 
     * @param mixed $user
     * @return object|null
     */
    public function EmployerData($user) {
        $user->listings_count = $this->employer->countJobs(Session::get('user')['id']);

        $user->application_count = $this->application->countApplications();

        $user->interview_count = $this->application->countInterview();

        return $user;
    }

    /**
     * Load the dashboard 
     * 
     * @return void
     */
    public function index() {
        $user = $this->employer->getUser();
        $employer = $this->user->getEmployer();

        
        $user = $this->EmployerData($user);
    
        loadView('users/employer/index', [
            'user' => $user,
            'employer' => $employer
        ]);
    }

    /**
     * Application Page load
     * 
     * @return void
     */
    public function applications() {
        $user = $this->employer->getUser();
        $employer = $this->user->getEmployer();

        $applications = $this->application->getApplications();

        // $test = $this->application->test();

        // inspect($test);

        // inspect($applicationData);
       loadView('/users/employer/application', [
            'user' => $user,
            'employer' => $employer,
            'applications' => $applications
       ]);
    }

    /**
     * Filter application
     */
    public function filter() {
        $user = $this->employer->getUser();
        $employer = $this->user->getEmployer();
        $filter = isset($_GET['status']) ? trim($_GET['status']) : '';

        if(!empty($filter)) {
            $applications = $this->application->filterByStatus($filter);
        } else {
            $applications = $this->application->getApplications();
        }

        // inspect($applications);
        loadView('/users/employer/application', [ 
            'user' => $user,
            'employer' => $employer,
            'applications' => $applications
       ]);
    }

    /**
     * Update users profile 
     * 
     * @param array $params
     * @return void
     */
    public function update($params) {
        $userId = $params['id'];

        $user = $this->user->find($userId);

        if(!$user) {
            ErrorController::notFound('Unauthorized users, please log in to our account');
            return;
        }

        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);

        // Validation 
        $errors = [];
        if(!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if(!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 character';
        }

        // check if user email 
        if($user->email !== $email) {
            // check if user entered email if exists in the database
            $checkEmail = $this->user->findByEmail($email);
            if($checkEmail) {
                $errors['email-found'] = "Can't update, {$email} is already exists";
            }
        }

        $user = $this->EmployerData($user);
        if(!empty($errors)) {
            loadView('users/employer/index', [
                'errors' => $errors,
                'user' => $user,
                'employer' => $this->user->getEmployer()
            ]);
            exit;
        } else {
            $userId = (int) Session::get('user')['id'];
            $data = [
                'name' => $name,
                'email' => $email
            ];
            $this->user->update($userId, $data);

            Session::setFlashMessage('success_message', 'Profile updated successfully');

            redirect('/users/employer/dashboard');
        }
    }
    /**
     * Update company information
     * 
     * @return void
     */
    public function companyUpdate() {
        
        $allowedFields = ['company_name', 'company_desc', 'company_website', 'contact', 'location'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['company_name', 'company_desc', 'contact', 'location'];

        $errors = [];

        // inspectAndDie($updateValues);

        foreach($requiredFields as $field) {
            if(empty($updateValues[$field])) {
                $errors[$field] = ucfirst($field . ' is required');
            }
        }
        
        if(!empty($errors)) {
            loadView('users/employer/index', [
                'errors' => $errors,
                'user' => $this->employer->getUser(),
                'employer' => $this->user->getEmployer()
            ]);
            exit;
        }
        // inspectAndDie($updateValues);

        if(isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
            $updateValues['company_logo'] = $this->uploadLogo($_FILES['company_logo'] , ['png', 'jpg', 'jpeg', 'webp'], 2); 
         } else {
            $updateValues['company_logo'] = $this->user->getEmployer()->company_logo;
        }

        
        $userId = (int) Session::get('user')['id'];
        $this->employer->update($userId, $updateValues);

        Session::setFlashMessage('success_message', 'Company information updated successfully');

        redirect('/users/employer/dashboard');
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
    public function uploadLogo($fileObj, $allowed_ext = ['pdf'], $maxSize = '1') {
        $file_name = $fileObj['name'];
        $file_size = $fileObj['size'];
        $file_temp = $fileObj['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_ext)) {
                $errors['company_logo'] = "Only png, jpg, webp are allowed!";
            } else {

                // size 
                $maxSize = 2 * 1024 * 1024;

                if($file_size > $maxSize) {
                    $errors['logo_size'] = "File size exceeds 2MB";
                    loadView('users/employer/index', [
                        'errors' => $errors,
                        'user' => $this->employer->getUser(),
                        'employer' => $this->user->getEmployer()
                    ]);
                    exit;
                }
                // inspectAndDie($errors);
                $logo = 'workhunt-' . uniqid() . '-logo' . '.' .$ext;
                $target_dir = basePath("public/uploads/company/logo/");
                if(!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_path = $target_dir . $logo;
                move_uploaded_file($file_temp, $target_path);

                return $logo;
            }

    }


    /**
     * Update Status
     * 
     * @param array $params
     * @return void
     */
    public function updateStatus($params) {
        $application_id = $params['id'];
        $application = $this->application->find($application_id);

        if(!$application) {
            ErrorController::notFound('resourse not found');
            return;
        }

        $allowedFields = ['status'];
        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        // sanitize
        $updateValues = array_map('sanitize', $updateValues);

        // validate 
        $errors = [];
        if(empty($updateValues['status'])) {
            $errors['empty'] = "Can't update, try again";
        }
        // validate values 
        $requiredValues = ['accepted', 'rejected', 'pending'];

        if(!in_array($updateValues['status'], $requiredValues)) {
            $errors['reject'] = "Don't try to the change value, try again";
        }
        $user = $this->employer->getUser();
        $applicationsData = $this->application->getApplications();


        if(!empty($errors)) {
            loadView('/users/employer/application', [
                'errors' => $errors,
                'user' => $user,
                'applications' => $applicationsData
           ]);
           exit;
        } 

        $this->application->update($application->id, $updateValues);

        Session::setFlashMessage('success_message', 'Application status updated successfully');

        redirect('/users/employer/dashboard/applications');

    }

    /**
     * Download Resume 
     * 
     * @param array $params
     * @return void
     */
    public function downloadResume($params) {
        $id = $params['id'];

        $application = $this->application->find($id);

        if(!$application) {
            ErrorController::notFound();
            return;
        }

        $resumePath = basePath("public/uploads/resumes/{$application->resume}");

        if(!file_exists($resumePath)) {
            Session::setFlashMessage('error_message', 'Resume not found');
            redirect('/users/employer/dashboard/applications');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($resumePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($resumePath));
        readfile($resumePath);

        exit;
    }
    /**
     * My Listings page 
     * 
     * @return void
     */
    public function myListings() {
        $user = $this->employer->getUser();
        $listings = $this->employer->getListings();

        $listing_count = 0;
        foreach($listings as $listing) {
            $listing->job_type = $this->listings->jobType($listing->job_type_id);
            $listing->application_count = $this->application->countAppWithListingId($listing->id);
            $listing_count += $this->listings->withCount($listing->id);
        }

        loadView('/users/employer/my-listings', [
            'user' => $user,
            'listing_count' => $listing_count,
            'listings' => $listings
        ]);
    }
}