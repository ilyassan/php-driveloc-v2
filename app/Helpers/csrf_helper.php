<?php
    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function validateCsrfToken() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $csrfToken = $_POST['csrf_token'] ?? $data['csrf_token'] ?? "";

        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $csrfToken);
    }