<section class="p-6">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Blog Theme</h3>
        
        <form action="<?= URLROOT . 'themes/store' ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            <!-- Image Upload -->
            <div class="flex justify-center mb-4">
                <div class="flex relative justify-center w-96 h-60">
                    <img id="theme-image" class="border-2 border-gray-300 rounded-lg w-full h-full" src="../../assets/images/default.jpg" alt="Theme">
                    <label for="theme_image" class="cursor-pointer border-2 border-gray-300 rounded-lg absolute w-full h-full bg-gray-50 text-gray-500 flex justify-center items-center">Upload an Image</label>
                    <input type="file" id="theme_image" name="image" class="hidden" accept="image/gif, image/jpeg, image/png">
                </div>
            </div>

            <!-- Theme Name Input -->
            <div>
                <label for="theme_name" class="block mb-2 text-sm font-medium text-gray-700">Theme Name</label>
                <input 
                    type="text" 
                    name="theme_name" 
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-gray-700 focus:ring-1 focus:ring-primary focus:border-primary outline-none"
                    placeholder="Enter theme name"
                    autocomplete="off"
                />
            </div>

            <!-- Theme Description -->
            <div>
                <label for="theme_description" class="block mb-2 text-sm font-medium text-gray-700">Theme Description</label>
                <textarea
                    name="theme_description"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-gray-700 focus:ring-1 focus:ring-primary focus:border-primary outline-none"
                    placeholder="Enter theme description"
                    rows="4"
                ></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center mt-4">
                <button 
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors"
                >
                    Save
                </button>
            </div>
        </form>
    </div>

    <!-- Themes List Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Blog Themes</h3>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">
                <?= count($themes) ?> Themes
            </span>
        </div>

        <!-- Themes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($themes as $theme):?>
                <div class="group shadow-md relative bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <h4 class="text-gray-700 font-medium"><?= $theme['name'] ?></h4>
                    <p class="text-sm text-gray-500">
                        <?= $theme['description'] ?>
                    </p>
                    
                    <!-- Delete Button -->
                    <button 
                        onclick="confirmDelete('<?= $theme['name'] ?>', '<?= $theme['id'] ?>')"
                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity p-2 hover:text-red-600"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete Theme</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="themeToDelete"></span>"? This action cannot be undone.</p>
        
        <div class="flex justify-end gap-4">
            <button 
                onclick="closeDeleteModal()"
                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
                Cancel
            </button>
            <form action="<?= URLROOT . 'themes/delete' ?>" method="POST">
                <input id="theme_id" type="hidden" name="theme_id" value="">
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
    const label = document.querySelector("[for='theme_image']");
    const imageInput = document.getElementById("theme_image");
    const imageElement = document.getElementById("theme-image");

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

    let themeToDelete = '';

    function confirmDelete(theme, id) {
        themeToDelete = theme;
        document.getElementById('theme_id').value = id;
        document.getElementById('themeToDelete').textContent = theme;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
        themeToDelete = '';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
