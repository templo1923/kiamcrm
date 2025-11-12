<?php
// === Set timezone
date_default_timezone_set('Asia/Calcutta');

// === Load database connection
require_once __DIR__ . '/include/conn.php';

// === Folder where backups will be stored
$backupDir = __DIR__ . '/backup_db/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// === Create filename with today's date
$filename = $db . '_backup_' . date('Y-m-d') . '.sql';
$filepath = $backupDir . $filename;

// === Check last backup file's date
$existingBackups = glob($backupDir . '*.sql');
$shouldBackup = true;
$latestFile = null;
$latestTime = 0;

// Find latest backup
foreach ($existingBackups as $file) {
    $fileTime = filemtime($file);
    if ($fileTime > $latestTime) {
        $latestTime = $fileTime;
        $latestFile = $file;
    }
}

// Skip if last backup is within 7 days
if ($latestFile && time() - $latestTime < 7 * 24 * 60 * 60) {
    $shouldBackup = false;
}

if ($shouldBackup) {
    // === Run mysqldump command ===
    $command = "mysqldump --user={$username} --password=\"{$password}\" --host={$host} {$db} > \"{$filepath}\"";
    exec($command, $output, $result);

    if ($result === 0) {
        echo "✅ Backup created: {$filename}\n";

        // Delete older backups except the latest one
        foreach ($existingBackups as $file) {
            if ($file !== $latestFile) {
                unlink($file);
            }
        }

    } else {
        echo "❌ Backup failed. Please check permissions or mysqldump path.\n";
    }
} else {
    echo "⏳ Recent backup already exists. No need to create a new one.\n";
}
?>
