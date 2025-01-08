<main class="container mx-auto py-12 max-w-4xl">
        <!-- Article Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex justify-between items-start mb-6">
                <span class="text-red-500 text-sm font-medium">
                    <?= (new DateTime($article->created_at))->format('F d, Y') ?>
                </span>
                <button class="text-primary">
                    <i class="<?= $isFavorite ? 'fas': 'far' ?> fa-bookmark text-xl"></i>
                </button>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $article->title ?></h1>
            <div class="flex items-center gap-6 text-sm text-gray-500 mb-8">
                <div class="flex items-center gap-3 pr-4 py-2 text-gray-600">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span class="font-medium"><?= $article->author_name ?></span>
                </div>
                <?php
                    // Function to calculate reading time
                    function calculateReadingTime($content, $wordsPerMinute = 200) {
                        $wordCount = str_word_count(strip_tags($content));
                        $readingTime = ceil($wordCount / $wordsPerMinute);
                        return $readingTime;
                    }

                    // Example usage
                    $articleContent = $article->content; // Replace with actual article content
                    $readingTime = calculateReadingTime($articleContent);
                ?>
                <span class="flex items-center gap-1">
                    <i class="far fa-clock"></i> <?= $readingTime ?> min read
                </span>
                <span class="flex items-center gap-1">
                    <i class="far fa-eye"></i> 1.2k views
                </span>
            </div>

            <!-- Article Content -->
            <div class="prose max-w-none">
                <?= htmlspecialchars_decode($article->content) ?>
            </div>

            <!-- Engagement Section -->
            <div class="border-t border-gray-200 mt-8 pt-6">
                <div class="flex items-center gap-6">
                    <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                        <i class="far fa-thumbs-up"></i> <?= $article->likes_count ?>
                    </button>
                    <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                        <i class="far fa-thumbs-down"></i> <?= $article->dislikes_count ?>
                    </button>
                    <button class="flex items-center gap-2 text-gray-500">
                        <i class="far fa-comment"></i> <?= count($comments) ?> Comments
                    </button>
                    <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                        <i class="far fa-share-square"></i> Share
                    </button>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Comments (<?= count($comments) ?>)</h3>
            
            <!-- Comment Form -->
            <div class="mb-8">
                <textarea class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" rows="3" placeholder="Add a comment..."></textarea>
                <button class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600">Post Comment</button>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
                <?php foreach($comments as $i => $comment): ?>
                    <div class="<?= $i == 0 ? '': 'border-t pt-6' ?> border-gray-200">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-user-circle text-4xl text-gray-600"></i>
                            <div>
                                <h4 class="font-medium text-gray-800"><?= $comment["author_name"] ?></h4>
                                <span class="text-sm text-gray-500"><?= getTimeAgoFromDate($comment['created_at']) ?></span>
                            </div>
                        </div>
                        <p class="text-gray-600"><?= $comment["content"] ?></p>
                        <div class="flex items-center gap-4 mt-3">
                            <button class="text-sm text-gray-500 hover:text-red-500">Reply</button>
                            <button class="text-sm text-gray-500 hover:text-red-500">Like</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>