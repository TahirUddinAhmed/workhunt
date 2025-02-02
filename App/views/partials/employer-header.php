<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
    <a href="/users/employer/dashboard">   
    <span class="bg-gradient-to-r from-blue-600 to-blue-400 text-transparent bg-clip-text">
            Hello, <?= $user->name ?>
        </span>
        </a> 
    </h2>
    <div class="flex gap-4">
        <a href="/" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-all flex items-center shadow-md hover:shadow-lg">
            <i class="fas fa-house-user mr-2"></i> Home
        </a>
        <a href="/users/employer/dashboard/applications" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl transition-all flex items-center shadow-md hover:shadow-lg">
            <i class="fas fa-file-alt mr-2"></i> Applications
        </a>
        <a href="/users/employer/dashboard/my-listings" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl transition-all flex items-center shadow-md hover:shadow-lg">
            <i class="fas fa-briefcase mr-2"></i> My Listings
        </a>

    </div>
</div>