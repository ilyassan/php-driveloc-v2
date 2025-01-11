<?php

    function user() {
        static $cachedUser = null;

        // Return cached user if available
        if ($cachedUser !== null) {
            return $cachedUser;
        }

        // Check if user ID is in session
        if (isset($_SESSION["user_id"])) {
            $userId = $_SESSION["user_id"];

            // Load the User model
            $userData = User::find($userId);

            if ($userData) {
                // Create and cache the AuthenticatedUser object
                $cachedUser = $userData;
                return $cachedUser;
            }else {
                unset($_SESSION["user_id"]);
                redirect("login");
            }
        }

        return null; // Return null if no user is logged in
    }