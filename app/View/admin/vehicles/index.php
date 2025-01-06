<div class="py-6">
    <!-- Filter Bar -->
    <form id="filter" class="bg-white shadow-lg p-6 rounded-lg mb-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">

            <!-- Car Name Search -->
            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
                <i class="fas fa-car text-gray-500"></i>
                <input id="name" autocomplete="off" type="text" name="name" placeholder="Search by name" class="ml-2 text-gray-500 focus:outline-none w-full" />
            </div>

            <!-- Categories (Custom Dropdown) -->
            <div class="relative">
                <input id="category_id" type="hidden" name="category_id" value="">
                <button
                    type="button"
                    id="categoriesDropdown"
                    class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-white text-gray-500 focus:outline-none"
                >
                    <i class="fas fa-layer-group text-gray-500 mr-2"></i>
                    <span id="selectedCategories"><?= htmlspecialchars("Categories") ?></span>
                    <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                </button>
                <!-- Dropdown Options -->
                <ul
                    id="categoriesDropdownMenu"
                    class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                >
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('categoriesDropdown', 'selectedCategories', '<?= htmlspecialchars("All") ?>')"><?= htmlspecialchars("All") ?></li>
                    <?php foreach ($categories as $category): ?>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('categoriesDropdown', 'selectedCategories', '<?= htmlspecialchars($category->getName()) ?>', '<?= htmlspecialchars($category->getId()) ?>')"><?= htmlspecialchars($category->getName()) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Min Price -->
            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
                <i class="fas fa-dollar-sign text-gray-500"></i>
                <input id="min_price" type="number" name="min_price" placeholder="Min Price" class="ml-2 text-gray-500 focus:outline-none w-full" />
            </div>

            <!-- Max Price -->
            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
                <i class="fas fa-dollar-sign text-gray-500"></i>
                <input id="max_price" type="number" name="max_price" placeholder="Max Price" class="ml-2 text-gray-500 focus:outline-none w-full" />
            </div>
        </div>

        <!-- Search Button -->
        <button class="flex items-center gap-2 w-fit mx-auto mt-4 px-6 py-1.5 bg-red-500 text-white font-semibold rounded-lg shadow-lg hover:bg-red-600">
            <?= htmlspecialchars("Search") ?> <i class="fa-solid fa-search"></i>
        </button>
    </form>


    <!-- Vehicle Cards -->
    <div id="alert" class="text-center py-3"></div>
    <div id="vehicles" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($vehicles as $vehicle): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="<?= ASSETSROOT . "images/vehicles/" . $vehicle['image_name'] ?>" alt="Car Image" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-bold text-secondary"><?= htmlspecialchars($vehicle["name"]) ?></h3>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($vehicle["category"]) ?></p>
                <p class="text-gray-500 text-sm mb-0.5">Seats: <?= htmlspecialchars($vehicle["seats"]) ?></p>
                <p class="text-sm text-gray-500 mb-2 flex items-center"><i class="fas <?= htmlspecialchars($vehicle["type"]) == "Gas" ? "fa-gas-pump" : "fa-car-battery"?> text-gray-500 mr-1"></i> <?= htmlspecialchars($vehicle["type"]) ?></p>
                <div class="mt-2">
                    <span class="text-primary font-bold">$<?= htmlspecialchars($vehicle["price"]) ?>/ Day</span>
                </div>
                <div class="mt-2 flex items-center text-sm text-yellow-400">
                <?php
                    $fullStars = floor($vehicle["rating"]);
                    $halfStar = ($vehicle["rating"] - $fullStars) >= 0.5 ? 1 : 0;
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
                    <span class="ml-2 text-gray-600">(<?= htmlspecialchars(number_format($vehicle["rating"], 2)) ?>)</span>
                </div>
                <a href="<?= htmlspecialchars(URLROOT . 'vehicles/edit/' . $vehicle['id']) ?>" class="block mt-4 text-center text-secondary font-semibold hover:underline">
                    <?= htmlspecialchars("View Details") ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<script>
    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value, id = '') {
        document.getElementById("category_id").value = id;
        document.getElementById(labelId).innerText = value;
        document.getElementById(`${dropdownId}Menu`).classList.add('hidden');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Event listeners for dropdown toggles
    document.getElementById('categoriesDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('categoriesDropdown', 'categoriesDropdownMenu');
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });

    document.getElementById("filter").onsubmit = async function (e){
        e.preventDefault();
        
        let name = document.getElementById("name").value;
        let categoryId = document.getElementById("category_id").value;
        let minPrice = document.getElementById("min_price").value;
        let maxPrice = document.getElementById("max_price").value;

        let res = await fetch("<?= URLROOT . 'api/vehicles'?>",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                name,
                                category_id: categoryId,
                                min_price: minPrice,
                                max_price: maxPrice,
                                csrf_token: "<?= generateCsrfToken()?>"
                            })
                        });
        let filteredVehicles = (await res.json()).data;
        
        const vehicleCardsContainer = document.getElementById('vehicles');

        vehicleCardsContainer.innerHTML = '';
        document.getElementById("alert").innerHTML = '';
        
        if (filteredVehicles.length == 0) {
            document.getElementById("alert").innerHTML = `
                    <i class="fa-solid fa-car-burst text-6xl text-gray-400 mb-6"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4"><?= htmlspecialchars("No Cars Available") ?></h2>
                    <p class="text-gray-500"><?= htmlspecialchars("Sorry, we couldn't find any cars matching your criteria. Please try adjusting your filters.") ?></p>
            `;
            return;
        }
        filteredVehicles.forEach(vehicle => {
            const cardHTML = `
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img src="<?= ASSETSROOT . 'images/vehicles/' ?>${vehicle.image_name}" alt="Car Image" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-secondary">${vehicle.name}</h3>
                        <p class="text-gray-500 text-sm">${vehicle.category}</p>
                        <p class="text-gray-500 text-sm mb-0.5">Seats: ${vehicle.seats}</p>
                        <p class="text-sm text-gray-500 mb-2 flex items-center"><i class="fas ${vehicle.type === "Gas" ? "fa-gas-pump" : "fa-car-battery"} text-gray-500 mr-1"></i> ${vehicle.type}</p>
                        <div class="mt-2">
                            <span class="text-primary font-bold">$${vehicle.price}/ Day</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-yellow-400">
                            ${renderRatingStars(vehicle.rating)}
                            <span class="ml-2 text-gray-600">(${parseFloat(vehicle.rating || 0).toFixed(2)})</span>
                        </div>
                        <a href="<?=URLROOT . 'vehicles/edit/'?>${vehicle.id}" class="block mt-4 text-center text-secondary font-semibold hover:underline">
                            <?= htmlspecialchars("View Details") ?>
                        </a>
                    </div>
                </div>
            `;
            vehicleCardsContainer.innerHTML += cardHTML;
        });
    }

    function renderRatingStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating - fullStars) >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        let starsHTML = '';

        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            starsHTML += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<i class="far fa-star"></i>';
        }
        return starsHTML;
    }
</script>