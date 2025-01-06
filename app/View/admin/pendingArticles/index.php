<section class="p-6 flex justify-center">
    <!-- Pending Articles List -->
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Pending Articles</h3>
            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                5 Articles
            </span>
        </div>

        <!-- Articles Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-center">Title</th>
                        <th class="px-6 py-3 text-sm font-semibold text-center">Author</th>
                        <th class="px-6 py-3 text-sm font-semibold text-center">Date Submitted</th>
                        <th class="px-6 py-3 text-sm font-semibold text-center">Status</th>
                        <th class="px-6 py-3 text-sm font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center">Understanding JavaScript</td>
                        <td class="px-6 py-4 text-sm text-center">John Doe</td>
                        <td class="px-6 py-4 text-sm text-center">2025-01-05</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center">How to Learn React</td>
                        <td class="px-6 py-4 text-sm text-center">Jane Smith</td>
                        <td class="px-6 py-4 text-sm text-center">2025-01-04</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center">Mastering CSS Grid</td>
                        <td class="px-6 py-4 text-sm text-center">Alice Brown</td>
                        <td class="px-6 py-4 text-sm text-center">2025-01-03</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center">Vue.js Basics</td>
                        <td class="px-6 py-4 text-sm text-center">David Lee</td>
                        <td class="px-6 py-4 text-sm text-center">2025-01-02</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-center">Intro to Node.js</td>
                        <td class="px-6 py-4 text-sm text-center">Emily White</td>
                        <td class="px-6 py-4 text-sm text-center">2025-01-01</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-green-500 hover:text-green-600">Approve</button>
                            <button class="text-red-500 hover:text-red-600 ml-4">Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            <nav role="navigation" aria-label="Pagination Navigation">
                <ul class="flex items-center space-x-2">
                    <li>
                        <a href="#" class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100 opacity-50 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">1</a>
                    </li>
                    <li>
                        <a href="#" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">2</a>
                    </li>
                    <li>
                        <a href="#" class="px-4 py-2 rounded-md bg-white text-gray-700 hover:bg-gray-100">3</a>
                    </li>
                    <li>
                        <a href="#" class="px-4 py-2 rounded-md bg-white text-gray-500 hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>
