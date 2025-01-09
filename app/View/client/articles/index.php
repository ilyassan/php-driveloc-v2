<main class="container mx-auto py-12">
    <!-- Theme Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800"><?= $theme->getName() ?></h1>
        <p class="text-gray-600 mt-2"><?= $articlesTotalCount ?> articles</p>
    </div>

    <!-- Search and Filter Bar -->
    <form method="GET" action="" class="flex gap-4 mb-8">
        <div class="flex-1 relative">
            <input type="text" 
                name="search" 
                value="<?= htmlspecialchars($keyword ?? '') ?>"
                placeholder="Search articles..." 
                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <!-- Categories (Custom Dropdown) -->
        <div class="relative">
            <input id="per_page" type="hidden" name="per_page" value="<?= htmlspecialchars($articlesPerPage) ?>">
            <button
                type="button"
                id="perPageDropdown"
                class="flex items-center border border-gray-300 rounded-md px-4 py-3 w-full bg-white text-gray-500 focus:outline-none"
            >
                <span id="selectedPerPage" class="mr-2"><?= htmlspecialchars($articlesPerPage) ?></span>
                <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
            </button>
            <!-- Dropdown Options -->
            <ul
                id="perPageDropdownMenu"
                class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
            >
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('perPageDropdown', 'selectedPerPage', '2')">2</li>
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('perPageDropdown', 'selectedPerPage', '5')">5</li>
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('perPageDropdown', 'selectedPerPage', '10')">10</li>
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('perPageDropdown', 'selectedPerPage', '20')">20</li>
            </ul>
        </div>

        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
            Search
        </button>

        <?php if (!empty($keyword)): ?>
            <a href="<?= URLROOT . 'themes/' . $theme->getId() ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
                Clear Search
            </a>
        <?php endif; ?>
    </form>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (empty($articles)): ?>
            <div class="col-span-full text-center py-8">
                <p class="text-gray-600">No articles found.</p>
            </div>
        <?php else: ?>
            
            <?php foreach ($articles as $article): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 relative">
                    <form action="<?= URLROOT . 'articles/addToFavorite' ?>" method="POST">
                        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                        <button class="absolute top-6 right-6 text-primary">
                            <i class="<?= $article['is_favorite'] ? 'fas': 'far'?> fa-bookmark text-xl"></i>
                        </button>
                    </form>
                    
                    <span class="text-red-500 text-sm font-medium">
                        <?= (new DateTime($article['created_at']))->format('F d, Y') ?>
                    </span>
                    <h2 class="text-xl font-bold text-gray-800 mt-2 mb-3"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="text-gray-600 line-clamp-3">
                        <?= htmlspecialchars(getExcerpt($article['content'], 100)); ?>
                    </p>

                    <div class="mt-4 flex justify-between items-center">
                    <form method="POST" class="flex items-center gap-4 mb-2">
                        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                        <button type="submit" formaction="<?= URLROOT . 'articles/like' ?>" class="<?= $article['is_liked'] ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 hover:text-red-500">
                            <i class="<?= $article['is_liked'] ? 'fas' : 'far' ?> fa-thumbs-up"></i> <?= $article["likes_count"] ?>
                        </button>
                        <button type="submit" formaction="<?= URLROOT . 'articles/dislike' ?>" class="<?= $article['is_disliked'] ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 text-gray-500 hover:text-red-500">
                            <i class="<?= $article['is_disliked'] ? 'fas' : 'far' ?> far fa-thumbs-down"></i> <?= $article["dislikes_count"] ?>
                        </button>
                            <span class="flex items-center gap-1 text-gray-500">
                                <i class="far fa-comment"></i> <?= $article['comments_count'] ?>
                            </span>
                        </div>
                        <a href="<?= URLROOT . 'articles/' . $article["id"] ?>" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8">
            <nav role="navigation" aria-label="Pagination Navigation">
                <ul class="flex items-center space-x-2">
                    <?php
                    $totalPages = ceil($articlesTotalCount / $articlesPerPage);
                    $currentPage = (int) ($_GET['page'] ?? 1);

                    // Ensure current page is within valid range
                    if ($currentPage < 1) {
                        $currentPage = 1;
                    } elseif ($currentPage > $totalPages) {
                        $currentPage = $totalPages;
                    }

                    // Calculate visible pages
                    $visiblePages = 5;
                    $halfVisible = floor($visiblePages / 2);

                    $startPage = max(1, $currentPage - $halfVisible);
                    $endPage = min($totalPages, $currentPage + $halfVisible);

                    if ($currentPage <= $halfVisible) {
                        $endPage = min($visiblePages, $totalPages);
                    } elseif ($currentPage + $halfVisible > $totalPages) {
                        $startPage = max(1, $totalPages - $visiblePages + 1);
                    }

                    $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;
                    $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : null;
                    ?>

                    <!-- Previous Button -->
                    <li>
                        <a href="<?= $previousPage ? "?search=" . urlencode($keyword) . "&per_page=" . $articlesPerPage . "&page=" . htmlspecialchars($previousPage) : '#' ?>"
                        class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100 <?= !$previousPage ? 'opacity-50 cursor-not-allowed' : '' ?>"
                        <?= !$previousPage ? 'aria-disabled="true"' : '' ?>>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- First Page -->
                    <?php if ($startPage > 1): ?>
                        <li>
                            <a href="?search=<?= urlencode($keyword) ?>&per_page=<?= $articlesPerPage ?>&page=1" 
                            class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">1</a>
                        </li>
                        <?php if ($startPage > 2): ?>
                            <li>
                                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-700">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li>
                            <a href="?search=<?= urlencode($keyword) ?>&per_page=<?= $articlesPerPage ?>&page=<?= htmlspecialchars($i) ?>"
                            class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                                <?= htmlspecialchars($i) ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Last Page -->
                    <?php if ($endPage < $totalPages): ?>
                        <!-- ... -->
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li>
                                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-700">...</span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?= "?search=" . urlencode($keyword) . "&per_page=" . $articlesPerPage . "&page=" . htmlspecialchars($totalPages) ?>" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">
                                <?= htmlspecialchars($totalPages) ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <li>
                        <a href="<?= $nextPage ? "?search=" . urlencode($keyword) . "&per_page=" . $articlesPerPage . "&page=" . htmlspecialchars($nextPage) : '#' ?>"
                        class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100 <?= !$nextPage ? 'opacity-50 cursor-not-allowed' : '' ?>"
                        <?= !$nextPage ? 'aria-disabled="true"' : '' ?>>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
</main>


<script>
    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value) {
        document.getElementById("per_page").value = value;
        document.getElementById(labelId).innerText = value;
        document.getElementById(`${dropdownId}Menu`).classList.add('hidden');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Event listeners for dropdown toggles
    document.getElementById('perPageDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('perPageDropdown', 'perPageDropdownMenu');
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });
</script>