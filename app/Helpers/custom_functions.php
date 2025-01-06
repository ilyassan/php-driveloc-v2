<?php
    function array_first_not_null_value($array){
        return reset(array_filter($array, fn($value) => $value !== ""));
    }