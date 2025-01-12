<?php
// header-admin.php
?>
<!DOCTYPE html>
<html class="text-[12px] md:text-[14px] lg:text-[16px]">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href=<?= ASSETSROOT ."css/all.min.css"?> rel="stylesheet" />
    <link href=<?= ASSETSROOT ."css/fontawesome.min.css"?> rel="stylesheet" />
    <link href=<?= ASSETSROOT ."css/output.css"?> rel="stylesheet" />
    <title>CAREX Admin</title>
</head>
<body class="bg-gray-50">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed overflow-y-scroll no-scrollbar top-0 left-0 h-screen w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b">
            <span class="text-primary font-bold text-2xl">CAREX ADMIN</span>
        </div>

        <!-- Navigation Menu -->
        <nav class="py-4">
            <div class="px-4 mb-3">
                <div class="flex items-center gap-3 px-4 py-2 text-gray-600">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="font-medium"><?= user()->getName() ?></span>
                </div>
            </div>

            <div class="px-4 space-y-1">
                <?php
                    function isActive($path)
                    {
                        return $path == requestPath() ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-gray-100';
                    }
                ?>
                <!-- Dashboard -->
                <a href="<?= URLROOT ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT) ?> rounded-lg">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Vehicles Section -->
                <div class="space-y-1 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Vehicles</p>
                    <a href="<?= URLROOT . 'vehicles' ?>" class="flex items-center gap-3 px-4 py-2 <?=  baseUrl() == URLROOT . 'vehicles/edit' ? isActive(requestPath()) : isActive(URLROOT . 'vehicles') ?> rounded-lg">
                        <i class="fas fa-car"></i>
                        <span>All Vehicles</span>
                    </a>
                    <a href="<?= URLROOT . 'vehicles/create' ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT . 'vehicles/create') ?> rounded-lg">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Vehicle</span>
                    </a>
                </div>

                <!-- Categories Section -->
                <div class="space-y-1 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Vehicle Categories</p>
                    <a href="<?= URLROOT . 'categories' ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT . 'categories') ?> rounded-lg">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </div>

                <!-- Blog Section -->
                <div class="space-y-1 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Blog</p>
                    <a href="<?= URLROOT . 'themes' ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT . 'themes') ?> rounded-lg">
                        <i class="fas fa-palette"></i>
                        <span>Themes</span>
                    </a>
                    <a href="<?= URLROOT . 'tags' ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT . 'tags') ?> rounded-lg">
                        <i class="fas fa-hashtag"></i>
                        <span>Tags</span>
                    </a>
                    <a href="<?= URLROOT . 'articles' ?>" class="flex items-center gap-3 px-4 py-2 <?= strpos(requestPath(), URLROOT . 'articles') === 0 && strpos(requestPath(), URLROOT . 'articles/pending') !== 0 ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-gray-100' ?> rounded-lg">
                        <i class="fas fa-file-alt"></i>
                        <span>Articles</span>
                    </a>

                    <a href="<?= URLROOT . 'articles/pending' ?>" class="flex items-center gap-3 px-4 py-2 <?= strpos(requestPath(), URLROOT . 'articles/pending') === 0 ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-gray-100' ?> rounded-lg">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Pending Articles</span>
                    </a>
                </div>

                <!-- Users Section -->
                <div class="space-y-1 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Users</p>
                    <a href="<?= URLROOT . 'users' ?>" class="flex items-center gap-3 px-4 py-2 <?= isActive(URLROOT . 'users') ?> rounded-lg">
                        <i class="fas fa-user-friends"></i>
                        <span>All Users</span>
                    </a>
                </div>

                <!-- Logout Section -->
                <div class="space-y-1 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Logout</p>
                    <!-- Logout Button -->
                    <form action="<?= URLROOT . "logout" ?>" method="POST">
                        <button class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <header class="bg-white shadow-sm">
            <div class="flex h-16 items-center justify-between px-4 py-3">
                <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden bg-primary text-white p-2 rounded-lg">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="p-4">
        