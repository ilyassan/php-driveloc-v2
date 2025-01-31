<section class="p-6 flex justify-center">
    <!-- Pending Articles List -->
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Pending Articles</h3>
            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                <?= count($articles) ?> Articles
            </span>
        </div>

        <?php if (empty($articles)): ?>
            <!-- No Articles Fallback -->
            <div class="flex flex-col items-center justify-center text-center space-y-4 py-12">
                <h4 class="text-lg font-semibold text-gray-700">No Pending Articles Found</h4>
            </div>
        <?php else: ?>
            <!-- Articles Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Title</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Author</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Date Submitted</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Status</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php foreach ($articles as $article): ?>
                        <tr onclick="window.location.href='<?= htmlspecialchars(URLROOT . 'articles/pending/' . $article['id']) ?>'" class="hover:bg-gray-50 cursor-pointer transition-colors">
                            <td class="px-6 py-4 text-sm text-center"><?= $article['title'] ?></td>
                            <td class="px-6 py-4 text-sm text-center"><?= $article['author_name'] ?></td>
                            <td class="px-6 py-4 text-sm text-center"><?= $article['created_at'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form method="POST">
                                    <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                    <button formaction="<?= URLROOT . 'articles/publish' ?>" class="text-green-500 hover:text-green-600">Publish</button>
                                    <button formaction="<?= URLROOT . 'articles/refuse' ?>" class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
