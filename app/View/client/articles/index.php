<main class="container mx-auto py-12">
    <!-- Theme Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Car Reviews</h1>
        <p class="text-gray-600 mt-2"><?= count($articles) ?> articles</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="flex gap-4 mb-8">
        <div class="flex-1 relative">
            <input type="text" placeholder="Search articles..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 relative">
                <button class="absolute top-6 right-6 text-gray-400 hover:text-red-500">
                    <i class="far fa-bookmark text-xl"></i>
                </button>
                <span class="text-red-500 text-sm font-medium">
                    <?= (new DateTime($article['created_at']))->format('F d, Y') ?>
                </span>
                <h2 class="text-xl font-bold text-gray-800 mt-2 mb-3"><?= htmlspecialchars($article['title']) ?></h2>
                <p class="text-gray-600 line-clamp-3"><?= htmlspecialchars($article['content']) ?></p>
                
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-thumbs-up"></i> <?= $article['likes_count'] ?>
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-thumbs-down"></i> <?= $article['dislikes_count'] ?>
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-comment"></i> <?= $article['comments_count'] ?>
                        </span>
                    </div>
                    <a href="#" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Load More Button -->
    <div class="mt-8 text-center">
        <button class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50">
            Load More Articles
        </button>
    </div>
</main>
