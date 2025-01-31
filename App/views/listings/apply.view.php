<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<main class="container mx-auto px-4 py-12">
      <div class="max-w-6xl mx-auto grid lg:grid-cols-5 gap-8">
        <!-- Job Summary Card -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200 sticky top-6">
            <div class="mb-6">
              <div class="flex items-center justify-between mb-4">
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                    <?= $listing->job_type->type_name ?? '' ?>
                </span>
                <span class="text-gray-500 text-sm"><?= "Posted " . get_time_ago($listing->created_at) ?></span>
              </div>
              <h2 class="text-2xl font-bold text-gray-900 mb-2"><?= $listing->title ?></h2>
              <div class="flex items-center text-sm space-x-4 text-gray-600">
                <span class="flex items-center">
                  <i class="fas fa-building mr-2 text-purple-600"></i><?= $listing->company ?? '' ?>
                </span>
                <span class="flex items-center">
                  <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                  <?php if($listing->remote === "Yes"): ?>
                    Remote
                  <?php else : ?>
                    On-site
                  <?php endif; ?>
                </span>
              </div>
            </div>
        
            <div class="space-y-4">
              <div class="p-3 bg-gray-50 rounded-lg">
                <div class="mb-4">
                  <p class="text-sm text-gray-500">Benefits</p>
                  <p class="font-semibold text-gray-900"><?= $listing->benefits ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Salary</p>
                  <p class="font-semibold text-gray-900"><?= formateSalary($listing->salary) ?></p>
                </div>
              </div>

              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Key Requirements</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                <?php $requirementsArr = strToArr($listing->requirements) ?>
                <?php foreach($requirementsArr as $requirement) : ?>  
                <li><?= $requirement ?></li>
                <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-3">
          <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-8">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">Application Form</h1>
              <p class="text-gray-500">Complete this form to apply for the position</p>
            </div>

            <form method="POST" action="/listings/apply/<?= $listing->id ?>" class="space-y-6" enctype="multipart/form-data">
              <!-- Personal Information -->
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input 
                      type="text" 
                      name="name"
                      class="w-full pl-10 pr-4 py-3 border <?= isset($errors['name']) ? 'border-red-400' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                      placeholder="John Doe"
                      value="<?= $user->name ?? '' ?>"
                    >
                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                  </div>
                  <span class="text-red-500"><?= $errors['name'] ?? '' ?></span>
                </div>
        
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Qualification <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input 
                      type="text" 
                      name="qualification"
                      class="w-full pl-10 pr-4 py-3 border  <?= isset($errors['qualification']) ? 'border-red-400' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                      placeholder="john@example.com"
                      value="<?= $job_seeker->qualification ?? '' ?>"
                    >
                    <i class="fa-solid fa-briefcase absolute text-blue-900 left-3 top-3.5 text-gray-400"></i>
                  </div>
                  <span class="text-red-500"><?= $errors['qualification'] ?? '' ?></span>
                </div>
              </div>

              <!-- Experience & contact -->
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Years of Experience <span class="text-red-500">*</span>
                  </label>
                  <select class="w-full px-4 py-3 border <?= isset($errors['years_of_exp']) ? 'border-red-400' : 'border-gray-300' ?>  rounded-lg focus:ring-2 focus:ring-purple-500" name="years_of_exp">
                    <option value="" selected>Select experience</option>
                    <option value="0-1 years">0-1 years</option>
                    <option value="2-4 years">2-4 years</option>
                    <option value="5-7 years">5-7 years</option>
                    <option value="8+ years">8+ years</option>
                  </select>
                  <span class="text-red-500"><?= $errors['years_of_exp'] ?? '' ?></span>
                </div>
        
                <div>
                  <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">
                    Contact <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input 
                      type="contact" 
                      name="contact"
                      class="w-full pl-10 pr-4 py-3 border <?= isset($errors['contact']) ? 'border-red-400' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                      placeholder="john@example.com"
                      value="<?= $job_seeker->contact ?? '' ?>"
                    >
                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                  </div>
                  <span class="text-red-500"><?= $errors['contact'] ?? '' ?></span>
                </div>

              </div>
        
              
        
              <!-- Resume Upload Section -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  Resume <span class="text-red-500">*</span>
                </label>
                
                <?php if(!empty($job_seeker->resume)) : ?>
                <!-- Existing Resume -->
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <i class="fas fa-file-pdf text-green-600 text-lg"></i>
                      <div>
                        <p class="text-sm font-medium text-gray-900"><?= $job_seeker->resume ?></p>
                        <p class="text-xs text-gray-500">Uploaded: <?= get_time_ago($job_seeker->created_at) ?></p>
                      </div>
                    </div>
                    <!-- <button type="button" class="text-red-600 hover:text-red-700 text-sm">
                      Remove
                    </button> -->
                  </div>
                </div>
                <?php endif; ?>
                  
                <!-- New Upload Area -->
                <div class="border-2 border-dashed <?= isset($errors['resume_size']) ? 'border-red-400' : 'border-gray-300' ?> rounded-lg p-6 text-center hover:border-green-500 transition-colors cursor-pointer">
                  <div class="mb-2 text-gray-600">
                    <i class="fas fa-cloud-upload-alt text-3xl"></i>
                  </div>
                  <div class="mt-4 text-sm/6 text-gray-600">
                      <label for="upload_resume" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span class="text-green-600 hover:underline">browse files</span>
                        <input id="upload_resume" name="resume" type="file" class="sr-only">
                      </label>
                      <p class="show-file-info text-sm/5 text-gray-800 mb-4"></p>
                    </div>  
                  <p class="text-xs text-gray-500 mt-2">
                    <?php if(!empty($errors['resume_size'])) : ?>
                      <span class="text-red-500"><?= $errors['resume_size']?></span>
                    <?php else : ?>
                     PDF (Max 5MB)
                    <?php endif; ?>
                  </p>
                  
                  <p class="text-sm text-gray-600 mt-3">
                    Want to update? Select a new file to replace current resume
                  </p>
                </div>
              </div>
              

              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2" for="skills">
                    Skills 
                  </label>
                  <div class="relative">
                    <input 
                      type="text" 
                      name="skills"
                      class="w-full pl-10 pr-4 py-3 border <?= isset($errors['skills']) ? 'border-red-400' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                      placeholder=""
                      value="<?= $job_seeker->skills ?? '' ?>"
                    >
                    <i class="fa-solid fa-lightbulb absolute left-3 top-3.5 text-gray-400"></i>
                  </div>
                  <span class="text-red-500"><?= $errors['skills'] ?? '' ?></span>
              </div>
              <div>
                  <label for="conver_letter" class="block text-sm font-medium text-gray-700 mb-2">
                    Cover Letter <span class="text-gray-500">(optional)</span>
                  </label>
                  <textarea 
                    name="cover_letter"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 h-32"
                    placeholder="Describe your qualifications..."
                  ></textarea>
              </div>
        
              <!-- Terms & Submission -->
              <div class="space-y-4">
                <div class="flex items-center">
                  <input 
                    type="checkbox" 
                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                    required
                  >
                  <label class="ml-2 text-sm text-gray-600">
                    I agree to the 
                    <a href="#" class="text-purple-600 hover:underline">terms of application</a>
                  </label>
                </div>
        
                <button 
                  type="submit" 
                  class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all"
                >
                  Submit Application
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </main>

<?php loadPartial('bottom-banner'); ?>
<?php loadPartial('footer') ?>