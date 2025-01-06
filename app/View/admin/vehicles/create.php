<div class="container mx-auto py-6">
    <form action="<?= htmlspecialchars(URLROOT . "vehicles/store") ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-center mb-4">
            <div class="flex relative justify-center w-96 h-60">
                <img id="menu-image" class="border-2 border-gray-300 rounded-lg w-full h-full" src="../../assets/images/dishes/23808324.jpg" alt="Menu">
                <label for="image" class="cursor-pointer border-2 border-gray-300 rounded-lg absolute w-full h-full bg-gray-50 text-gray-500 flex justify-center items-center">Upload an Image</label>
                <input type="file" id="image" name="image" class="hidden" accept="image/gif, image/jpeg, image/png">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Vehicle Name -->
            <div>
                <label for="vehicle_name" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Name</label>
                <input autocomplete="off" type="text" id="vehicle_name" name="vehicle_name" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Porsche" />
            </div>

            <!-- Vehicle Model -->
            <div>
                <label for="vehicle_model" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Model</label>
                <input autocomplete="off" type="text" id="vehicle_model" name="vehicle_model" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="2024" />
            </div>

            <!-- Category menu -->
            <div class="relative">
                <input id="category_id" type="hidden" name="category_id" value="">
                <label for="vehicle_category" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Category</label>

                <span
                    id="categoriesDropdown"
                    class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-gray-50 text-gray-500 focus:outline-none"
                >
                    <i class="fas fa-layer-group text-gray-500 mr-2"></i>
                    <span id="selectedCategories">Categories</span>
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
                <input id="type_id" type="hidden" name="type_id" value="">
                <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-700">Vehicle Type</label>

                <span
                    id="typesDropdown"
                    class="flex items-center border border-gray-300 rounded-md px-4 py-2 w-full bg-gray-50 text-gray-500 focus:outline-none"
                >
                    <i class="fas fa-tower-broadcast text-gray-500 mr-2"></i>
                    <span id="selectedType">Types</span>
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
                <input type="number" id="seats" name="seats" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="4" />
            </div>

            <!-- Price per Day -->
            <div>
                <label for="price_per_day" class="block mb-2 text-sm font-medium text-gray-700">Price per Day</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-500 text-sm">$</span>
                    </div>
                    <input type="number" id="price_per_day" name="price_per_day" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-7" placeholder="250" />
                </div>
            </div>
        </div>

        <button type="submit" class="mt-6 w-full bg-primary text-white py-2.5 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary">Add Vehicle</button>
    </form>
</div>

<script>
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