<main class="container mx-auto py-12">
    <!-- Theme Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Manage Articles</h1>
        <p class="text-gray-600 mt-2">24 articles</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="flex gap-4 mb-8">
        <!-- Search Bar -->
        <div class="flex-1 relative">
            <input type="text" placeholder="Search articles..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <!-- Theme Select Filter -->
        <div class="relative">
            <select id="themeFilter" class="w-48 pl-4 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">Select Theme</option>
                <option value="bmw">BMW</option>
                <option value="tesla">Tesla</option>
                <option value="electric">Electric Cars</option>
                <option value="sports">Sports Cars</option>
            </select>
        </div>

        <!-- Filter Button -->
        <button onclick="applyFilter()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <!-- Articles Grid -->
    <div id="articlesGrid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Example Article Cards will appear here based on filter -->
        <!-- Article Card 1 -->
        <div class="bg-white rounded-lg shadow-lg p-6 relative article bmw">
            <button class="absolute top-6 right-6 text-red-500" onclick="deleteArticle(1)">
                <i class="fas fa-trash-alt text-xl"></i>
            </button>
            <span class="text-red-500 text-sm font-medium">June 15, 2024</span>
            <h2 class="text-xl font-bold text-gray-800 mt-2 mb-3">The New BMW M3: A Perfect Blend of Power and Luxury</h2>
            <p class="text-gray-600 line-clamp-3">Experience the thrill of BMW's latest masterpiece...</p>
            <div class="mt-4 flex justify-between items-center">
                <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
            </div>
        </div>

        <!-- Article Card 2 -->
        <div class="bg-white rounded-lg shadow-lg p-6 relative article tesla">
            <button class="absolute top-6 right-6 text-red-500" onclick="deleteArticle(2)">
                <i class="fas fa-trash-alt text-xl"></i>
            </button>
            <span class="text-red-500 text-sm font-medium">June 14, 2024</span>
            <h2 class="text-xl font-bold text-gray-800 mt-2 mb-3">Tesla Model S vs Porsche Taycan: Electric Giants Clash</h2>
            <p class="text-gray-600 line-clamp-3">A detailed comparison of two leading electric vehicles...</p>
            <div class="mt-4 flex justify-between items-center">
                <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
            </div>
        </div>

        <!-- More Article Cards -->
    </div>

    <!-- Load More Button -->
    <div class="mt-8 text-center">
        <button class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50">
            Load More Articles
        </button>
    </div>
</main>

<script>
    // Function to delete an article (for demonstration purposes)
    function deleteArticle(articleId) {
        if (confirm("Are you sure you want to delete this article?")) {
            // Perform delete operation (e.g., API call)
            alert('Article ' + articleId + ' deleted.');
        }
    }

    // Function to filter articles based on the selected theme
    function applyFilter() {
        const selectedTheme = document.getElementById('themeFilter').value;
        const articles = document.querySelectorAll('.article');
        
        articles.forEach(article => {
            // Show all if no theme is selected
            if (!selectedTheme || article.classList.contains(selectedTheme)) {
                article.style.display = 'block';
            } else {
                article.style.display = 'none';
            }
        });
    }
</script>
