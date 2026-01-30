<?php

if (!function_exists('strmax')) {
    function strmax($string, $length = 20) {
        if (strlen($string) <= $length) return $string;
        return substr($string, 0, $length) . '...';
    }
}
