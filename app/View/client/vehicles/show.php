<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<section class="container bg-gray-50 py-10">
    <!-- Car Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white shadow-lg rounded-lg p-6">
        <!-- Car Image -->
        <div class="flex items-center justify-center">
            <img src="<?= $vehicle->getImagePath() ?>" alt="Car Image" class="rounded-lg w-full h-80">
        </div>

        <!-- Car Information -->
        <div class="flex flex-col justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($vehicle->getName()) ?></h1>
                <p class="text-gray-600 mt-2">Anywhere, this car is got your back. This is a luxury car with advanced features and a comfortable interior, perfect for your trips.</p>
                <div class="mt-4">
                    <p class="text-lg text-gray-700"><strong>Model:</strong> <?= htmlspecialchars($vehicle->getModel()) ?></p>
                    <p class="text-lg text-gray-700"><strong>Seats:</strong> <?= htmlspecialchars($vehicle->getSeats()) ?></p>
                    <p class="text-lg text-gray-700"><strong>Price:</strong> $<?= htmlspecialchars($vehicle->getPrice()) ?> / Day</p>
                    <div class="mt-2 flex items-center text-sm text-yellow-400">
                        <?php
                            $fullStars = floor($vehicle->getRating());
                            $halfStar = ($vehicle->getRating() - $fullStars) >= 0.5 ? 1 : 0;
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
                        <span class="ml-2 text-gray-600">(<?= htmlspecialchars(number_format($vehicle->getRating(), 2)) ?>)</span>
                    </div>
                </div>
            </div>

            <!-- Date Range Inputs -->
            <div class="mt-8">
                <form action="<?= htmlspecialchars(URLROOT . 'vehicles/reservate/' . $vehicle->getId()) ?>" method="POST">
                    <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($vehicle->getId()) ?>">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- From Date -->
                        <div>
                            <label for="fromDate" class="block text-gray-600 font-semibold mb-2">Pickup Date:</label>
                            <input placeholder="YYYY-MM-DD" type="date" id="fromDate" name="from_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <!-- To Date -->
                        <div>
                            <label for="toDate" class="block text-gray-600 font-semibold mb-2">Return Date:</label>
                            <input placeholder="YYYY-MM-DD" type="date" id="toDate" name="to_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <!-- Location (Custom Dropdown) -->
                        <div>
                            <label for="locationDropdown" class="block text-gray-600 font-semibold mb-2">Location:</label>
                            <div class="relative">
                                <input id="place_id" type="hidden" name="place_id">
                                <span
                                    id="locationDropdown"
                                    class="flex cursor-pointer items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-white text-gray-500 focus:outline-none"
                                >
                                    <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                                    <span id="selectedLocation">Location</span>
                                    <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                                </span>
                                <!-- Dropdown Options -->
                                <ul
                                    id="locationDropdownMenu"
                                    class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                                >
                                    <?php foreach ($places as $place): ?>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('locationDropdown', 'selectedLocation', '<?= htmlspecialchars($place->getName()) ?>', '<?= htmlspecialchars($place->getId()) ?>')"><?= htmlspecialchars($place->getName()) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Reserve Button -->
                    <button type="submit" class="mt-6 w-full bg-red-500 text-white font-semibold rounded-lg py-2 shadow-lg hover:bg-red-600">
                        Reserve Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Additional Details Section -->
    <div class="mt-10 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800">Additional Information</h2>
        <p class="text-gray-600 mt-4">
            This car comes equipped with a powerful engine, premium leather seats, and advanced safety features to ensure a smooth and safe ride. Perfect for both city driving and long-distance trips.
        </p>
        <ul class="list-disc list-inside mt-4 text-gray-600">
            <li>Engine Type: V6 Turbocharged</li>
            <li>Fuel Efficiency: 25 MPG</li>
            <li>Transmission: Automatic</li>
            <li>Color Options: Red, Black, Silver</li>
            <li>Availability: In stock</li>
        </ul>
    </div>
</section>

<script>
    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value, id = null) {
        document.getElementById("place_id").value = id;
        document.getElementById(labelId).innerText = value;
        document.getElementById(`${dropdownId}Menu`).classList.add('hidden');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    document.getElementById('locationDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('locationDropdown', 'locationDropdownMenu');
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const notAvailableDates = (<?= json_encode($notAvailableDates) ?>).map(obj => [obj.from_date, obj.to_date]);

        const fromDateInput = document.getElementById('fromDate');
        const toDateInput = document.getElementById('toDate');
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);

        let fromDateInstance;
        let toDateInstance;

        function isDateNotAvailable(date) {
            if (!date) {
                return false;
            }
            for (const range of notAvailableDates) {
                const startDate = new Date(range[0]);
                const endDate = new Date(range[1]);
                const currentDate = new Date(date);

                if (currentDate >= startDate && currentDate <= endDate) {
                    return true;
                }
            }
            return false;
        }

        function addRedHighlight() {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(dateInput => {
                const calendarInput = dateInput.nextElementSibling;

                if (calendarInput && calendarInput.classList.contains('flatpickr-calendar')) {
                    const days = calendarInput.querySelectorAll('.flatpickr-day');
                    days.forEach(day => {
                        const date = day.getAttribute('aria-label');
                        const dayDate = day.getAttribute('data-day');

                        if (dayDate) {
                            const currentDate = new Date(calendarInput.querySelector('.flatpickr-month').getAttribute('aria-label') + '-' + dayDate);
                            if (isDateNotAvailable(currentDate)) {
                                day.classList.add('unavailable-date');
                                day.classList.remove('available-date');
                            } else {
                                day.classList.add('available-date');
                                day.classList.remove('unavailable-date');
                            }
                        } else {
                            day.classList.remove('available-date');
                            day.classList.remove('unavailable-date');
                        }
                    });
                }
            });
        }

        function getDisabledDates() {
            let disabledDates = [];
            for (let date = new Date(0); date < tomorrow; date.setDate(date.getDate() + 1)) {
                disabledDates.push(new Date(date));
            }
            return disabledDates;
        }

        fromDateInstance = flatpickr(fromDateInput, {
            disable: [
                ...getDisabledDates(),
                ...notAvailableDates.reduce((acc, range) => {
                    const start = new Date(range[0]);
                    const end = new Date(range[1]);

                    for (let date = new Date(start); date <= end; date.setDate(date.getDate() + 1)) {
                        acc.push(new Date(date));
                    }
                    return acc;
                }, [])
            ],
            onChange: function (selectedDates) {
                addRedHighlight();
                if (selectedDates[0]) {
                    toDateInstance.set('minDate', selectedDates[0]);
                }
            },
            onReady: function () {
                addRedHighlight();
            }
        });

        toDateInstance = flatpickr(toDateInput, {
            disable: [
                ...getDisabledDates(),
                ...notAvailableDates.reduce((acc, range) => {
                    const start = new Date(range[0]);
                    const end = new Date(range[1]);

                    for (let date = new Date(start); date <= end; date.setDate(date.getDate() + 1)) {
                        acc.push(new Date(date));
                    }
                    return acc;
                }, [])
            ],
            onChange: function (selectedDates) {
                addRedHighlight();
                if (selectedDates[0]) {
                    fromDateInstance.set('maxDate', selectedDates[0]);
                }
            },
            onReady: function () {
                addRedHighlight();
            }
        });
    });
</script>