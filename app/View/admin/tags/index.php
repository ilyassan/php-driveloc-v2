<section class="p-6">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Tag</h3>
        
        <form action="<?= URLROOT . 'tags/store' ?>" method="POST" class="flex flex-col gap-4">
            <!-- Tag Name Input -->
            <div class="flex-1">
                <label for="tag_name" class="block mb-2 text-sm font-medium text-gray-700">
                    Tag Name
                </label>
                <div id="inputs" class="space-y-3">
                    <input 
                        type="text" 
                        name="tags_name[]" 
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-gray-700 focus:ring-1 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Enter tag name"
                        autocomplete="off"
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center">
                <div class="flex gap-6 text-primary text-3xl">
                    <button 
                        type="button"
                        onclick="addInput()"
                    >
                        <i class="fas fa-plus"></i>
                    </button>
                    <button 
                        type="button"
                        onclick="removeInput()"
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <button 
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors"
                >
                    Save
                </button>
            </div>
        </form>
    </div>

    <!-- Tags List Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Tags</h3>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">
                <?= count($tags) ?> Tags
            </span>
        </div>

        <!-- Tags Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($tags as $tag):?>
                <div class="group shadow-md relative bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <!-- Tag Name -->
                    <h4 class="text-gray-700 font-medium"><?= $tag["name"] ?></h4>
                    
                    <!-- Delete Button -->
                    <button 
                        onclick="confirmDelete('<?= $tag['name'] ?>', '<?= $tag['id'] ?>')"
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
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete Tag</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="tagToDelete"></span>"? This action cannot be undone.</p>
        
        <div class="flex justify-end gap-4">
            <button 
                onclick="closeDeleteModal()"
                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
                Cancel
            </button>
            <form action="<?= URLROOT . 'tags/delete' ?>" method="POST">
                <input id="tag_id" type="hidden" name="tag_id" value="">
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
    let tagToDelete = '';

    function confirmDelete(tag, id) {
        tagToDelete = tag;
        document.getElementById('tag_id').value = id;

        document.getElementById('tagToDelete').textContent = tag;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
        tagToDelete = '';
        document.getElementById('tag_id').value = '';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    let inputsContainer = document.getElementById('inputs');
    let count = 1;
    let inputCopy = inputsContainer.firstElementChild.cloneNode(true);

    function addInput() {
        count++;
        inputsContainer.appendChild(inputCopy.cloneNode(true));
    }

    function removeInput(){
        if (count > 1) {
            count--;
            inputsContainer.removeChild(inputsContainer.lastElementChild);
        }
    }
</script>
