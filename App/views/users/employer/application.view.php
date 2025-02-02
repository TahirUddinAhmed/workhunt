<?php loadPartial('head'); ?>
 <!-- Main Content: Applications Page -->
 <main class="container mx-auto px-4 mt-8">

     <?php loadPartial('employer-header', ['user' => $user]) ?>

     <!-- Page Title and Description -->
     <div class="mb-8 text-center">
         <h1 class="text-4xl font-bold text-gray-800 mb-2">Job Applications</h1>
         <p class="text-gray-600">Manage and review applications submitted for your job listings.</p>
     </div>

     <!-- Filters & Search -->
     <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
         <div class="flex flex-wrap gap-4">
             <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                 <option>All Status</option>
                 <option>Pending</option>
                 <option>Reviewed</option>
                 <option>Rejected</option>
             </select>
             <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                 <option>All Jobs</option>
                 <option>Software Engineer</option>
                 <option>Product Manager</option>
                 <option>Designer</option>
             </select>

             <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl transition-all flex items-center shadow-md hover:shadow-lg">
             <i class="fas fa-filter mr-2"></i>   
                Filter
            </button>
         </div>
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
                <?php foreach($applications as $applicationData): ?>
                    <?php foreach($applicationData as $application) : ?>
        
                    <tr class="hover:bg-gray-50 transition-all">
                     <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-lg font-semibold mb-2"><?= $application->user->name ?? '' ?></p>
                        <p class="text-xs font-normal mb-2"><a href="mailto: <?= $application->user->email ?>" class="underline"><?= $application->user->email ?? '' ?></a></p>
                        <p class="text-xs font-normal mb-2"><a href="tel: <?= $application->jobSeeker->contact ?>"><?= $application->jobSeeker->contact ?? '' ?></a></p>
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap"><a href="/listings/<?= $application->listing->id ?>" class="underline"><?= $application->listing->title ?></a></td>
                     <td class="px-6 py-4 whitespace-nowrap">
                     <a href="#">
                        <button class="flex items-center bg-blue-900 text-white py-2 px-2 rounded-lg shadow hover:bg-blue-800">
                            <i class="fa-solid fa-eye mr-2"></i>
                            View Resume
                        </button>
                        </a>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                             <?= $application->status ?>
                         </span>
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                         <a href="#" class="text-white hover:text-gray-700 bg-blue-500 text-xs leading-5 font-semibold rounded-full py-2 px-4 mr-2">
                            Accepted
                         </a>
                         <a href="#" class="text-white hover:text-gray-700 bg-red-500 text-xs leading-5 font-semibold rounded-full py-2 px-4">
                            Rejected
                         </a>
                     </td>
                 </tr>
                 <?php endforeach; ?>
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