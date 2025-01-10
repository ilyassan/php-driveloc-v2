<main class="p-6">
    <!-- Article Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex justify-between items-start mb-6">
            <span class="text-red-500 text-sm font-medium">
                <?= (new DateTime($article->created_at))->format('F d, Y') ?>
            </span>
            <button class="text-gray-400 hover:text-red-500" onclick="confirmArticleDelete('<?= $article->title ?>', '<?= $article->id ?>')">
                <i class="far fa-trash-alt text-xl"></i>
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
                <form method="POST" class="flex items-center gap-6">
                    <input type="hidden" name="article_id" value="<?= $article->id ?>">
                    <button type="submit" formaction="<?= URLROOT . 'articles/like' ?>" class="<?= $isLiked ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 hover:text-red-500">
                        <i class="<?= $isLiked ? 'fas' : 'far' ?> fa-thumbs-up"></i> <?= $article->likes_count ?>
                    </button>
                    <button type="submit" formaction="<?= URLROOT . 'articles/dislike' ?>" class="<?= $isDisliked ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 text-gray-500 hover:text-red-500">
                        <i class="<?= $isDisliked ? 'fas' : 'far' ?> far fa-thumbs-down"></i> <?= $article->dislikes_count ?>
                    </button>
                    <button type="button" class="flex items-center gap-2 text-gray-500">
                        <i class="far fa-comment"></i> <?= count($comments) ?> Comments
                    </button>
                    <button type="button" class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                        <i class="far fa-share-square"></i> Share
                    </button>
                </form>
            </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Comments (<?= count($comments) ?>)</h3>

        <!-- Comments List -->
        <div class="space-y-6">
            <?php foreach($comments as $i => $comment): ?>
                <div class="<?= $i == 0 ? '': 'border-t pt-6' ?> border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-user-circle text-4xl text-gray-600"></i>
                            <div>
                                <h4 class="font-medium text-gray-800"><?= $comment["author_name"] . ($comment['author_id'] === user()->getId() ? " ( You )" : "") ?></h4>
                                <span class="text-sm text-gray-500"><?= getTimeAgoFromDate($comment['created_at']) ?></span>
                            </div>
                        </div>
                        
                        <?php if ($comment['author_id'] === user()->getId()): ?>
                            <form action="<?= URLROOT . 'comments/delete' ?>" method="POST" class="delete-comment-form">
                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                <input type="hidden" name="article_id" value="<?= $article->id ?>">
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        <?php endif; ?>
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
    

    <!-- Delete Article Confirmation -->
    <div id="deleteArticleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete Category</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="articleToDelete"></span>"? This action cannot be undone.</p>
            
            <div class="flex justify-end gap-4">
                <button 
                    onclick="closeArticleDeleteModal()"
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

    function confirmArticleDelete(articleToDelete, id) {
        articleToDelete = articleToDelete;
        document.getElementById('article_id').value = id;

        document.getElementById('articleToDelete').textContent = articleToDelete;
        document.getElementById('deleteArticleModal').classList.remove('hidden');
        document.getElementById('deleteArticleModal').classList.add('flex');
    }

    function closeArticleDeleteModal() {
        document.getElementById('deleteArticleModal').classList.remove('flex');
        document.getElementById('deleteArticleModal').classList.add('hidden');
        articleToDelete = '';
        document.getElementById('article_id').value = '';
    }

    document.getElementById('deleteArticleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeArticleDeleteModal();
        }
    });
</script>