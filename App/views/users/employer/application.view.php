<?php loadPartial('head'); ?>
 <!-- Main Content: Applications Page -->
 <main class="container mx-auto px-4 mt-8">

     <?php loadPartial('employer-header', ['user' => $user]) ?>

     <!-- Page Title and Description -->
     <div class="mb-8 text-center">
         <h1 class="text-4xl font-bold text-gray-800 mb-2">Job Applications</h1>
         <p class="text-gray-600">Manage and review applications submitted for your job listings.</p>
     </div>

     <?php loadPartial('message') ?>
     <!-- Filters & Search -->
     <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
         <form action="/users/employer/dashboard/applications/filter" method="GET">
         <div class="flex flex-wrap gap-4">
             <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                 <option value="">All Status</option>
                 <option value="pending">Pending</option>
                 <option value="accepted">Accepted</option>
                 <option value="rejected">Rejected</option>
             </select>

             <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl transition-all flex items-center shadow-md hover:shadow-lg">
             <i class="fas fa-filter mr-2"></i>   
                Filter
            </button>
        </div>
    </form>
         <!-- <div>
             <input type="text" placeholder="Search applications..." class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
         </div> -->
     </div>
     <!-- Applications Table -->
     <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
         <table class="min-w-full divide-y divide-gray-200">
             <thead class="bg-gray-50">
                 <tr>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant Details</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                     <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                 </tr>
             </thead>
             <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($applications as $application): ?>
                    
        
                    <tr class="hover:bg-gray-50 transition-all">
                     <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-lg font-semibold mb-2"><?= $application->seeker_name ?? '' ?></p>
                        <p class="text-xs font-normal mb-2"><a href="mailto: <?= $application->seeker_email ?>" class="underline"><?= $application->seeker_email ?? '' ?></a></p>
                        <p class="text-xs font-normal mb-2"><a href="tel: <?= $application->j_contact ?>"><?= $application->j_contact ?? '' ?></a></p>
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-lg font-semibold">
                            <a href="/listings/<?= $application->a_listings_id ?>" class="underline"><?= $application->l_title ?></a>
                        </p>

                    </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                     <a href="/users/employer/dashboard/applications/download-resume/<?= $application->a_id ?>">
                        <button class="flex items-center bg-blue-900 text-white py-2 px-2 rounded-lg shadow hover:bg-blue-800">
                            <i class="fa-solid fa-eye mr-2"></i>
                            View Resume
                        </button>
                        </a>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($application->a_status == 'accepted') : ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                             <?= $application->a_status ?>
                            </span>
                        <?php elseif($application->a_status == 'rejected') : ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                             <?= $application->a_status ?>
                            </span>
                        <?php else : ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <?= $application->a_status ?>
                            </span>
                        <?php endif; ?>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-24">
                        <div class="flex justify-end">
                            <form action="/users/employer/dashboard/applications/<?= $application->a_id ?>" method="POST"> 
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="text-white hover:text-gray-300 bg-blue-500 text-xs leading-5 font-semibold rounded-full py-2 px-4 mr-2">
                                    Accepted
                                </button>
                            </form>
                            <form action="/users/employer/dashboard/applications/<?= $application->a_id ?>" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="text-white hover:text-gray-300 bg-red-500 text-xs leading-5 font-semibold rounded-full py-2 px-4">
                                    Rejected
                                </button>
                            </form>
                         </div>
                     </td>
                 </tr>
                 
                <?php endforeach; ?>
                 <!-- Sample Row 1 -->
                 
                
             </tbody>
         </table>
     </div>

     <!-- Pagination -->
     <!-- <div class="mt-6 flex justify-end">
         <nav class="inline-flex -space-x-px">
             <a href="#" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">Previous</a>
             <a href="#" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">1</a>
             <a href="#" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">2</a>
             <a href="#" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">3</a>
             <span class="px-3 py-2 border border-gray-300 bg-white text-gray-500">...</span>
             <a href="#" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">10</a>
             <a href="#" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">Next</a>
         </nav>
     </div> -->
 </main>

<?php loadPartial('bottom-banner') ?>
<?php loadPartial('footer') ?>