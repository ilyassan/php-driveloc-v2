<section class="p-6 flex justify-center">
    <!-- Clients List -->
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars("Clients") ?></h3>
            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                <?= htmlspecialchars($usersTotalCount) ?> Clients
            </span>
        </div>

        <!-- Clients Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-center"><?= htmlspecialchars("First Name") ?></th>
                        <th class="px-6 py-3 text-sm font-semibold text-center"><?= htmlspecialchars("Last Name") ?></th>
                        <th class="px-6 py-3 text-sm font-semibold text-center"><?= htmlspecialchars("Email") ?></th>
                        <th class="px-6 py-3 text-sm font-semibold text-center"><?= htmlspecialchars("Registration Date") ?></th>
                        <th class="px-6 py-3 text-sm font-semibold text-center"><?= htmlspecialchars("Status") ?></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-center"><?= htmlspecialchars($user['first_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-center"><?= htmlspecialchars($user['last_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-center"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4 text-sm text-center"><?= htmlspecialchars($user['created_at']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-sm rounded-full <?= $user['reservations_count'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                                    <?= htmlspecialchars($user['reservations_count'] > 0 ? 'Active' : 'Inactive') ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            <nav role="navigation" aria-label="Pagination Navigation">
                <ul class="flex items-center space-x-2">
                    <?php
                    // Pagination logic
                    $usersPerPage = 10;
                    $totalPages = ceil($usersTotalCount / $usersPerPage);
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

                    // Adjust start and end pages if near the beginning or end
                    if ($currentPage <= $halfVisible) {
                        $endPage = min($visiblePages, $totalPages);
                    } elseif ($currentPage + $halfVisible > $totalPages) {
                        $startPage = max(1, $totalPages - $visiblePages + 1);
                    }

                    // Previous and next pages
                    $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;
                    $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : null;
                    ?>

                    <!-- Previous Button -->
                    <li>
                        <a href="<?= $previousPage ? "?page=" . htmlspecialchars($previousPage) : '#' ?>"
                           class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100 <?= !$previousPage ? 'opacity-50 cursor-not-allowed' : '' ?>"
                           <?= !$previousPage ? 'aria-disabled="true"' : '' ?>>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- First Page -->
                    <?php if ($startPage > 1): ?>
                        <li>
                            <a href="?page=1" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">1</a>
                        </li>
                        <!-- Ellipsis -->
                        <?php if ($startPage > 2): ?>
                            <li>
                                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-700">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li>
                            <a href="?page=<?= htmlspecialchars($i) ?>"
                               class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                                <?= htmlspecialchars($i) ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Last Page -->
                    <?php if ($endPage < $totalPages): ?>
                        <!-- Ellipsis -->
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li>
                                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-700">...</span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="?page=<?= htmlspecialchars($totalPages) ?>" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">
                                <?= htmlspecialchars($totalPages) ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <li>
                        <a href="<?= $nextPage ? "?page=" . htmlspecialchars($nextPage) : '#' ?>"
                           class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100 <?= !$nextPage ? 'opacity-50 cursor-not-allowed' : '' ?>"
                           <?= !$nextPage ? 'aria-disabled="true"' : '' ?>>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>