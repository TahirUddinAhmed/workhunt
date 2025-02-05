<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>
<?php loadPartial('showcase-search'); ?>
<?php loadPartial('top-banner'); ?>
<!-- Job Listings -->
<?php loadPartial('message') ?>
<section>
  <div class="container mx-auto p-4 mt-4">
    <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">Recent Jobs</div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <?php foreach ($listings as $jobs): ?>
        <!-- Job Listing 1: Software Engineer -->
        <div class="rounded-lg shadow-md bg-white">
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-start mb-4">
                <img class="w-12 h-12 rounded-lg object-cover border border-gray-200" src="/../images/company-logo/<?= $jobs->company_logo ?>" alt="">
                <div class="ml-4">
                  <h2 class="text-xl font-semibold mb-1 text-gray-900"><?= $jobs->title ?></h2>
                  <div class="flex items-center text-sm text-gray-500 mt-1">
                    <span class="text-gray-500 text-sm"><?= "Posted " . get_time_ago($jobs->created_at) ?? '' ?></span>
                  </div>
                </div>
              </div>

              <div>
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                  <?= $jobs->job_type->type_name ?? '' ?>
                </span>
              </div>
            </div>
            <p class="text-gray-600 mb-5 leading-relaxed">
              <?= $jobs->description ?>
            </p>
            <div class="space-y-3 text-sm">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium"><?= formateSalary($jobs->salary) ?></span>
              </div>

              <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="flex items-center">
                  </strong> <?= $jobs->city ?>
                  <?php if ($jobs->remote === "Yes") : ?>
                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                      Remote
                    </span>
                  <?php else: ?>
                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                      On-site
                    </span>
                  <?php endif; ?>
                </span>
              </div>

              <div class="flex items-start">
                <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <div class="flex flex-wrap gap-2">
                <?php $tags = strToArr($jobs->tags); ?>
                  <?php if (!empty($tags)) : ?>
                    <?php foreach($tags as $tag) : ?>
                      <span class="px-2 py-1 bg-gray-100 rounded-md text-sm text-gray-600"><?= $tag ?></span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="mt-6">
              <a href="/listings/<?= $jobs->id ?>"
                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 duration-200">
                Details

                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
    <a href="/listings" class="block text-xl text-center">
      <i class="fa fa-arrow-alt-circle-right"></i>
      Show All Jobs
    </a>
</section>



<?php loadPartial('bottom-banner'); ?>
<?php loadPartial('footer') ?>