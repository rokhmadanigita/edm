<?php
$url = 'https://script.google.com/macros/s/AKfycbyUIA95d4kY5zS6Yoy1asGIhgI9rH8H-87kq-z4KNg7EzIge3DhkzMIWmm8Yo3MDlz7Rw/exec';
$data = json_encode([
    'siswa_id' => 123,
    'nama_siswa' => 'Budi',
    'sekolah' => 'SMK Contoh',
    'kelas' => 'XII-A',
    'tanggal' => '2026-07-26',
    'jam' => '08:00:00',
    'jenis' => 'masuk',
    'catatan' => '',
    'photo' => ''
]);
$options = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nContent-Length: " . strlen($data) . "\r\n",
        'content' => $data,
        'ignore_errors' => true,
    ],
];
$context = stream_context_create($options);
$res = file_get_contents($url, false, $context);
echo $res;
