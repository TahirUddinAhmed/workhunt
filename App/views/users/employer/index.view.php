<?php loadPartial('head'); ?>
<main class="container mx-auto px-4 mt-8">
        <!-- Dashboard Header -->
        <div class="mb-8">
            <?php loadPartial('employer-header', ['user' => $user]) ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:transform hover:-translate-y-1 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="fas fa-briefcase text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm mb-1">Active Jobs</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $user->listings_count ? $user->listings_count : '0' ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:transform hover:-translate-y-1 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <i class="fas fa-users text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm mb-1">Total Applications</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $user->application_count ? $user->application_count : '0' ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:transform hover:-translate-y-1 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm mb-1">Interviews</h3>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <?php loadPartial('message') ?>
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Profile Section -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-200">
                    <div class="text-center mb-6">
                        <?php if(!empty($employer->company_logo)) :  ?>
                            <div class="relative w-32 h-32 rounded-full bg-gray-100 mx-auto mb-4 overflow-hidden ring-4 ring-white shadow-lg">
                                <img src="/../uploads/company/logo/<?= $employer->company_logo ?>" class="w-full h-full object-cover" alt="Profile">
                                <button class="absolute bottom-0 right-0 p-2 bg-blue-600 rounded-full text-white hover:bg-blue-700 shadow-sm">
                                    <i class="fas fa-camera text-sm"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <h3 class="text-xl font-bold text-gray-800"><?= $user->name ?></h3>
                        <p class="text-gray-500">Recruiter</p>
                    </div>
                    <span class="text-red-500 mt-2 mb-3"><?= $errors['email-found'] ?? '' ?></span>
                    <form method="POST" action="/users/employer/dashboard/<?= $user->id ?>" class="space-y-4">
                        <div>
                            <input type="hidden" name="_method" value="PUT">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="name"
                                    value="<?= $user->name ?>"
                                    class="w-full px-4 py-3 border <?= isset($errors['name']) ? 'border-red-400' : 'border-gray-300' ?>  rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                >
                                <i class="fas fa-user absolute right-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span class="text-red-500"><?= $errors['name'] ?? '' ?></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    name="email"
                                    value="<?= $user->email ?>" 
                                    class="w-full px-4 py-3 border <?= isset($errors['email']) ? 'border-red-400' : 'border-gray-300' ?> rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    >
                                <i class="fas fa-envelope absolute right-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span class="text-red-500"><?= $errors['email'] ?? '' ?></span>
                        </div>
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>
            <!-- Company Info Section -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Company Information</h3>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-building text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    
                    <form method="POST" action="/users/employer/dashboard/update-company" class="space-y-6" enctype="multipart/form-data">
                        <!-- Company Logo Upload -->
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-blue-400 transition-all">
                            <div class="max-w-xs mx-auto">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-6"></i>
                                <!-- <p class="text-gray-500 mb-2">Drag and drop company logo</p> -->
                                <p class="text-gray-400 text-sm mb-6">
                                <input type="file" name="company_logo" id="company_logo" class="hidden">
                                <label for="company_logo" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-xl cursor-pointer transition-all">
                                    Browse Files
                                </label>
                                </p>
                                <p class="text-gray-400 text-sm mb-6 show-logo-info"></p>
                                <p class="text-gray-400 text-sm mt-3">PNG, JPG up to 2MB</p>
                            </div>
                        </div>

                        <!-- Company Info Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="company_name"
                                        class="w-full px-4 py-3 border <?= isset($errors['company_name']) ? 'border-red-400' : 'border-gray-300' ?> rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Enter company name"
                                        value="<?= $employer->company_name ?? '' ?>"
                                    />
                                    <i class="fas fa-building absolute right-3 top-3.5 text-gray-400"></i>
                                </div>
                                <span class="text-red-500"><?= $errors['company_name'] ?? '' ?></span>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
                                <input 
                                        type="text" 
                                        name="contact"
                                        class="w-full px-4 py-3 border <?= isset($errors['contact']) ? 'border-red-400' : 'border-gray-300' ?> rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Contact number"
                                        value="<?= $employer->contact ?? '' ?>"
                                />
                                <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400"></i>
                                
                                <span class="text-red-500"><?= $errors['contact'] ?? '' ?></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="location"
                                        class="w-full px-4 py-3 border <?= isset($errors['location']) ? 'border-red-400' : 'border-gray-300' ?> rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Enter company location"
                                        value="<?= $employer->location ?? '' ?>"
                                    >
                                    <i class="fas fa-map-marker-alt absolute right-3 top-3.5 text-gray-400"></i>
                                </div>
                                <span class="text-red-500"><?= $errors['location'] ?? '' ?></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                <div class="relative">
                                    <input 
                                        type="url"
                                        name="company_website" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="https://example.com"
                                        value="<?= $employer->company_website ?? '' ?>"
                                    >
                                    <i class="fas fa-globe absolute right-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">About Company</label>
                            <textarea 
                                class="w-full px-4 py-3 border <?= isset($errors['company_desc']) ? 'border-red-400' : 'border-gray-300' ?> rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all h-32"
                                placeholder="Describe your company..."
                                name="company_desc"><?= $employer->company_desc ?? '' ?></textarea>
                        </div>
                        <span class="text-red-500"><?= $errors['company_desc'] ?? '' ?></span>

                        <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            Save Company Info
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php loadPartial('bottom-banner') ?>
<?php loadPartial('footer') ?>