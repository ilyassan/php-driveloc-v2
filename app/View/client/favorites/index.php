<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<main class="container mx-auto py-12">
    <!-- Theme Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Your Favorite Articles</h1>
        <p class="text-gray-600 mt-2">Enjoy a curated list of articles you’ve marked as your favorites.</p>
    </div>

    <!-- Favorite Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Favorite Article Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-transform transform hover:scale-105">
            <button class="absolute top-6 right-6 text-red-500 hover:text-red-600 transition-colors">
                <i class="fas fa-bookmark text-xl"></i>
            </button>
            <div class="p-6">
                <span class="text-red-500 text-sm font-medium">June 15, 2024</span>
                <h2 class="text-xl font-bold text-gray-800 mt-3 mb-3">The New BMW M3: A Perfect Blend of Power and Luxury</h2>
                <p class="text-gray-600 line-clamp-3">Experience the thrill of BMW's latest masterpiece. With enhanced performance metrics and refined interior features, the new M3 sets a new standard...</p>
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-thumbs-up"></i> 245
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-comment"></i> 89
                        </span>
                    </div>
                    <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                </div>
            </div>
        </div>

        <!-- Another Favorite Article Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-transform transform hover:scale-105">
            <button class="absolute top-6 right-6 text-red-500 hover:text-red-600 transition-colors">
                <i class="fas fa-bookmark text-xl"></i>
            </button>
            <div class="p-6">
                <span class="text-red-500 text-sm font-medium">June 14, 2024</span>
                <h2 class="text-xl font-bold text-gray-800 mt-3 mb-3">Tesla Model S vs Porsche Taycan: Electric Giants Clash</h2>
                <p class="text-gray-600 line-clamp-3">A detailed comparison of two leading electric vehicles. We dive deep into performance, range, comfort, and technology to help you decide...</p>
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-thumbs-up"></i> 312
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-comment"></i> 156
                        </span>
                    </div>
                    <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                </div>
            </div>
        </div>

        <!-- Another Favorite Article Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-transform transform hover:scale-105">
            <button class="absolute top-6 right-6 text-red-500 hover:text-red-600 transition-colors">
                <i class="fas fa-bookmark text-xl"></i>
            </button>
            <div class="p-6">
                <span class="text-red-500 text-sm font-medium">May 10, 2024</span>
                <h2 class="text-xl font-bold text-gray-800 mt-3 mb-3">The Electric Revolution: Why EVs are the Future</h2>
                <p class="text-gray-600 line-clamp-3">Electric vehicles are revolutionizing the automotive industry. Let’s explore the top reasons why EVs are the future of transportation...</p>
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-thumbs-up"></i> 120
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-comment"></i> 45
                        </span>
                    </div>
                    <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Load More Button -->
    <div class="mt-8 text-center">
        <button class="bg-gradient-to-r from-gray-100 to-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50">
            Load More Articles
        </button>
    </div>
</main>
