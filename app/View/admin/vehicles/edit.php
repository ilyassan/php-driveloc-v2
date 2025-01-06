<div class="container mx-auto py-6">
    <div class="relative">
        <div onclick="confirmDelete()" class="absolute right-4 top-2">
            <button><i class="fas fa-plus text-primary text-3xl rotate-45"></i></button>
        </div>
        <form action="<?= htmlspecialchars(URLROOT . 'vehicles/update/' . $vehicle->getId()) ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex justify-center mb-4">
                <div class="flex relative justify-center w-96 h-60">
                    <img id="menu-image" class="border-2 border-gray-300 rounded-lg w-full h-full" src="<?= htmlspecialchars($vehicle->getImagePath()) ?>" alt="Menu">
                    <label for="image" class="cursor-pointer opacity-0 border-2 border-gray-300 rounded-lg absolute w-full h-full bg-gray-50 text-gray-500 flex justify-center items-center">Upload an Image</label>
                    <input type="file" id="image" name="image" class="hidden" accept="image/gif, image/jpeg, image/png">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Vehicle Name -->
                <div>
                    <label for="vehicle_name" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Name</label>
                    <input autocomplete="off" type="text" id="vehicle_name" name="vehicle_name" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= htmlspecialchars($vehicle->getName()) ?>" />
                </div>

                <!-- Vehicle Model -->
                <div>
                    <label for="vehicle_model" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Model</label>
                    <input autocomplete="off" type="text" id="vehicle_model" name="vehicle_model" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= htmlspecialchars($vehicle->getModel()) ?>" />
                </div>

                <!-- Category menu -->
                <div class="relative">
                    <input id="category_id" type="hidden" name="category_id" value="<?= htmlspecialchars($vehicle->getCategoryId()) ?>">
                    <label for="vehicle_category" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Category</label>

                    <span
                        id="categoriesDropdown"
                        class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-gray-50 text-gray-500 focus:outline-none"
                    >
                        <i class="fas fa-layer-group text-gray-500 mr-2"></i>
                        <span id="selectedCategories">
                            <?php
                                $array = array_filter($categories, function ($category) use ($vehicle) {
                                    return $category->getId() == $vehicle->getCategoryId();
                                });
                                echo htmlspecialchars(array_shift($array)->getName());
                            ?>
                        </span>
                        <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                    </span>
                    <!-- Dropdown Options -->
                    <ul
                        id="categoriesDropdownMenu"
                        class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                    >
                        <?php foreach ($categories as $category): ?>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('categoriesDropdown', 'selectedCategories', '<?= htmlspecialchars($category->getName()) ?>', 'category_id', '<?= htmlspecialchars($category->getId()) ?>')"><?= htmlspecialchars($category->getName()) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Type menu -->
                <div class="relative">
                    <input id="type_id" type="hidden" name="type_id" value="<?= htmlspecialchars($vehicle->getTypeId()) ?>">
                    <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Type</label>

                    <span
                        id="typesDropdown"
                        class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-gray-50 text-gray-500 focus:outline-none"
                    >
                        <i class="fas fa-tower-broadcast text-gray-500 mr-2"></i>
                        <span id="selectedType">
                            <?php
                                $array = array_filter($types, function ($type) use ($vehicle) {
                                    return $type->getId() == $vehicle->getTypeId();
                                });
                                echo htmlspecialchars(array_shift($array)->getName());
                            ?>
                        </span>
                        <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                    </span>
                    <!-- Dropdown Options -->
                    <ul
                        id="typesDropdownMenu"
                        class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                    >
                        <?php foreach ($types as $type): ?>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('typesDropdown', 'selectedType', '<?= htmlspecialchars($type->getName()) ?>', 'type_id', '<?= htmlspecialchars($type->getId()) ?>')"><?= htmlspecialchars($type->getName()) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Number of Seats -->
                <div>
                    <label for="seats" class="block mb-2 text-sm font-medium text-gray-700">Number of Seats</label>
                    <input type="number" id="seats" name="seats" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= htmlspecialchars($vehicle->getSeats()) ?>" />
                </div>

                <!-- Price per Day -->
                <div>
                    <label for="price_per_day" class="block mb-2 text-sm font-medium text-gray-700">Price per Day</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" id="price_per_day" name="price_per_day" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-7" value="<?= htmlspecialchars($vehicle->getPrice()) ?>" />
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-6 w-full bg-primary text-white py-2.5 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary">Update Vehicle</button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete Vehicle</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete "<?= htmlspecialchars($vehicle->getName()) ?>"? This action cannot be undone.</p>
        
        <div class="flex justify-end gap-4">
            <button 
                onclick="closeDeleteModal()"
                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
                Cancel
            </button>
            <form action="<?= htmlspecialchars(URLROOT . 'vehicles/delete') ?>" method="POST">
                <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($vehicle->getId()) ?>">
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                >
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    const label = document.querySelector("[for='image']");
    const imageInput = document.getElementById("image");
    const imageElement = document.getElementById("menu-image");

    imageInput.onchange = () => {
        if (imageInput.files && imageInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                imageElement.src = e.target.result;
                label.classList.add("opacity-0");
            };

            reader.readAsDataURL(imageInput.files[0]);
        }
    };

    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value, inputId, id = '') {
        document.getElementById(inputId).value = id;
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
    document.getElementById('typesDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('typesDropdown', 'typesDropdownMenu');
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });
</script>