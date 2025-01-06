<?php    
    require_once __DIR__ . '/../app/Config/config.php';

    function require_all_files($directory) {
        foreach (glob($directory . '/*.php') as $filename) {
            require_once $filename;
        }
    }

    // Require all core files
    require_all_files(__DIR__ . '/../app/Core');
    $db = new Database();
    BaseClass::setDatabase($db);

    require_all_files(__DIR__ . '/../app/Classes');
    require_all_files(__DIR__ . '/../app/Helpers');

    // Define the routes
    $router = new Router();
    $request = new Request();

    $router->add('GET', '/', 'HomePage@index', "client");
    $router->add('GET', '/vehicles', 'VehiclesPage@index', "client");
    $router->add('POST', '/vehicles/reservate/{id}', 'VehiclesDetailsPage@store', "client");
    $router->add('GET', '/vehicles/{id}', 'VehiclesDetailsPage@index', "client");

    $router->add('GET', '/reservations', 'ReservationsPage@index', "client");
    $router->add('GET', '/blog', 'BlogThemesPage@index', "client");
    $router->add('GET', '/blog/themes/articles/create', 'ArticlesPage@create', "client");
    $router->add('GET', '/blog/themes/articles/{id}', 'ArticlesPage@show', "client");
    $router->add('GET', '/blog/themes/{id}', 'ArticlesPage@index', "client");

    $router->add('GET', '/blog/favorites', 'FavoritesPage@index', "client");

    $router->add('POST', '/api/vehicles', 'VehiclesPage@getFilteredVehicles');
    $router->add('POST', '/api/getReservations', 'ReservationsPage@getFilteredReservations');
    $router->add('POST', '/api/rateReservation', 'ReservationsPage@rateReservationVehicle');
    $router->add('POST', '/api/deleteRating', 'ReservationsPage@deleteReservationRate');

    $router->add('GET', '/', 'DashboardPage@index', "admin");

    $router->add('GET', '/vehicles', 'VehiclesAdminPage@index', "admin");
    $router->add('GET', '/vehicles/create', 'VehiclesAdminPage@create', "admin");
    $router->add('POST', '/vehicles/store', 'VehiclesAdminPage@store', "admin");
    $router->add('GET', '/vehicles/edit/{id}', 'VehiclesAdminPage@edit', "admin");
    $router->add('POST', '/vehicles/update/{id}', 'VehiclesAdminPage@update', "admin");
    $router->add('POST', '/vehicles/delete', 'VehiclesAdminPage@delete', "admin");

    $router->add('GET', '/categories', 'CategoriesAdminPage@index', "admin");
    $router->add('POST', '/categories/store', 'CategoriesAdminPage@store', "admin");
    $router->add('POST', '/categories/delete', 'CategoriesAdminPage@delete', "admin");
    
    $router->add('GET', '/themes', 'ThemesAdminPage@index', "admin");
    $router->add('GET', '/articles', 'ArticlesAdminPage@index', "admin");

    $router->add('GET', '/users', 'UsersPage@index', "admin");
    
    $router->add('GET', '/signup', 'SignupPage@index');
    $router->add('POST', '/signup', 'SignupPage@signup');
    $router->add('GET', '/login', 'LoginPage@index');
    $router->add('POST', '/login', 'LoginPage@login');
    $router->add('POST', '/logout', 'LoginPage@logout');

    $router->dispatch($request);