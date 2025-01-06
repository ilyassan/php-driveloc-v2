<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section>
    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Monthly Profit Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500">Monthly Profit</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">$<?= htmlspecialchars($monthProfit) ?></h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
            <span class="text-sm font-medium <?= $ratioProfit > 0 ? 'text-green-600' : 'text-red-600' ?> flex items-center gap-1 mt-1">
                <i class="fas fa-arrow-<?= $ratioProfit > 0 ? 'up' : 'down' ?> text-xs"></i> <?= htmlspecialchars(number_format($ratioProfit, 2)) ?>% from last month
            </span>
        </div>

        <!-- Monthly Reservations Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500">Monthly Reservations</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2"><?= htmlspecialchars($monthReservations) ?></h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                </div>
            </div>
            <span class="text-sm font-medium <?= $diffReservations > 0 ? 'text-green-600' : 'text-red-600' ?> flex items-center gap-1 mt-1">
                <i class="fas fa-arrow-<?= $diffReservations > 0 ? 'up' : 'down' ?> text-xs"></i> <?= htmlspecialchars($diffReservations) ?> from last month
            </span>
        </div>

        <!-- Total Clients Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Clients</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2"><?= htmlspecialchars($usersCount) ?></h3>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <i class="fas fa-users text-purple-500 text-xl"></i>
                </div>
            </div>
            <span class="text-sm font-medium text-gray-600 flex items-center gap-1 mt-1">
                Total registered clients
            </span>
        </div>

        <!-- Average Rating Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500">Average Rating</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2"><?= htmlspecialchars(number_format($averageRating, 2)) ?></h3>
                    <div class="flex items-center gap-1 mt-1 text-yellow-400 text-sm">
                        <?php
                            $fullStars = floor($averageRating);
                            $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;

                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            }
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="far fa-star"></i>';
                            }
                        ?>
                    </div>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Overview</h3>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        <!-- Vehicle Categories Distribution -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Categories</h3>
            <canvas id="categoriesChart" height="300"></canvas>
        </div>
    </div>

    <!-- Highlights Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Rated Vehicle Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Highest Rated Vehicle</h3>
            <div class="flex gap-4">
                <div class="max-w-[55%]">
                    <img src="<?= htmlspecialchars(ASSETSROOT . "images/vehicles/" .$topVehicle->image_name) ?>" alt="Top Vehicle" class="object-cover rounded-lg">
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($topVehicle->name) ?></h4>
                    <div class="flex items-center gap-1 text-yellow-400 mt-2">
                        <?php
                            $fullStars = floor($topVehicle->rating);
                            $halfStar = ($topVehicle->rating - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;

                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            }
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="far fa-star"></i>';
                            }
                        ?>
                        <span class="ml-2 text-gray-600">(<?= htmlspecialchars(number_format($topVehicle->rating, 2)) ?>)</span>
                    </div>
                    <p class="text-gray-600 mt-2">Luxury Sports Car</p>
                    <p class="text-primary font-semibold mt-2">$<?= htmlspecialchars($topVehicle->price) ?>/day</p>
                </div>
            </div>
        </div>

        <!-- Recent Activities Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h3>
            <div class="space-y-4">
                <?php if (empty($recentActivities)): ?>
                    <p class="text-gray-600">No recent activities.</p>
                <?php else: ?>
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="flex items-center gap-4">
                            <?php if ($activity['type'] === 'reservation'): ?>
                                <div class="bg-green-100 p-2 rounded-full">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                            <?php elseif ($activity['type'] === 'registration'): ?>
                                <div class="bg-blue-100 p-2 rounded-full">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                            <?php elseif ($activity['type'] === 'rate'): ?>
                                <div class="bg-yellow-100 p-2 rounded-full">
                                    <i class="fas fa-star text-yellow-600"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-gray-800"><?= htmlspecialchars($activity['message']) ?></p>
                                <p class="text-sm text-gray-500">
                                    <?php
                                        $createdAt = new DateTime($activity['created_at']);
                                        $now = new DateTime();
                                        $interval = $now->diff($createdAt);

                                        if ($interval->y > 0) {
                                            echo htmlspecialchars($interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago');
                                        } elseif ($interval->m > 0) {
                                            echo htmlspecialchars($interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago');
                                        } elseif ($interval->d > 0) {
                                            echo htmlspecialchars($interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago');
                                        } elseif ($interval->h > 0) {
                                            echo htmlspecialchars($interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago');
                                        } elseif ($interval->i > 0) {
                                            echo htmlspecialchars($interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago');
                                        } else {
                                            echo 'Just now';
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Revenue Chart
    const revenues = <?= json_encode($lastSixMonthsRevenue) ?>;
    
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: Object.keys(revenues),
            datasets: [{
                label: 'Revenue',
                data: Object.values(revenues),
                borderColor: '#EF4444',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(239, 68, 68, 0.1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    const categories = <?= json_encode($popularCategories) ?>;
    
    // Categories Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'doughnut',
        data: {
            labels: categories.map(c => c.category),
            datasets: [{
                data: categories.map(c => c.reservations_count),
                backgroundColor: [
                    '#EF4444',
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#7C3AED'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>