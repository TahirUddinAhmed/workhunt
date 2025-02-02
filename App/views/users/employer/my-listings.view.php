<?php loadPartial('head'); ?>

<main class="container mx-auto px-4 mt-8">
    <?php loadPartial('employer-header', ['user' => $user]) ?>
    <div class="mb-8 text-center">
         <h1 class="text-3xl font-bold text-gray-800 mt-8 mb-2">
            Job Applications
            <span class="text-gray-400 ml-2">(<?= $listing_count .' Job listing' ?? '' ?>)</span>
        </h1>
        <p class="text-gray-600">Manage your job listings.</p>
     </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Job Card 1 -->
        <?php foreach($listings as $listing) : ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800"><?= $listing->title ?></h3>
                        <span class="text-sm text-gray-500"><?= 'Posted ' . get_time_ago($listing->created_at) ?></span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm mt-2">
                        <?= $listing->job_type->type_name ?>
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <?php if($listing->remote === 'Yes'): ?>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            <span>Remote</span>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            <span>On-site</span>
                        </div>
                    <?php endif; ?>
                    <!-- <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        <span>Posted 5d ago</span>
                    </div> -->
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-wallet mr-2 text-blue-500"></i>
                        <span><?= formateSalary($listing->salary) ?></span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Applications</span>
                        <span class="text-blue-600 font-medium"><?= $listing->application_count ?></span>
                    </div>
                    <!-- <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-500 rounded-full w-3/4"></div>
                    </div> -->
                </div>

                <div class="flex justify-between gap-2">
                    <a href="/listings/edit/<?= $listing->id ?>" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-600 px-4 py-2 rounded-xl text-center transition-all">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="/listings/<?= $listing->id ?>" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-center transition-all">
                        <i class="fas fa-eye mr-2"></i>View
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- Job Card 2 (Draft Example) -->
        <!-- <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">UX Designer</h3>
                        <span class="text-sm text-gray-500">Contract</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm">
                        Draft
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span>New York, NY</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        <span>Created 2d ago</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-wallet mr-2 text-blue-500"></i>
                        <span>$80k - $100k</span>
                    </div>
                </div>

                <div class="flex justify-between gap-2">
                    <a href="#" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-600 px-4 py-2 rounded-xl text-center transition-all">
                        <i class="fas fa-edit mr-2"></i>Continue
                    </a>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-xl transition-all">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Job Card 3 (Expired Example) -->
        <!-- <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Data Analyst</h3>
                        <span class="text-sm text-gray-500">Part-time</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm">
                        Expired
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span>London, UK</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        <span>Closed 1w ago</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-wallet mr-2 text-blue-500"></i>
                        <span>£45k - £55k</span>
                    </div>
                </div>

                <div class="flex justify-between gap-2">
                    <a href="#" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-600 px-4 py-2 rounded-xl text-center transition-all">
                        <i class="fas fa-sync mr-2"></i>Repost
                    </a>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-xl transition-all">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div> -->
    </div>
</main>

<?php loadPartial('bottom-banner') ?>
<?php loadPartial('footer') ?>