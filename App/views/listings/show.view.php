<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>
<main class="container mx-auto px-4 py-8">
      <!-- Job Header Section -->
      <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl p-6 mb-8 shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
          <div class="mb-4 md:mb-0">
            <a href="/listings" class="flex items-center text-blue-100 hover:text-white transition-colors">
              <i class="fas fa-arrow-left mr-2"></i>
              Back to Listings
            </a>
            <h1 class="text-3xl font-bold mt-4"><?= $listing->title ?? '' ?></h1>
            <div class="flex items-center flex-wrap gap-2 mt-2">
              <span class="bg-blue-100/20 px-3 py-1 rounded-full text-sm"><?= $listing->job_type->type_name ?></span>
              <span class="bg-blue-100/20 px-3 py-1 rounded-full text-sm">
                <?php if($listing->remote === 'YES'): ?>
                    Remote
                <?php else : ?>
                    On-site
                <?php endif; ?>
              </span>
              <span class="flex items-center">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <?= $listing->city . ", " . $listing->state ?>
              </span>
            </div>
          </div>
          <?php if(Framework\Authorization::isOwner($company->id)) : ?>
            <div class="flex gap-3">
                <a href="/listings/edit/<?= $listing->id ?>" class="px-6 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                    Edit Job
                </a>
                <form action="" method="POST"></form>
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="px-6 py-2 bg-red-500/90 hover:bg-red-600 rounded-lg transition-colors">
                        Delete
                    </button>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="grid lg:grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
          <!-- Job Highlights -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
              <div class="p-4">
                <p class="text-gray-500 text-sm">Salary</p>
                <p class="text-xl font-bold text-blue-600"><?= formateSalary($listing->salary) ?></p>
              </div>
              <!-- <div class="p-4">
                <p class="text-gray-500 text-sm">Experience</p>
                <p class="text-xl font-bold text-blue-600">3+ Years</p>
              </div> -->
              <div class="p-4">
                <p class="text-gray-500 text-sm">Applications</p>
                <p class="text-xl font-bold text-blue-600"><?= $listing->application_count ?? '0' ?></p>
              </div>
            </div>
          </div>

          <!-- Job Details -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <h2 class="text-2xl font-bold mb-4 text-blue-800">Job Description</h2>
            <p class="text-gray-600 leading-relaxed">
              <?= $listing->description ?>
            </p>

            <div class="mt-8">
              <h3 class="text-xl font-semibold mb-3 text-blue-700">
                <i class="fas fa-tasks mr-2"></i>Requirements
              </h3>
              <?php $requirementsArr = strToArr($listing->requirements)  ?>
              <ul class="list-disc pl-6 space-y-2 text-gray-600">
                <?php foreach($requirementsArr as $requirement): ?>
                    <li><?= $requirement ?></li>
                <?php endforeach; ?>
              </ul>
            </div>

            <div class="mt-8">
              <h3 class="text-xl font-semibold mb-3 text-blue-700">
                <i class="fas fa-star mr-2"></i>Benefits
              </h3>
              <?php $benefitsArr = strToArr($listing->benefits)  ?>
              <ul class="list-disc pl-6 space-y-2 text-gray-600">
                <?php foreach($benefitsArr as $benefit): ?>
                    <li><?= $benefit ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6">
          <!-- Company Card -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="text-center mb-4">
              <img src="/uploads/company/logo/<?= $company->company_logo ?>" alt="Company Logo" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
              <h2 class="text-xl font-bold text-blue-800"><?= $company->company_name ?? '' ?></h2>
              <!-- <p class="text-gray-500">Technology & Software Development</p> -->
            </div>
            <div class="space-y-4">
              <?php if(!empty($company->company_website)): ?>
              <div class="flex items-start">
                <i class="fas fa-globe text-blue-600 mt-1 mr-3"></i>
                <div>
                  <p class="font-medium">Website</p>
                  <a href="<?= $company->company_website ?>" target="_blank" class="text-blue-600 hover:underline"><?= $company->company_website ?></a>
                </div>
              </div>
              <?php endif; ?>
              <div class="flex items-start">
                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3"></i>
                <div>
                  <p class="font-medium">Location</p>
                  <p class="text-gray-600"><?= $company->city . ", " . $company->state ?></p>
                </div>
              </div>
              <div class="flex items-start">
                <i class="fas fa-clock text-blue-600 mt-1 mr-3"></i>
                <div>
                  <p class="font-medium">Posted</p>
                  <p class="text-gray-600"><?= get_time_ago($listing->created_at) ?></p>
                </div>
              </div>
            </div>

            <a href="/listings/apply/<?= $listing->id ?>" class="block text-center w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors">
              Apply Now
            </a>

          </div>
        </div>
      </div>
    </main>

<?php loadPartial('bottom-banner'); ?>
<?php loadPartial('footer') ?>