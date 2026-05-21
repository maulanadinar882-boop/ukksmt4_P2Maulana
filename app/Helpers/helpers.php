<?php

function encryptPassword($password) {
    return hash('sha256', $password . 'parkir_salt_2024');
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function getRole() {
    return $_SESSION['user']['role'] ?? null;
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}