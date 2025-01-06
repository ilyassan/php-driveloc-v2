<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <main class="container mx-auto py-12 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Create New Article</h1>
                <p class="text-gray-600 mt-2">Share your automotive insights with the community</p>
            </div>

            <form class="space-y-6">
                <!-- Article Title -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="title">
                        Article Title
                    </label>
                    <input type="text" id="title" name="title" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Enter a descriptive title">
                </div>

                <!-- Theme Selection -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="theme">
                        Theme
                    </label>
                    <select id="theme" name="theme" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select a theme</option>
                        <option value="reviews">Car Reviews</option>
                        <option value="maintenance">Maintenance Tips</option>
                        <option value="news">Industry News</option>
                        <option value="buying">Buying Guides</option>
                    </select>
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

                <!-- Tags -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="tags">
                        Tags
                    </label>
                    <select id="tag-select" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <option value="">Select a tag</option>
                        <option value="bmw">BMW</option>
                        <option value="mercedes">Mercedes</option>
                        <option value="audi">Audi</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="review">Review</option>
                        <option value="performance">Performance</option>
                        <option value="electric">Electric</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                    
                    <!-- Container for selected tags -->
                    <div id="selected-tags" class="flex flex-wrap gap-2 mt-3"></div>
                    
                    <!-- Hidden input to store tags for form submission -->
                    <input type="hidden" id="tags" name="tags">
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
        document.addEventListener('DOMContentLoaded', () => {
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

            // Tags Management
            const tagSelect = document.getElementById('tag-select');
            const selectedTagsContainer = document.getElementById('selected-tags');
            const hiddenTagsInput = document.getElementById('tags');
            const selectedTags = new Set();

            tagSelect.addEventListener('change', (e) => {
                const selectedValue = e.target.value;
                if (selectedValue && !selectedTags.has(selectedValue)) {
                    selectedTags.add(selectedValue);
                    updateTagsDisplay();
                }
                tagSelect.value = ''; // Reset select to placeholder
            });

            function removeTag(tag) {
                selectedTags.delete(tag);
                updateTagsDisplay();
            }

            function updateTagsDisplay() {
                // Update visual tags
                selectedTagsContainer.innerHTML = Array.from(selectedTags)
                    .map(tag => `
                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                            #${tag}
                            <button type="button" onclick="removeTag('${tag}')" class="ml-2 text-gray-500 hover:text-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    `).join('');

                // Update hidden input value
                hiddenTagsInput.value = Array.from(selectedTags).join(',');
            }

            // Make removeTag function globally available
            window.removeTag = removeTag;

            // Form Submission
            const form = document.querySelector('form');
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const content = document.querySelector('input[name="content"]');
                content.value = quill.root.innerHTML;
                form.submit();
            });
        });
    </script>