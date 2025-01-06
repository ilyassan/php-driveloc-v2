<main class="container py-12">
    <!-- Hero Section -->
    <div class="hero flex flex-col md:flex-row items-center gap-8">
        <div class="text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-secondary mb-4">
                Get your dream <span class="text-primary">Car now</span>
            </h1>
            <p class="text-lg text-secondary font-semibold mb-6">
                Buy and sell reputable cars. Renting a car is easy and fast with Carex.
            </p>
            <div class="flex justify-center md:justify-start gap-5 text-3xl font-bold">
                <div>
                    <p class="text-secondary">30+</p>
                    <p class="text-base font-semibold text-secondary">Car brands</p>
                </div>
                <div class="min-h-full w-0.5 bg-gray-200"></div>
                <div>
                    <p class="text-secondary">1k+</p>
                    <p class="text-base font-semibold text-secondary">Clients</p>
                </div>
            </div>
        </div>
        <div class="hidden sm:block">
            <img src="<?= htmlspecialchars(ASSETSROOT . "images/car-hero.png") ?>" alt="Car Hero" class="max-w-2xl -scale-x-100 mx-auto">
        </div>
    </div>

    <h1 class="font-bold text-3xl text-center text-secondary">Our Collection</h1>

    <?php
        foreach ($categoriesWithVehicles as $category => $vehicles) {
    ?>
        <section class="bg-white py-12">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-secondary"><?= htmlspecialchars($category) ?></h2>
                <a href="<?= htmlspecialchars(URLROOT . 'vehicles') ?>" class="text-primary font-medium hover:underline flex items-center">
                    See all <span class="ml-1"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                    foreach ($vehicles as $vehicle) {
                ?>
                    <div onclick="window.location.href='<?= htmlspecialchars(URLROOT . 'vehicles/' . $vehicle['id']) ?>'" class="card cursor-pointer bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="<?= ASSETSROOT . "images/vehicles/" . $vehicle['image_name'] ?>" alt="Porsche Cayenne 2020" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($vehicle["name"]) ?></h3>
                            <p class="text-sm text-gray-500 flex items-center mb-2">
                                <span class="text-yellow-500">&#9733;</span>
                                <?= htmlspecialchars(number_format($vehicle["rating"], 2)) ?> (<?= htmlspecialchars($vehicle["rates_count"]) ?>) Reviews
                            </p>
                            <p class="text-sm text-gray-500 mb-2 flex items-center">
                                <span class="mr-1"><i class="fas fa-chair text-gray-500 mr-0.5"></i></span> <?= htmlspecialchars($vehicle["seats"]) ?> Seats
                            </p>
                            <p class="text-sm text-gray-500 mb-4 flex items-center">
                                <span class="mr-1"><i class="fas <?= htmlspecialchars($vehicle["type"] == "Gas" ? "fa-gas-pump" : "fa-car-battery") ?> text-gray-500 mr-0.5"></i></span> <?= htmlspecialchars($vehicle["type"]) ?>
                            </p>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-bold text-secondary">$<?= htmlspecialchars($vehicle["price"]) ?><span class="text-sm font-medium">/day</span></p>
                                <a href="<?= htmlspecialchars(URLROOT . 'vehicles/' . $vehicle['id']) ?>" class="bg-secondary text-white px-4 py-2 text-sm font-medium rounded-md">See Details</a>
                            </div>
                        </div>
                    </div>
                <?php
                    }    
                ?>
            </div>
        </section>
    <?php
        }    
    ?>

    <a href="<?= htmlspecialchars(URLROOT . 'vehicles') ?>" class="bg-primary mx-auto block w-fit text-white px-3 py-2 rounded-lg font-semibold">Explore All Cars</a>

</main>
<section class="relative bg-gray-900 text-white">
  <!-- Background Image -->
  <div
    class="absolute inset-0 bg-cover bg-center opacity-50"
    style="background-image: url('https://static.vecteezy.com/system/resources/previews/023/218/471/large_2x/red-sports-car-on-the-road-in-the-mountains-ai-generate-free-photo.jpg');"
  ></div>

  <!-- Content Container -->
  <div class="relative container mx-auto px-4 py-28 flex flex-col md:flex-row items-center gap-6">
    <!-- Text Section -->
    <div class="md:w-1/2">
      <h2 class="text-4xl font-bold mb-4">CAREX NEWS</h2>
      <p class="text-lg mb-6">
        More than 50 cars are available to you by Carex.
        Follow us to get notified by our latest cars.
      </p>

      <!-- Buttons -->
      <div class="flex gap-4">
        <a
          href="#"
          class="px-4 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700"
        >
          Receive news on your email
        </a>
      </div>
    </div>
    
  </div>
</section>
