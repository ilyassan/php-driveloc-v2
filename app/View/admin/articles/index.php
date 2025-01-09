<main class="p-6 mx-auto">
    <!-- Theme Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Manage Articles</h1>
        <p class="text-gray-600 mt-2"><?= count($articles) ?> articles</p>
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
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 relative article bmw">
                <button class="absolute top-6 right-6 text-red-500" onclick="confirmDelete('<?= $article['title'] ?>', '<?= $article['id'] ?>')">
                    <i class="fas fa-trash-alt text-xl"></i>
                </button>
                <span class="text-red-500 text-sm font-medium">
                    <?= (new DateTime($article['created_at']))->format('F d, Y') ?>
                </span>
                <h2 class="text-xl font-bold text-gray-800 mt-2 mb-3"><?= htmlspecialchars($article['title']) ?></h2>
                <p class="text-gray-600 line-clamp-3"><?= htmlspecialchars(getExcerpt($article['content'], 100)); ?></p>
                <div class="mt-4 flex justify-between items-center">
                    <a href="<?= URLROOT . 'articles/' . $article['id'] ?>" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete Category</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="articleToDelete"></span>"? This action cannot be undone.</p>
            
            <div class="flex justify-end gap-4">
                <button 
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <form action="<?= htmlspecialchars(URLROOT . 'articles/delete') ?>" method="POST">
                    <input id="article_id" type="hidden" name="article_id" value="">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</main>

<script>
    let articleToDelete = '';

    function confirmDelete(articleToDelete, id) {
        articleToDelete = articleToDelete;
        document.getElementById('article_id').value = id;

        document.getElementById('articleToDelete').textContent = articleToDelete;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
        articleToDelete = '';
        document.getElementById('article_id').value = '';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    function applyFilter() {
        const selectedTheme = document.getElementById('themeFilter').value;
        const articles = document.querySelectorAll('.article');
        
        articles.forEach(article => {
            if (!selectedTheme || article.classList.contains(selectedTheme)) {
                article.style.display = 'block';
            } else {
                article.style.display = 'none';
            }
        });
    }
</script>
