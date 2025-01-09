<main class="container mx-auto py-12">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Explore Our <span class="text-red-500">Blog Themes</span>
            </h1>
            <p class="text-lg text-gray-600">
                Discover expert insights, tips, and news about the automotive world
            </p>
        </div>

        <div class="flex justify-end my-3">
            <a href="<?= URLROOT . 'articles/create' ?>" class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600 transition-colors">
                Create Article <i class="fas fa-plus"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($themes as $theme):?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition-shadow">
                    <img src="<?= $theme["image_name"] ? ASSETSROOT . 'images/themes/' .  $theme["image_name"] : 'https://placehold.co/600x400?text=Minimalist+Theme' ?>" alt="Car Reviews" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= $theme["name"] ?></h3>
                        <p class="text-gray-600 mb-4"><?= $theme["description"] ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500"><?= $theme["articles_count"] ?> Articles</span>
                            <a href="<?= URLROOT . 'themes/' . $theme['id'] ?>" class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600 transition-colors">
                                View Articles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        
    </main>