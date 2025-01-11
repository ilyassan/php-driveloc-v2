<main class="container mx-auto py-12 max-w-4xl">
        <!-- Article Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex justify-between items-start mb-6">
                <span class="text-red-500 text-sm font-medium">
                    <?= (new DateTime($article->created_at))->format('F d, Y') ?>
                </span>
                <form action="<?= URLROOT . 'articles/addToFavorite' ?>" method="POST">
                    <input type="hidden" name="article_id" value="<?= $article->id ?>">
                    <button class="text-primary">
                        <i class="<?= $isFavorite ? 'fas': 'far' ?> fa-bookmark text-xl"></i>
                    </button>
                </form>
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
                    <i class="far fa-eye"></i> <?= $article->views ?> views
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
            
            <!-- Comment Form -->
            <form action="<?= URLROOT . 'comments/create' ?>" method="POST" class="mb-8">
                <textarea name="comment" class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" rows="3" placeholder="Add a comment..."></textarea>
                <input type="hidden" name="theme_id" value="<?= $article->id ?>">
                <button class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600">Post Comment</button>
            </form>

            <!-- Comments List -->
            <div class="space-y-6">
                <?php foreach($comments as $i => $comment): ?>
                    <div class="<?= $i == 0 ? '': 'border-t pt-6' ?> border-gray-200">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3 mb-3">
                                <i class="fas fa-user-circle text-4xl text-gray-600"></i>
                                <div>
                                    <h4 class="font-medium text-gray-800"><?= $comment["author_name"] . (isLoggedIn() && $comment['author_id'] === user()->getId() ? " ( You )" : "") ?></h4>
                                    <span class="text-sm text-gray-500"><?= getTimeAgoFromDate($comment['created_at']) ?> <?= $comment['is_edited'] ? '(Edited)' : '' ?></span>
                                </div>
                            </div>
                            
                            <?php if (isLoggedIn() && $comment['author_id'] === user()->getId()): ?>
                                <div class="flex gap-4">
                                    <!-- Edit button -->
                                    <button type="button" 
                                            class="text-sm text-gray-500 hover:text-red-500 edit-comment-btn"
                                            data-comment-id="<?= $comment['id'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <!-- Delete form -->
                                    <form action="<?= URLROOT . 'comments/delete' ?>" method="POST" class="delete-comment-form">
                                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                        <input type="hidden" name="article_id" value="<?= $article->id ?>">
                                        <button type="submit" class="text-sm text-gray-500 hover:text-red-500">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Regular comment content -->
                        <p class="text-gray-600 comment-content" id="comment-content-<?= $comment['id'] ?>"><?= $comment["content"] ?></p>
                        
                        <!-- Edit form (hidden by default) -->
                        <form action="<?= URLROOT . 'comments/update' ?>" 
                            method="POST" 
                            class="hidden edit-comment-form" 
                            id="edit-form-<?= $comment['id'] ?>">
                            <textarea name="comment" 
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-2"
                                    rows="3"><?= $comment["content"] ?></textarea>
                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                            <input type="hidden" name="article_id" value="<?= $article->id ?>">
                            <div class="flex gap-2">
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600">
                                    Save Changes
                                </button>
                                <button type="button" 
                                        class="border border-gray-300 text-gray-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 cancel-edit-btn"
                                        data-comment-id="<?= $comment['id'] ?>">
                                    Cancel
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center gap-4 mt-3">
                            <button class="text-sm text-gray-500 hover:text-red-500">Reply</button>
                            <button class="text-sm text-gray-500 hover:text-red-500">Like</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>


<script>
    document.querySelectorAll('.edit-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute("data-comment-id");
            const contentElement = document.getElementById(`comment-content-${commentId}`);
            const editForm = document.getElementById(`edit-form-${commentId}`);
            
            // Hide the content and show the edit form
            contentElement.classList.add('hidden');
            editForm.classList.remove('hidden');
        });
    });

    // Handle cancel button clicks
    document.querySelectorAll('.cancel-edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute("data-comment-id");
            const contentElement = document.getElementById(`comment-content-${commentId}`);
            const editForm = document.getElementById(`edit-form-${commentId}`);
            
            // Show the content and hide the edit form
            contentElement.classList.remove('hidden');
            editForm.classList.add('hidden');
        });
    });

    // Optional: Auto-resize textarea as user types
    document.querySelectorAll('.edit-comment-form textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>