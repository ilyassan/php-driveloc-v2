<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <main class="container mx-auto py-12 max-w-4xl">

        <!-- Back Link -->
        <div class="mb-4">
            <a href="<?= URLROOT . 'themes' ?>" class="flex items-center text-primary text-3xl">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Create New Article</h1>
                <p class="text-gray-600 mt-2">Share your automotive insights with the community</p>
            </div>

            <form id="article-form" class="space-y-6" method="POST" action="<?= URLROOT . 'articles/store' ?>">
                <!-- Article Title -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="title">
                        Article Title
                    </label>
                    <input type="text" id="title" name="title" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Enter a descriptive title">
                </div>

                <!-- Themes (Custom Dropdown) -->
                <div class="relative">
                    <input id="selectedThemes_value" type="hidden" name="theme_id" value="">
                    <button
                        type="button"
                        id="themesDropdown"
                        class="flex items-center border border-gray-300 rounded-md px-4 py-3 w-full bg-white text-gray-500 focus:outline-none"
                    >
                        <span id="selectedThemes">Select Theme</span>
                        <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                    </button>
                    <!-- Dropdown Options -->
                    <ul
                        id="themesDropdownMenu"
                        class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                    >
                        <?php foreach ($themes as $theme): ?>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption('themesDropdown', 'selectedThemes', '<?= htmlspecialchars($theme['name']) ?>', '<?= htmlspecialchars($theme['id']) ?>')"><?= htmlspecialchars($theme['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Article Content -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Article Content
                    </label>
                    <div id="editor-container" class="border border-gray-300 rounded-lg" style="height: 300px;"></div>
                    <!-- Hidden input to store content -->
                    <input type="hidden" name="content">
                </div>

                <!-- Tags (Custom Dropdown) -->
                <div class="relative">
                    <button
                        type="button"
                        id="tagsDropdown"
                        class="flex items-center border border-gray-300 rounded-md px-4 py-3 w-full bg-white text-gray-500 focus:outline-none"
                    >
                        <span id="selectedTags">Select Tags</span>
                        <i class="fas fa-chevron-down ml-auto text-gray-400"></i>
                    </button>
                    <!-- Dropdown Options -->
                    <ul
                        id="tagsDropdownMenu"
                        class="absolute dropdown-menu hidden bg-white shadow-md rounded-md w-full mt-2 z-10"
                    >
                        <?php foreach ($tags as $tag): ?>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectTag(<?= $tag['id'] ?>)"><?= htmlspecialchars($tag['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div id="tagsContainer" class="flex gap-2">
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-6 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600">
                        Request Publish
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Quill Editor Initialization
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Write your article content here...',
            modules: {
                toolbar: [
                    [{ 'font': [] }], // Font family
                    [{ 'size': [false, 'large', 'huge'] }], // Font size
                    ['bold', 'italic', 'underline'], // Text styles
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Lists
                ]
            }
        });

        // Form Submission
        const form = document.getElementById('article-form');
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const content = document.querySelector('input[name="content"]');
            content.value = quill.root.innerHTML;
            form.submit();
        });



    function toggleDropdown(dropdownId, menuId) {
        closeAllDropdowns();
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function selectOption(dropdownId, labelId, value, id = '') {
        document.getElementById(labelId + "_value").value = id;
        document.getElementById(labelId).innerText = value;
        document.getElementById(dropdownId).classList.remove("text-gray-500");
        document.getElementById(`${dropdownId}Menu`).classList.add('hidden');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Event listeners for dropdown toggles
    document.getElementById('themesDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('themesDropdown', 'themesDropdownMenu');
    });
    document.getElementById('tagsDropdown').addEventListener('click', function (event) {
        event.stopPropagation();
        toggleDropdown('tagsDropdown', 'tagsDropdownMenu');
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });


    let tags = <?= json_encode($tags) ?>;
    let selectedTags = [];
    let tagsContainer = document.getElementById("tagsContainer");
    let tagsInput = document.getElementById("tags");

    function selectTag(id){
        let i = tags.findIndex(tag => tag.id == id);
        let tag = tags.splice(i, 1)[0];
        selectedTags.push(tag);
        refreshTags();  
    }

    function removeTag(id){
        let i = selectedTags.findIndex(tag => tag.id == id);
        let tag = selectedTags.splice(i, 1)[0];
        tags.push(tag);
        refreshTags();           
    }


    function refreshTags(){
        tagsContainer.innerHTML = "";

        for (let tag of selectedTags) {
            tagsContainer.innerHTML += `
                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                    #${tag.name}
                    <button type="button" onclick="removeTag('${tag.id}')" class="ml-2 text-gray-500 hover:text-red-500">
                        <i class="fas fa-times text-primary"></i>
                    </button>
                    <input type="hidden" name="tag_ids[]" value="${tag.id}" >
                </span>
            `
        }

        document.getElementById("tagsDropdownMenu").innerHTML = "";
        
        for (let tag of tags) {
            document.getElementById("tagsDropdownMenu").innerHTML += `
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectTag(${tag.id})">${tag.name}</li>
            `;
        }
    }
    </script>