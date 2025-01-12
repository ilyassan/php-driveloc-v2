<main class="p-6">
    <!-- Article Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex justify-between items-start mb-6">
            <span class="text-red-500 text-sm font-medium">
                <?= (new DateTime($article->created_at))->format('F d, Y') ?>
            </span>
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
        </div>

        <!-- Article Content -->
        <div class="prose max-w-none">
            <?= htmlspecialchars_decode($article->content) ?>
        </div>

        <!-- Article Tags -->
        <div class="flex flex-wrap gap-2 mt-10">
            <?php if (!empty($article->tags)): ?>
                <?php foreach ($article->tags as $tag): ?>
                    <span 
                        class="px-2 py-1 rounded-full text-xs font-medium bg-primary text-white"
                    >
                        #<?= htmlspecialchars($tag) ?>
                    </span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Article Actions -->
    <form method="POST" class="flex justify-between mb-6">
        <input type="hidden" name="article_id" value="<?= $article->id ?>">
        <button formaction="<?= URLROOT . 'articles/publish' ?>" class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-green-600" onclick="approveArticle()">
            Approve
        </button>
        <button formaction="<?= URLROOT . 'articles/refuse' ?>" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-red-600" onclick="rejectArticle()">
            Reject
        </button>
    </form>
</main>