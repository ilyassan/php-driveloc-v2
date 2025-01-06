<main class="container mx-auto py-12 max-w-4xl">
    <!-- Article Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex justify-between items-start mb-6">
            <span class="text-red-500 text-sm font-medium">June 15, 2024</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">The New BMW M3: A Perfect Blend of Power and Luxury</h1>
        <div class="flex items-center gap-6 text-sm text-gray-500 mb-8">
            <div class="flex items-center gap-3 px-4 py-2 text-gray-600">
                <i class="fas fa-user-circle text-xl"></i>
                <span class="font-medium">John Smith</span>
            </div>
            <span class="flex items-center gap-1">
                <i class="far fa-clock"></i> 8 min read
            </span>
        </div>

        <!-- Article Content -->
        <div class="prose max-w-none">
            <p class="text-gray-600 mb-4">Experience the thrill of BMW's latest masterpiece...</p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">Performance Specs</h2>
            <p class="text-gray-600 mb-4">The heart of the new M3 is its 3.0-liter twin-turbocharged inline-six engine...</p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">Interior Comfort</h2>
            <p class="text-gray-600 mb-4">The cabin features premium materials throughout...</p>
        </div>
    </div>

    <!-- Article Actions -->
    <div class="flex justify-between mb-6">
        <button class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-green-600" onclick="approveArticle()">
            Approve
        </button>
        <button class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-red-600" onclick="rejectArticle()">
            Reject
        </button>
    </div>

    <!-- Delete Article Modal -->
    <div id="deleteArticleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Delete Article</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this article?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeDeleteArticleModal()" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                <button onclick="deleteArticle()" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
            </div>
        </div>
    </div>
</main>

<script>
    function approveArticle() {
        // Handle approval logic here (e.g., send approval request to server)
        alert('Article approved');
    }

    function rejectArticle() {
        // Handle rejection logic here (e.g., send rejection request to server)
        alert('Article rejected');
    }

    function closeDeleteArticleModal() {
        document.getElementById('deleteArticleModal').classList.add('hidden');
    }

    function deleteArticle() {
        // Handle delete logic here
        alert('Article deleted');
        closeDeleteArticleModal();
    }
</script>
