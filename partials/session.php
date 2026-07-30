<?php
// Dipanggil di awal setiap halaman (sebelum ada output apa pun) supaya
// halaman tahu status login siswa/admin lewat $_SESSION, tanpa perlu
// query database di setiap halaman.
session_name('edmgmt_session');
session_start();

$isLoggedIn = !empty($_SESSION['student_id']);
$studentName = $_SESSION['student_name'] ?? '';
$isAdminSession = !empty($_SESSION['is_admin']);
