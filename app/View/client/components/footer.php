<footer class="bg-gray-50 py-8 text-gray-800">
  <div class="container mx-auto px-4">
    <div class="flex flex-col gap-10 md:flex-row md:justify-between">
      <!-- Company Info -->
      <div>
        <h2 class="text-2xl font-bold text-red-600 mb-2">CAREX</h2>
        <p class="text-sm">
        Rent new cars with famous brands such as Bentley, Mercedes, Audi, Porsche, Honda...
        </p>
        <div class="mt-4 flex gap-4 text-gray-500">
          <!-- Social Media Icons -->
          <a href="#" aria-label="Facebook" class="hover:text-red-600"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="Twitter" class="hover:text-red-600"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="Instagram" class="hover:text-red-600"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="YouTube" class="hover:text-red-600"><i class="fab fa-youtube"></i></a>
          <a href="#" aria-label="TikTok" class="hover:text-red-600"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

      <div class="flex justify-around w-3/4">
        <!-- Navigation Links -->
        <div>
            <h3 class="text-lg font-bold mb-4">Links</h3>
            <ul class="text-sm space-y-2">
            <li><a href="#" class="hover:underline">Home</a></li>
            <li><a href="#" class="hover:underline">Vehicles</a></li>
            <li><a href="#" class="hover:underline">Reservations</a></li>
            </ul>
        </div>

        <!-- Services Links -->
        <div>
            <h3 class="text-lg font-bold mb-4">Services</h3>
            <ul class="text-sm space-y-2">
            <li><a href="#" class="hover:underline">Delivery</a></li>
            <li><a href="#" class="hover:underline">Warranty</a></li>
            </ul>
        </div>

        <!-- Company Links -->
        <div>
            <h3 class="text-lg font-bold mb-4">Company</h3>
            <ul class="text-sm space-y-2">
            <li><a href="#" class="hover:underline">Contact Us</a></li>
            <li><a href="#" class="hover:underline">Our policy</a></li>
            </ul>
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="mt-8 border-t border-gray-300 pt-4 text-center text-sm text-gray-600">
      &copy; 2025 CAREX. All rights reserved.
    </div>
  </div>
</footer>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const burgerMenu = document.getElementById("burger-menu");
    const menu = document.getElementById("menu");
    burgerMenu.onclick = () => {
      menu.classList.toggle("-top-[500%]");
      menu.classList.toggle("top-full");
      burgerMenu.classList.toggle("text-primary");
      burgerMenu.classList.toggle("text-based");
    };

    let successMessage = <?= json_encode(flash("success")); ?>;
    if (successMessage) {
        Swal.fire("Success", successMessage, "success");
    }

    let errorMessage = <?= json_encode(flash("error")); ?>;
    if (errorMessage) {
        Swal.fire("Error", errorMessage, "error");
    }

    let warningMessage = <?= json_encode(flash("warning")); ?>;
    if (warningMessage) {
        Swal.fire("Warning", warningMessage, "warning");
    }

    let forms = document.querySelectorAll('form');
    if (forms.length > 0) {
        forms.forEach(form => {
            if(form.method == "get") return;
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', 'csrf_token');
            input.value = "<?= generateCsrfToken() ?>";
            form.appendChild(input);
        });
    }
  </script>
  </body>
</html>
