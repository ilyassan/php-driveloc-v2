<?php
    function array_first_not_null_value($array){
        return reset(array_filter($array, fn($value) => $value !== ""));
    }

    function getTimeAgoFromDate($date)
    {
        $createdAt = new DateTime($date);
        $now = new DateTime();
        $interval = $now->diff($createdAt);

        if ($interval->y > 0) {
            echo htmlspecialchars($interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago');
        } elseif ($interval->m > 0) {
            echo htmlspecialchars($interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago');
        } elseif ($interval->d > 0) {
            echo htmlspecialchars($interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago');
        } elseif ($interval->h > 0) {
            echo htmlspecialchars($interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago');
        } elseif ($interval->i > 0) {
            echo htmlspecialchars($interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago');
        } else {
            echo 'Just now';
        }
    }

    function getExcerpt($content, $length = 100)
    {
        $plainText = strip_tags($content);
        return strlen($plainText) > $length ? substr($plainText, 0, $length) . '...' : $plainText;
    }