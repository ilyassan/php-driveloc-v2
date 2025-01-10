<section class="p-6 flex justify-center">
    <!-- Pending Articles List -->
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Pending Articles</h3>
            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                <?= count($articles) ?> Articles
            </span>
        </div>

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
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center"><?= $article['title'] ?></td>
                        <td class="px-6 py-4 text-sm text-center"><?= $article['author_name'] ?></td>
                        <td class="px-6 py-4 text-sm text-center"><?= $article['created_at'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>
