<?php
/**
 * =====================================================================
 * CLOUD MARKET - DATABASE RESET ENGINE
 * =====================================================================
 * Skrip utilitas untuk mereset seluruh database Cloud Market ke kondisi awal
 * yang bersih, terstruktur, dan terisi data demo siap pakai.
 *
 * Cara Menjalankan:
 * 1. Lewat Terminal / CLI : php database/reset/reset.php
 * 2. Lewat Windows Batch  : Klik 2x file database/reset/reset.bat
 * 3. Lewat Web Browser   : Buka http://localhost/cloud-market/database/reset/reset.php
 * =====================================================================
 */

// Pastikan jalur root aplikasi benar
$rootDir = dirname(__DIR__, 2);
$configFile = $rootDir . '/config/config.php';

if (!file_exists($configFile)) {
    die("Error: File konfigurasi tidak ditemukan di $configFile\n");
}

$config = require $configFile;
$dbConfig = $config['db'];

// Deteksi mode eksekusi: CLI atau Browser
$isCli = (PHP_SAPI === 'cli');

function connectDb(array $c): PDO {
    $dsn = "mysql:host={$c['host']};port={$c['port']};charset={$c['charset']}";
    $pdo = new PDO($dsn, $c['user'], $c['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // Pastikan database ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$c['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$c['name']}`");
    return $pdo;
}

function executeSqlFile(PDO $pdo, string $filePath): array {
    if (!file_exists($filePath)) {
        throw new RuntimeException("File SQL tidak ditemukan: $filePath");
    }

    $sql = file_get_contents($filePath);
    // Hapus komentar multi-line dan komentar satu baris
    $lines = explode("\n", $sql);
    $cleanSql = '';
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
            continue;
        }
        $cleanSql .= $line . "\n";
    }

    // Pisahkan query berdasarkan delimiter ';'
    $queries = array_filter(array_map('trim', explode(';', $cleanSql)));
    $executed = 0;

    foreach ($queries as $query) {
        if (!empty($query)) {
            $pdo->exec($query);
            $executed++;
        }
    }

    return ['queries' => $executed];
}

function runDatabaseReset(array $dbConfig, string $rootDir): array {
    $startTime = microtime(true);
    $pdo = connectDb($dbConfig);

    $schemaFile = __DIR__ . '/schema.sql';
    $seedFile   = __DIR__ . '/seed.sql';

    // 1. Eksekusi Schema
    $schemaResult = executeSqlFile($pdo, $schemaFile);

    // 2. Eksekusi Seed Data
    $seedResult = executeSqlFile($pdo, $seedFile);

    // 3. Cek Status Seluruh Tabel
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $tableCounts = [];
    foreach ($tables as $t) {
        $cnt = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        $tableCounts[$t] = (int)$cnt;
    }

    $elapsed = round(microtime(true) - $startTime, 3);

    return [
        'success'      => true,
        'elapsed'      => $elapsed,
        'schemaQueries'=> $schemaResult['queries'],
        'seedQueries'  => $seedResult['queries'],
        'tableCounts'  => $tableCounts,
    ];
}

// =====================================================================
// PENANGANAN EKSEKUSI CLI
// =====================================================================
if ($isCli) {
    echo "\n=======================================================\n";
    echo "       🚀 CLOUD MARKET - DATABASE RESET ENGINE         \n";
    echo "=======================================================\n";
    echo "Database: {$dbConfig['name']} @ {$dbConfig['host']}:{$dbConfig['port']}\n";
    echo "Memulai proses reset...\n\n";

    try {
        $result = runDatabaseReset($dbConfig, $rootDir);

        echo "✅ [1/2] Struktur Skema (schema.sql) berhasil diterapkan ({$result['schemaQueries']} query).\n";
        echo "✅ [2/2] Data Awal Bersih (seed.sql) berhasil dimasukkan ({$result['seedQueries']} query).\n\n";

        echo "📊 STATUS TABEL SETELAH RESET:\n";
        echo "-------------------------------------------------------\n";
        foreach ($result['tableCounts'] as $tbl => $cnt) {
            printf("  - %-20s : %d baris\n", $tbl, $cnt);
        }
        echo "-------------------------------------------------------\n";
        echo "⏱️  Selesai dalam {$result['elapsed']} detik.\n\n";

        echo "🔑 AKUN DEMO SIAP DIGUNAKAN:\n";
        echo "  1. Administrator -> Username: admin   | Password: admin123\n";
        echo "  2. Penjual       -> Username: penjual | Password: penjual123 (Toko: Berkah Second Store)\n";
        echo "  3. Pembeli       -> Username: pembeli | Password: pembeli123\n";
        echo "  4. Penjual 2     -> Username: putra   | Password: penjual123 (Toko: Mewing Vintage Store)\n\n";
        echo "=======================================================\n";
        echo " Database berhasil direset 100%! Selamat beraktivitas.\n";
        echo "=======================================================\n\n";
    } catch (Throwable $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n\n";
        exit(1);
    }
    exit(0);
}

// =====================================================================
// PENANGANAN EKSEKUSI BROWSER / WEB
// =====================================================================
$message = null;
$error = null;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_reset'])) {
    try {
        $result = runDatabaseReset($dbConfig, $rootDir);
        $message = "Database berhasil direset 100% dalam {$result['elapsed']} detik!";
    } catch (Throwable $e) {
        $error = "Gagal mereset database: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Database - Cloud Market</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;800;900&family=Onest:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1117;
            --card-bg: #181c27;
            --border: #2a3142;
            --text-main: #f3f4f6;
            --text-sub: #9ca3af;
            --accent: #2563eb;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --success: #16a34a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Onest', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .container {
            width: 100%;
            max-width: 680px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .header {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }
        .subtitle {
            color: var(--text-sub);
            font-size: 14px;
            margin-top: 4px;
        }
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.5;
        }
        .alert-warning {
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.3);
            color: #facc15;
        }
        .alert-success {
            background: rgba(22, 163, 74, 0.15);
            border: 1px solid rgba(22, 163, 74, 0.4);
            color: #4ade80;
        }
        .alert-danger {
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.4);
            color: #f87171;
        }
        .table-stat {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 13px;
        }
        .table-stat th, .table-stat td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        .table-stat th { color: var(--text-sub); font-weight: 600; }
        .table-stat td:last-child { text-align: right; font-weight: 700; }
        .accounts-box {
            background: #11141e;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 13px;
        }
        .accounts-box h4 {
            color: var(--text-main);
            margin-bottom: 8px;
            font-size: 14px;
        }
        .accounts-box ul {
            list-style: none;
            line-height: 1.8;
            color: var(--text-sub);
        }
        .accounts-box code {
            background: #1e2433;
            color: #60a5fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            font-family: 'Onest', sans-serif;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover {
            background: var(--danger-hover);
        }
        .btn-secondary {
            background: #2a3142;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #374151;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <svg viewBox="0 0 198 130" width="28" height="18" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M50 108h92a32 32 0 0 0 6-63A44 44 0 0 0 64 40a34 34 0 0 0-14 68z"/>
                </svg>
                CLOUD MARKET
            </div>
            <div class="title">Pengaturan & Reset Basis Data</div>
            <div class="subtitle">Database: <code><?= htmlspecialchars($dbConfig['name']) ?></code></div>
        </div>

        <?php if ($message && $result): ?>
            <div class="alert alert-success">
                <strong>Berhasil!</strong> <?= htmlspecialchars($message) ?><br>
                Seluruh skema tabel telah diperbarui dan data awal bersih telah diinjeksikan.
            </div>

            <div class="accounts-box">
                <h4>Status Data Tabel:</h4>
                <table class="table-stat">
                    <thead>
                        <tr><th>Nama Tabel</th><th>Jumlah Baris</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['tableCounts'] as $tbl => $cnt): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($tbl) ?></code></td>
                                <td><?= number_format($cnt) ?> baris</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="accounts-box">
                <h4>Akun Default Siap Pakai:</h4>
                <ul>
                    <li>👑 <strong>Admin</strong>: <code>admin</code> / <code>admin123</code></li>
                    <li>🏪 <strong>Penjual</strong>: <code>penjual</code> / <code>penjual123</code> (Toko: Berkah Second Store)</li>
                    <li>🛒 <strong>Pembeli</strong>: <code>pembeli</code> / <code>pembeli123</code></li>
                    <li>⭐ <strong>Penjual 2</strong>: <code>putra</code> / <code>penjual123</code> (Toko: Mewing Vintage Store)</li>
                </ul>
            </div>

            <div class="btn-group">
                <a href="../../public/" class="btn btn-primary">Buka Halaman Utama</a>
                <a href="../../public/login" class="btn btn-secondary">Menuju Halaman Login</a>
            </div>

        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <strong>Peringatan Error:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="alert alert-warning">
                <strong>⚠️ PERHATIAN PENTING:</strong><br>
                Tindakan ini akan mengosongkan dan membangun ulang seluruh tabel data transaksi, produk, chat nego, dan akun pengguna. Data akan digantikan dengan data sampel awal yang bersih. Data 83.000+ wilayah kode pos tetap dipertahankan.
            </div>

            <div class="accounts-box">
                <h4>Yang akan dilakukan sistem:</h4>
                <ul>
                    <li>1. Menerapkan skema relasional terkini dari <code>database/reset/schema.sql</code>.</li>
                    <li>2. Membangun tabel terpadu: <code>users</code>, <code>seller_profiles</code>, <code>kategori</code>, <code>barang</code>, <code>transaksi</code>, <code>detailtransaksi</code>, <code>pesan</code>.</li>
                    <li>3. Mengisi data sampel produk, toko terverifikasi, dan akun default dari <code>database/reset/seed.sql</code>.</li>
                </ul>
            </div>

            <form method="POST" onsubmit="return confirm('Apakah Anda benar-benar yakin ingin mereset seluruh database Cloud Market? Semua data uji coba akan digantikan dengan data awal.');">
                <input type="hidden" name="konfirmasi_reset" value="1">
                <div class="btn-group">
                    <a href="../../public/" class="btn btn-secondary">Batal / Kembali</a>
                    <button type="submit" class="btn btn-danger">Ya, Reset Database Sekarang</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

