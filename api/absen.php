<?php
require_once __DIR__ . '/db.php';

function pushAttendanceToGoogleSheet(array $attendance): void {
    if (!defined('GOOGLE_SHEET_ENABLED') || !GOOGLE_SHEET_ENABLED) {
        return;
    }

    if (!defined('GOOGLE_SHEET_WEBHOOK_URL') || empty(GOOGLE_SHEET_WEBHOOK_URL)) {
        return;
    }

    $payload = json_encode($attendance, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if (function_exists('curl_init')) {
        $ch = curl_init(GOOGLE_SHEET_WEBHOOK_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            error_log('Google Sheet attendance push failed. HTTP ' . $httpCode . ' Response: ' . (string) $response);
        }
        return;
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $payload,
            'ignore_errors' => true,
            'timeout' => 25,
        ],
    ]);

    @file_get_contents(GOOGLE_SHEET_WEBHOOK_URL, false, $context);
}

$action = $_GET['action'] ?? '';
$body = input();

switch ($action) {

  case 'add':
    $sid = requireStudent();
    $stmt = $pdo->prepare('SELECT * FROM siswa WHERE id = ?');
    $stmt->execute([$sid]);
    $s = $stmt->fetch();
    if (!$s) respond(['error' => 'Akun tidak ditemukan.'], 404);

    $jenis = (($body['type'] ?? '') === 'pulang') ? 'pulang' : 'masuk';
    $catatan = trim($body['note'] ?? '');
    $photo = isset($body['photo']) && is_string($body['photo']) ? trim($body['photo']) : '';

    if ($jenis === 'masuk' && !$photo) {
        respond(['error' => 'Ambil foto dari kamera dulu sebelum absen masuk.'], 400);
    }

    $photoFilename = null;
    if ($photo && strpos($photo, 'data:image') === 0) {
        $photoFilename = 'att_' . $sid . '_' . time() . '.jpg';
        $photoPath = __DIR__ . '/../assets/img/' . $photoFilename;
        
        if (preg_match('/^data:image\/(\w+);base64,(.*)$/', $photo, $m)) {
            $imageData = base64_decode($m[2], true);
            if ($imageData !== false) {
                file_put_contents($photoPath, $imageData);
            }
        }
    }

    $stmt = $pdo->prepare('INSERT INTO absensi (siswa_id, nama_siswa, sekolah, kelas, tanggal, jam, jenis, catatan, foto) VALUES (?, ?, ?, ?, CURDATE(), CURTIME(), ?, ?, ?)');
    $stmt->execute([$sid, $s['nama'], $s['sekolah'], $s['kelas'], $jenis, $catatan, $photoFilename]);

    $attendanceData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'siswa_id' => $sid,
        'nama_siswa' => $s['nama'],
        'sekolah' => $s['sekolah'],
        'kelas' => $s['kelas'],
        'tanggal' => date('Y-m-d'),
        'jam' => date('H:i:s'),
        'jenis' => $jenis,
        'catatan' => $catatan,
        'photo' => $photoFilename,
    ];
    pushAttendanceToGoogleSheet($attendanceData);

    respond(['ok' => true]);
    break;

  case 'today':
    $sid = requireStudent();
    $stmt = $pdo->prepare("SELECT jenis AS type, TIME_FORMAT(jam, '%H:%i') AS time, catatan AS note FROM absensi WHERE siswa_id = ? AND tanggal = CURDATE() ORDER BY jam DESC");
    $stmt->execute([$sid]);
    respond(['rows' => $stmt->fetchAll()]);
    break;

  case 'mine':
    // dipakai untuk menghitung "pangkat kehadiran" siswa yang sedang login
    $sid = requireStudent();
    $stmt = $pdo->prepare("SELECT DISTINCT tanggal FROM absensi WHERE siswa_id = ? AND jenis = 'masuk'");
    $stmt->execute([$sid]);
    respond(['dates' => $stmt->fetchAll(PDO::FETCH_COLUMN)]);
    break;

  case 'list':
    // rekap untuk admin, dengan filter opsional
    requireAdmin();
    $nama = trim($_GET['name'] ?? '');
    $tanggal = trim($_GET['date'] ?? '');
    $jenis = trim($_GET['status'] ?? '');

    $sql = "SELECT id, nama_siswa AS student_name, sekolah AS school, kelas, tanggal AS att_date, TIME_FORMAT(jam, '%H:%i') AS att_time, jenis AS type, catatan AS note, foto AS photo FROM absensi WHERE 1=1";
    $params = [];
    if ($nama)    { $sql .= ' AND nama_siswa LIKE ?'; $params[] = "%$nama%"; }
    if ($tanggal) { $sql .= ' AND tanggal = ?'; $params[] = $tanggal; }
    if ($jenis)   { $sql .= ' AND jenis = ?'; $params[] = $jenis; }
    $sql .= ' ORDER BY tanggal DESC, jam DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    respond(['rows' => $stmt->fetchAll()]);
    break;

  case 'delete':
    requireAdmin();
    $id = (int) ($body['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM absensi WHERE id = ?');
    $stmt->execute([$id]);
    respond(['ok' => true]);
    break;

  default:
    respond(['error' => 'Aksi tidak dikenal.'], 400);
}
