<?php
// config/datetime.php
date_default_timezone_set('Asia/Bangkok');

function getThaiDate($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = time();
    } else if (is_string($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    
    return date('d/m/Y', $timestamp);
}

function getThaiTime($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = time();
    } else if (is_string($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    
    return date('H:i', $timestamp);
}

function getThaiDateTime($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = time();
    } else if (is_string($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    
    return date('d/m/Y H:i:s', $timestamp);
}

function getSQLDateTime() {
    return date('Y-m-d H:i:s');
}