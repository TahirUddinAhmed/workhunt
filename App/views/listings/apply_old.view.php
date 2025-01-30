<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<div class="container mx-auto grid sm:grid-cols-12 grid-col-2 sm:gap-4">
      <section class="p-4 mt-4 sm:col-span-8">
        <div class="rounded-lg shadow-md bg-white p-3">
          <div class="flex justify-between items-center">
            <a class="block p-4 text-blue-700" href="/listings">
              <i class="fa fa-arrow-alt-circle-left"></i>
              Back To Listings
            </a>
          </div>
          <div class="p-4">
            <h1 class="text-lg mb-4">Hello, <?= $job_seeker['name'] ?></h1>
            <form method="POST" action="/jobs/apply" enctype="multipart/form-data">
              <div class="mb-4">
                <label class="block text-gray-700" for="contact">Contact Number</label>
                <input
                  id="contact"
                  type="text"
                  name="contact"
                  class="w-full px-4 py-2 border rounded focus:outline-none"
                  placeholder="Enter phone"
                />
              </div>

              <div class="mb-4">
                <label class="block text-gray-700" for="years_of_exp"
                  >Years Of Experience</label
                >
                <select
                  id="job_type"
                  name="job_type"
                  class="w-full px-4 py-2 border rounded focus:outline-none"
                >
                  <option value="No Experience" selected>No Experience</option>
                  <option value="0 to 1 year">0 to 1 Year</option>
                  <option value="1 to 2 year">1 to 2 Year</option>
                  <option value="3 to 5 Year">3 to 5 Year</option>
                  <option value="More than 5 Years">More than 5 Years</option>
                </select>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700" for="upload_resume"
                  >Upload Resume</label
                >
                <input
                  id="upload_resume"
                  type="file"
                  name="upload_resume"
                  class="w-full px-4 py-2 border rounded focus:outline-none"
                />
              </div>

              <button
                type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
              >
                Apply
              </button>
            </form>
          </div>
        </div>
      </section>

      <section class="p-4 mt-4 sm:col-span-4">
        <div class="rounded-lg shadow-md bg-white p-3">
          <h2 class="text-xl mb-4 font-semibold text-center">Job Info</h2>

          <h3 class="text-lg font-semibold"><?= $listing->title ?></h3>
          <p class="text-md mb-2 text-gray-600"><?= $listing->job_type->type_name ?? '' ?></p>
          <h3 class="text-lg mb-2 font-semibold text-blue-500">Job Requirements</h3>
          <p class="mb-4"><?= $listing->requirements ?></p>
          <h3 class="text-lg mb-2 font-semibold text-blue-500">Location</h3>
          <p class="mb-4"><?= $listing->city ?></p>
          <h3 class="text-lg mb-2 font-semibold text-blue-500">Salary</h3>
          <p class="mb-4"><?= formateSalary($listing->salary) ?></p>
        </div>
      </section>
    </div>

<?php loadPartial('bottom-banner'); ?>
<?php loadPartial('footer') ?>