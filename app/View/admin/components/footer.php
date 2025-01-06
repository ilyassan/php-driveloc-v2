</div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const main = document.querySelector('main');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024) { 
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        function updateHeaderTitle() {
            const currentPage = window.location.pathname.split('/').pop().split('.')[0];
            const title = document.querySelector('header h1');
            
            const titles = {
                'dashboard': 'Dashboard',
                'vehicles': 'Vehicles Management',
                'create': 'Add New Vehicle',
                'categories': 'Categories Management',
                'reservations': 'Reservations',
                'upcoming': 'Upcoming Reservations',
                'users': 'Users Management'
            };
            title.textContent = titles[currentPage] || 'Dashboard';
        }

        updateHeaderTitle();


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