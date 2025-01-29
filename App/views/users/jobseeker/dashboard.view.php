<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<main class="container mx-auto p-4 mt-4">
    <section class="flex flex-col md:flex-row gap-6">
        <!-- Profile Info -->
        <div class="bg-white p-8 rounded-2xl shadow-md w-full md:w-2/3 mx-auto">
            <h3 class="text-3xl text-center font-extrabold mb-6 text-gray-800">
                Profile
            </h3>
            <div class="bg-gray-100 p-6 rounded-xl shadow-sm">
                <!-- Name -->
                <h3 class="text-2xl font-semibold text-center mb-4 text-gray-900">
                    <?= $user->name ?? '' ?>
                </h3>
                <!-- Location -->
                <div class="flex justify-center items-center text-gray-600 text-sm mb-2">
                    <i class="fa-solid fa-location-dot text-blue-900 mr-2"></i>
                    <span><?= $user->city ?? '' ?>, <?= $user->state ?? '' ?></span>
                </div>
                <!-- Contact -->
                <?php if (!empty($jobseeker->contact)): ?>
                    <div class="text-center text-gray-700 text-md mb-2">
                        <i class="fa-solid fa-phone text-blue-900 mr-2"></i>
                        <span><?= $jobseeker->contact ?? '' ?></span>
                    </div>
                <?php endif; ?>
                <!-- Email -->
                <div class="text-center text-gray-700 text-md mb-6">
                    <i class="fa-regular fa-envelope text-blue-900 mr-2"></i>
                    <span><?= $user->email ?? '' ?></span>
                </div>
                
                <!-- Qualification Section -->

                <?php if (!empty($jobseeker->qualification)) : ?>
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fa-solid fa-graduation-cap text-blue-900 text-lg mr-3"></i>
                            <h4 class="text-lg font-bold text-gray-800">Qualification</h4>
                        </div>
                        <p class="text-gray-600"><?= $jobseeker->qualification ?></p>
                    </div>
                <?php endif; ?>
                <!-- Skills Section -->
                <?php if (!empty($jobseeker->arraySkills)): ?>
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fa-solid fa-lightbulb text-blue-900 text-lg mr-3"></i>
                            <h4 class="text-lg font-bold text-gray-800">Skills</h4>
                        </div>
                        <ul class="list-disc pl-5 text-gray-600">
                            <?php foreach ($jobseeker->arraySkills as $skill) : ?>
                                <li><?= $skill ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <!-- Experience Section -->
                <?php if (!empty($jobseeker->experience)): ?>
                    <div>
                        <div class="flex items-center mb-2">
                            <i class="fa-solid fa-briefcase text-blue-900 text-lg mr-3"></i>
                            <h4 class="text-lg font-bold text-gray-800">Experience</h4>
                        </div>
                        <p class="text-gray-600"><?= $jobseeker->experience ?></p>
                    </div>
                <?php endif; ?>
                <!-- Resume view  -->
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-file text-blue-900 text-lg mr-3"></i>
                        <h4 class="text-lg font-bold text-gray-800">Resume</h4>
                    </div>
                     <div class="flex justify-start">
                        <button class="flex items-center bg-blue-900 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-800">
                            <i class="fa-solid fa-eye mr-2"></i>
                            View Resume
                        </button>
                    </div>
                </div>
            </div>
        </div>




        <!-- My Job Listings -->
        <div class="bg-white p-8 rounded-lg shadow-md w-full">
            <form
                method="POST"
                action="/users/jobseeker/profile"
                enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700" for="name">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="<?= $user->name ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">Email</label>
                    <input
                        id="email"
                        type="text"
                        name="email"
                        value="<?= $user->email ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">Contact</label>
                    <input
                        id="contact"
                        type="phone"
                        name="contact"
                        value="<?= $jobseeker->contact ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" 
                        placeholder="Mobile"/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">Qualification</label>
                    <input
                        id="qualification"
                        type="text"
                        name="qualification"
                        value="<?= $jobseeker->qualification ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" 
                        placeholder="BCA, BE, BTech"/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">Skills</label>
                    <input
                        id="skills"
                        type="text"
                        name="skills"
                        value="<?= $jobseeker->skills ?? '' ?>"
                        placeholder="Web development (HTMP, CSS, Javacript), React etc"
                        class="w-full px-4 py-2 border rounded focus:outline-none" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="city">City</label>
                    <input
                        id="city"
                        type="text"
                        name="city"
                        value="<?= $user->city ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" 
                        placeholder="e.g, Guwahati"/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="state">State</label>
                    <input
                        id="state"
                        type="text"
                        name="state"
                        value="<?= $user->state ?? '' ?>"
                        class="w-full px-4 py-2 border rounded focus:outline-none" 
                        placeholder="e.g, Assam"/>
                </div>
                <!-- Upload resume -->
                <div class="col-span-full">
                <label for="resume" class="block text-gray-700">Upload Resume</label>
                <div class="mt-2 flex justify-center rounded-lg border border-dashed <?= isset($errors['resume_size']) ? 'border-red-400' : 'border-gray-900/25' ?>  px-6 py-10">
                  <div class="text-center d-flex justify-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-11 mb-2 fill-gray-500" viewBox="0 0 32 32">
                      <path
                        d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
                        data-original="#000000" />
                      <path
                        d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
                        data-original="#000000" />
                    </svg>
                    <div class="mt-4 text-sm/6 text-gray-600">
                      <label for="upload_resume" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Upload a file</span>
                        <input id="upload_resume" name="resume" type="file" class="sr-only">
                      </label>
                      <p class="show-file-info text-sm/5 text-gray-800 mb-4"></p>
                    </div>  
                    <p class="text-xs/5 text-gray-600">only PDF size up to 3MB</p>
                    </div>
                </div>
                <p class="text-xs/5 text-red-500"><?= $errors['resume_size'] ?? '' ?></p>

              </div>
                <!-- !Upload resume -->
                <button
                    type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                    Save & Update
                </button>
            </form>
        </div>
    </section>
</main>

<?php loadPartial('footer') ?>