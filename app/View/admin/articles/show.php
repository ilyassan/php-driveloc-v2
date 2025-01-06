<main class="container mx-auto py-12 max-w-4xl">
    <!-- Article Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex justify-between items-start mb-6">
            <span class="text-red-500 text-sm font-medium">June 15, 2024</span>
            <button class="text-gray-400 hover:text-red-500" onclick="openDeleteArticleModal()">
                <i class="far fa-trash-alt text-xl"></i>
            </button>
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
            <span class="flex items-center gap-1">
                <i class="far fa-eye"></i> 1.2k views
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

        <!-- Engagement Section -->
        <div class="border-t border-gray-200 mt-8 pt-6">
            <div class="flex items-center gap-6">
                <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                    <i class="far fa-thumbs-up"></i> 245
                </button>
                <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                    <i class="far fa-thumbs-down"></i> 12
                </button>
                <button class="flex items-center gap-2 text-gray-500">
                    <i class="far fa-comment"></i> 89 Comments
                </button>
                <button class="flex items-center gap-2 text-gray-500 hover:text-red-500">
                    <i class="far fa-share-square"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Comments (89)</h3>
        
        <!-- Comment Form -->
        <div class="mb-8">
            <textarea class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" rows="3" placeholder="Add a comment..."></textarea>
            <button class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600">Post Comment</button>
        </div>

        <!-- Comments List -->
        <div class="space-y-6">
            <!-- Comment 1 -->
            <div class="border-b border-gray-200 pb-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fas fa-user-circle text-4xl text-gray-600"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Mike Johnson</h4>
                        <span class="text-sm text-gray-500">2 hours ago</span>
                    </div>
                </div>
                <p class="text-gray-600">Great review! The performance specs are impressive...</p>
                <div class="flex items-center gap-4 mt-3">
                    <button class="text-sm text-gray-500 hover:text-red-500">Reply</button>
                    <button class="text-sm text-gray-500 hover:text-red-500">Like</button>
                    <button class="text-sm text-red-500 hover:text-red-600" onclick="openDeleteCommentModal()">Delete</button>
                </div>
            </div>

            <!-- Comment 2 -->
            <div class="border-b border-gray-200 pb-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fas fa-user-circle text-4xl text-gray-600"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Sarah Chen</h4>
                        <span class="text-sm text-gray-500">5 hours ago</span>
                    </div>
                </div>
                <p class="text-gray-600">The interior looks amazing...</p>
                <div class="flex items-center gap-4 mt-3">
                    <button class="text-sm text-gray-500 hover:text-red-500">Reply</button>
                    <button class="text-sm text-gray-500 hover:text-red-500">Like</button>
                    <button class="text-sm text-red-500 hover:text-red-600" onclick="openDeleteCommentModal()">Delete</button>
                </div>
            </div>
        </div>

        <!-- Load More Comments -->
        <button class="mt-6 w-full py-3 text-gray-500 hover:text-red-500 font-medium">
            Load More Comments
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

    <!-- Delete Comment Modal -->
    <div id="deleteCommentModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Delete Comment</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this comment?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeDeleteCommentModal()" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                <button onclick="deleteComment()" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
            </div>
        </div>
    </div>
</main>
