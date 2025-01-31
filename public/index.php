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

    $router->add('GET', '/', 'HomePage@index', ["visitor", "client"]);
    $router->add('GET', '/vehicles', 'VehiclesPage@index', ["visitor", "client"]);
    $router->add('POST', '/vehicles/reservate/{id}', 'VehiclesDetailsPage@store', ["client"]);
    $router->add('GET', '/vehicles/{id}', 'VehiclesDetailsPage@index', ["visitor", "client"]);

    $router->add('GET', '/reservations', 'ReservationsPage@index', ["client"]);

    $router->add('GET', '/themes', 'ThemesPage@index', ["visitor", "client"]);
    $router->add('GET', '/themes/{id}', 'ArticlesPage@index', ["visitor", "client"]);

    $router->add('GET', '/articles/favorites', 'FavoritesPage@index', ["client"]);

    $router->add('GET', '/articles/create', 'ArticlesPage@create', ["client"]);
    $router->add('POST', '/articles/store', 'ArticlesPage@store', ["client"]);
    $router->add('GET', '/articles/{id}', 'ArticlesPage@show', ["visitor", "client"]);

    $router->add('POST', '/articles/like', 'ArticlesPage@like', ["client", "admin"]);
    $router->add('POST', '/articles/dislike', 'ArticlesPage@dislike', ["client", "admin"]);
    
    $router->add('GET', '/favorites', 'FavoritesPage@index', ["client"]);
    $router->add('POST', '/articles/addToFavorite', 'ArticlesPage@addToFavorite', ["client"]);
    
    $router->add('POST', '/comments/create', 'ArticlesPage@createComment', ["client"]);
    $router->add('POST', '/comments/update', 'ArticlesPage@updateComment', ["client"]);

    $router->add('POST', '/comments/delete', 'ArticlesPage@deleteComment', ["client", "admin"]);

    $router->add('POST', '/api/vehicles', 'VehiclesPage@getFilteredVehicles', ["visitor" , "client", "admin"]);
    $router->add('POST', '/api/getReservations', 'ReservationsPage@getFilteredReservations', ["client", "admin"]);
    $router->add('POST', '/api/rateReservation', 'ReservationsPage@rateReservationVehicle', ["client", "admin"]);
    $router->add('POST', '/api/deleteRating', 'ReservationsPage@deleteReservationRate', ["client", "admin"]);

    $router->add('GET', '/', 'DashboardPage@index', ["admin"]);

    $router->add('GET', '/vehicles', 'VehiclesAdminPage@index', ["admin"]);
    $router->add('GET', '/vehicles/create', 'VehiclesAdminPage@create', ["admin"]);
    $router->add('POST', '/vehicles/store', 'VehiclesAdminPage@store', ["admin"]);
    $router->add('GET', '/vehicles/edit/{id}', 'VehiclesAdminPage@edit', ["admin"]);
    $router->add('POST', '/vehicles/update/{id}', 'VehiclesAdminPage@update', ["admin"]);
    $router->add('POST', '/vehicles/delete', 'VehiclesAdminPage@delete', ["admin"]);

    $router->add('GET', '/categories', 'CategoriesAdminPage@index', ["admin"]);
    $router->add('POST', '/categories/store', 'CategoriesAdminPage@store', ["admin"]);
    $router->add('POST', '/categories/delete', 'CategoriesAdminPage@delete', ["admin"]);
    
    $router->add('GET', '/themes', 'ThemesAdminPage@index', ["admin"]);
    $router->add('POST', '/themes/store', 'ThemesAdminPage@store', ["admin"]);
    $router->add('POST', '/themes/delete', 'ThemesAdminPage@delete', ["admin"]);

    $router->add('GET', '/articles', 'ArticlesAdminPage@index', ["admin"]);
    $router->add('POST', '/articles/delete', 'ArticlesAdminPage@delete', ["admin"]);

    $router->add('GET', '/articles/pending', 'PendingArticlesPage@index', ["admin"]);
    $router->add('GET', '/articles/pending/{id}', 'PendingArticlesPage@show', ["admin"]);
    $router->add('POST', '/articles/publish', 'PendingArticlesPage@publishArticle', ["admin"]);
    $router->add('POST', '/articles/refuse', 'PendingArticlesPage@refuseArticle', ["admin"]);

    $router->add('GET', '/articles/{id}', 'ArticlesAdminPage@show', ["admin"]);

    $router->add('GET', '/tags', 'TagsPage@index', ["admin"]);
    $router->add('POST', '/tags/store', 'TagsPage@store', ["admin"]);
    $router->add('POST', '/tags/delete', 'TagsPage@delete', ["admin"]);

    $router->add('GET', '/users', 'UsersPage@index', ["admin"]);
    
    $router->add('GET', '/signup', 'SignupPage@index', ["visitor"]);
    $router->add('POST', '/signup', 'SignupPage@signup', ["visitor"]);
    $router->add('GET', '/login', 'LoginPage@index', ["visitor"]);
    $router->add('POST', '/login', 'LoginPage@login', ["visitor"]);
    $router->add('POST', '/logout', 'LoginPage@logout', ["client", "admin"]);

    $router->dispatch($request);