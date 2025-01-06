<main class="bg-primary/10 py-12">
    <div class="max-w-xl bg-white p-5 rounded-lg shadow-lg mx-auto">
        <h1 class="font-bold text-2xl text-center mb-10 text-secondary">Log In To <span class="text-primary font-extrabold">Carex</span></h1>
        <form action="<?= URLROOT . "login" ?>" method="POST">
            <div class="mb-4">
                <label for="email" class="block mb-2 text-xs font-medium">Email</label>
                <input autocomplete="off" type="email" id="email" name="email" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="john.doe@company.com" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-xs font-medium">Password</label>
                <input autocomplete="off" type="password" id="password" name="password" class="outline-primary bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
            </div>
            <div class="mb-5 text-xs">
                Don't have an account? <a href="signup" class="text-primary font-bold">Sign Up</a>
            </div>
            <input type="submit" value="Log In" name="login" class="text-white w-full cursor-pointer hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/30 font-medium rounded-lg text-sm py-2 mt-5 bg-primary">
        </form>
    </div>
</main>