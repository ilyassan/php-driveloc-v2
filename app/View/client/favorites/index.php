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
        <?php foreach($articles as $article): ?>
            <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-transform transform hover:scale-105">
                <form action="<?= URLROOT . 'articles/addToFavorite' ?>" method="POST">
                    <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                    <button class="absolute top-6 right-6 text-primary">
                        <i class="<?= $article['is_favorite'] ? 'fas': 'far'?> fa-bookmark text-xl"></i>
                    </button>
                </form>
                <div class="p-6">
                    <span class="text-red-500 text-sm font-medium">
                        <?= (new DateTime($article['created_at']))->format('F d, Y') ?>
                    </span>
                    <h2 class="text-xl font-bold text-gray-800 mt-3 mb-3"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="text-gray-600 line-clamp-3"><?= htmlspecialchars(getExcerpt($article['content'], 100)); ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <form method="POST" class="flex items-center gap-4 mb-2">
                            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                            <button type="submit" formaction="<?= URLROOT . 'articles/like' ?>" class="<?= $article['is_liked'] ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 hover:text-red-500">
                                <i class="<?= $article['is_liked'] ? 'fas' : 'far' ?> fa-thumbs-up"></i> <?= $article["likes_count"] ?>
                            </button>
                            <button type="submit" formaction="<?= URLROOT . 'articles/dislike' ?>" class="<?= $article['is_disliked'] ? 'text-primary' : 'text-gray-500' ?> flex items-center gap-2 text-gray-500 hover:text-red-500">
                                <i class="<?= $article['is_disliked'] ? 'fas' : 'far' ?> far fa-thumbs-down"></i> <?= $article["dislikes_count"] ?>
                            </button>
                        </form>
                        <a href="<?= URLROOT . 'articles/' . $article["id"] ?>" class="text-red-500 hover:text-red-600 font-medium">Read More →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>
