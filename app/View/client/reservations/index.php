<section class="container bg-gray-50 py-10">
    <!-- Hero Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800">My Reservations</h1>
        <p class="text-lg text-gray-600 mt-4">Track your vehicle bookings</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white shadow-lg p-6 rounded-lg mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Status Filter -->
            <div class="relative">
                <button
                    id="statusDropdown"
                    class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-white text-gray-500 focus:outline-none"
                >
                    <i class="fas fa-filter text-gray-500 mr-2"></i>
                    <span id="selectedStatus">All Status</span>
                    <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                </button>
                <ul
                    id="statusDropdownMenu"
                    class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                >
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('statusDropdown', 'selectedStatus', 'All Status')">All Status</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('statusDropdown', 'selectedStatus', 'Upcoming')">Upcoming</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('statusDropdown', 'selectedStatus', 'Active')">Active</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('statusDropdown', 'selectedStatus', 'Completed')">Completed</li>
                </ul>
            </div>

            <!-- Date Range Filters -->
            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
                <i class="fas fa-calendar text-gray-500 mr-2"></i>
                <input type="text" id="start_date" class="flatpickr text-gray-500 focus:outline-none w-full" placeholder="Start Date"/>
            </div>

            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
                <i class="fas fa-calendar text-gray-500 mr-2"></i>
                <input type="text" id="end_date" class="flatpickr text-gray-500 focus:outline-none w-full" placeholder="End Date"/>
            </div>
        </div>

        <!-- Apply Filters Button -->
        <button id="filterBtn" class="flex items-center gap-2 w-fit mx-auto mt-4 px-6 py-1.5 bg-red-500 text-white font-semibold rounded-lg shadow-lg hover:bg-red-600">
            Apply Filters <i class="fa-solid fa-check"></i>
        </button>
    </div>

    <div id="alert" class="text-center py-6 <?= (empty($reservations)) ? '': 'hidden'?>">
        <i class="fa-solid fa-envelope text-6xl text-gray-400 mb-6"></i>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">No Reservations Available</h2>
        <p class="text-gray-500">Sorry, we couldn't find any reservations matching your criteria.</p>
    </div>

    <!-- Reservation Cards -->
    <div id="reservationsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php if (!empty($reservations)): ?>
        <?php foreach ($reservations as $reservation): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Reservation Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-secondary"><?= htmlspecialchars($reservation["vehicle_name"]) ?></h3>
                            <p class="text-gray-500 text-sm"><?= htmlspecialchars($reservation["category_name"]) ?></p>
                            <p class="text-gray-500 text-sm">Location: <?= htmlspecialchars($reservation["place_name"]) ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            <?php
                            $pickupDate = new DateTime($reservation["from_date"]);
                            $returnDate = new DateTime($reservation["to_date"]);
                            $now = new DateTime();

                            $statusClass = '';
                            $statusText = '';

                            if ($pickupDate > $now) {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'Upcoming';
                            } elseif ($pickupDate <= $now && $returnDate >= $now) {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Active';
                            } else {
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Completed';
                            }
                            echo htmlspecialchars($statusClass);
                            ?>">
                            <?= htmlspecialchars($statusText) ?>
                        </span>
                    </div>

                    <!-- Reservation Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Pickup Date</p>
                            <p class="font-semibold"><?= htmlspecialchars((new DateTime($reservation["from_date"]))->format('M j, Y')) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Return Date</p>
                            <p class="font-semibold"><?= htmlspecialchars((new DateTime($reservation["to_date"]))->format('M j, Y')) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Duration</p>
                            <?php
                                $diff = $pickupDate->diff($returnDate);
                                $duration = $diff->days + 1;
                            ?>
                            <p class="font-semibold"><?= htmlspecialchars($duration) ?> Days</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total Cost</p>
                            <p class="font-semibold text-primary">$<?= htmlspecialchars($reservation["total_cost"]) ?></p>
                        </div>
                    </div>

                    <!-- Star Rating Section -->
                    <div class="mt-4">
                        <p class="text-gray-500 text-sm mb-2">Rate your experience:</p>
                        <div class="flex items-center justify-between rating-area">
                            <div class="flex items-center space-x-1 rating-container" data-vehicle-id="<?= htmlspecialchars($reservation['vehicle_id']) ?>">
                                <?php
                                    $fullStars = floor($reservation['rating']);
                                    $halfStar = ($reservation['rating'] - $fullStars) >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;

                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star text-yellow-400 text-xl cursor-pointer hover:text-yellow-500 star" data-star="'. htmlspecialchars($i + 1) .'"></i>';
                                    }
                                    if ($halfStar) {
                                        echo '<i class="fas fa-star-half-alt text-yellow-400 text-xl cursor-pointer hover:text-yellow-500 star" data-star="'. htmlspecialchars($fullStars + 1) .'"></i>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star text-yellow-400 text-xl cursor-pointer hover:text-yellow-500 star" data-star="'. htmlspecialchars($fullStars + $i + 1) .'"></i>';
                                    }
                                ?>
                            </div>
                            <?php if (!empty($reservation['rating'])): ?>
                                <div class="delete-rating-container" data-vehicle-id="<?= htmlspecialchars($reservation['vehicle_id']) ?>">
                                    <i class="fa-regular fa-trash-can text-primary text-xl cursor-pointer delete-rating-btn"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Include flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Include flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Initialize flatpickr
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        allowInput: true
    });

    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value) {
        document.getElementById(labelId).innerText = value;
        document.getElementById(`${dropdownId}Menu`).classList.add('hidden');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Event listeners for dropdown toggles
    document.getElementById('statusDropdown').addEventListener('click', function(event) {
        event.stopPropagation();
        toggleDropdown('statusDropdown', 'statusDropdownMenu');
    });

    document.addEventListener('click', function() {
        closeAllDropdowns();
    });

    document.getElementById('filterBtn').addEventListener('click', async function() {
        const selectedStatus = document.getElementById('selectedStatus').innerText;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        const reservationsContainer = document.getElementById('reservationsContainer');

        const res = await fetch("<?= htmlspecialchars(URLROOT . 'api/getReservations') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                status: selectedStatus,
                start_date: startDate,
                to_date: endDate,
                csrf_token: "<?= generateCsrfToken()?>"
            })
        });

        const data = await res.json();
        const filteredReservations = data.data;

        reservationsContainer.innerHTML = '';
        document.getElementById("alert").classList.add("hidden");

        if (filteredReservations.length === 0) {
            document.getElementById("alert").classList.remove("hidden");
           return;
        }

        filteredReservations.forEach(reservation => {
             const pickupDate = new Date(reservation.from_date);
            const returnDate = new Date(reservation.to_date);
            const now = new Date();
            let statusClass = '';
            let statusText = '';

            if (pickupDate > now) {
                statusClass = 'bg-yellow-100 text-yellow-800';
                statusText = 'Upcoming';
            } else if (pickupDate <= now && returnDate >= now) {
                statusClass = 'bg-green-100 text-green-800';
                statusText = 'Active';
            } else {
                statusClass = 'bg-gray-100 text-gray-800';
                statusText = 'Completed';
            }

            const cardHTML = `
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <!-- Reservation Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-secondary">${reservation.vehicle_name}</h3>
                                <p class="text-gray-500 text-sm">${reservation.category_name}</p>
                                <p class="text-gray-500 text-sm">Location: ${reservation.place_name}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold ${statusClass}">
                                ${statusText}
                            </span>
                        </div>

                        <!-- Reservation Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Pickup Date</p>
                                <p class="font-semibold">${pickupDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Return Date</p>
                                <p class="font-semibold">${returnDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Duration</p>
                                <p class="font-semibold">${Math.ceil((returnDate - pickupDate) / (1000 * 60 * 60 * 24)) + 1} Days</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Cost</p>
                                <p class="font-semibold text-primary">$${reservation.total_cost ? parseFloat(reservation.total_cost).toFixed(2) : '0.00'}</p>
                            </div>
                        </div>

                         <!-- Star Rating Section -->
                         <div class="mt-4">
                            <p class="text-gray-500 text-sm mb-2">Rate your experience:</p>
                            <div class="flex items-center justify-between rating-area">
                                <div class="flex items-center space-x-1 rating-container" data-vehicle-id="${reservation.vehicle_id}">
                                    ${Array.from({ length: 5 }, (_, index) => {
                                        const starValue = index + 1;
                                        const isFilled = starValue <= Math.floor(reservation.rating);
                                        let iconClass = 'far fa-star';
                                        if (isFilled) {
                                            iconClass = 'fas fa-star text-yellow-400 star';
                                        }
                                        return `
                                            <i class="${iconClass} text-yellow-400 text-xl cursor-pointer hover:text-yellow-500 star" data-star="${starValue}"></i>
                                        `;
                                    }).join('')}
                                </div>
                                ${reservation.rating != null ? `
                                <div class="delete-rating-container" data-vehicle-id="${reservation.vehicle_id}">
                                        <i class="fa-regular fa-trash-can text-primary text-xl cursor-pointer delete-rating-btn"></i>
                                </div>
                                 ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            reservationsContainer.innerHTML += cardHTML;
        });
        attachStarRatingListeners();
        attachDeleteRatingListeners();
    });

    function attachStarRatingListeners() {
        document.querySelectorAll('.rating-container').forEach(container => {
            const stars = container.querySelectorAll('.star');
            const vehicleId = container.getAttribute("data-vehicle-id");
           const ratingArea = container.closest('.rating-area');
            stars.forEach(star => {
                star.addEventListener('click', async function() {
                    const rating = parseInt(this.getAttribute("data-star"));
                    highlightStars(stars, rating);

                    const res = await fetch("<?= htmlspecialchars(URLROOT . 'api/rateReservation') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            vehicle_id: vehicleId,
                            rating: rating,
                            csrf_token: "<?= generateCsrfToken()?>"
                        })
                    });
                    
                    if (!ratingArea.querySelector('.delete-rating-container')) {
                        const deleteButtonHTML = `
                            <div class="delete-rating-container" data-vehicle-id="${vehicleId}">
                                <i class="fa-regular fa-trash-can text-primary text-xl cursor-pointer delete-rating-btn"></i>
                            </div>
                        `;
                        ratingArea.insertAdjacentHTML('beforeend', deleteButtonHTML);
                        attachDeleteRatingListeners();
                        }    
                });
            });
        });
    }

    function highlightStars(stars, rating) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute("data-star"));
            if (starValue <= rating) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }

   function attachDeleteRatingListeners() {
        document.querySelectorAll('.delete-rating-container').forEach(container => {
            const deleteButton = container.querySelector('.delete-rating-btn');
            const vehicleId = container.getAttribute("data-vehicle-id");
            const ratingContainer = container.closest('.rating-area').querySelector('.rating-container');

            deleteButton.addEventListener('click', async function() {
                const res = await fetch("<?= htmlspecialchars(URLROOT . 'api/deleteRating') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        csrf_token: "<?= generateCsrfToken()?>"
                    })
                });

                // Reset the stars to empty
               ratingContainer.innerHTML = Array.from({ length: 5 }, (_, index) => `
                    <i class="far fa-star text-yellow-400 text-xl cursor-pointer hover:text-yellow-500 star" data-star="${index + 1}"></i>
                `).join('');
                attachStarRatingListeners();
                container.remove();
            });
        });
    }

    // Attach initial listeners when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        attachStarRatingListeners();
        attachDeleteRatingListeners();
    });
</script>