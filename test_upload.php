<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Storage;

// Test file storage
echo "Testing file storage...\n";

// Test creating a test file
$testContent = "This is a test file for upload verification.\n";
$testFileName = 'test_'.time().'.txt';
$testFilePath = 'publications/'.$testFileName;

try {
    // Store file
    $stored = Storage::disk('public')->put($testFilePath, $testContent);
    echo 'File stored: '.($stored ? 'SUCCESS' : 'FAILED')."\n";

    // Check if file exists
    $exists = Storage::disk('public')->exists($testFilePath);
    echo 'File exists: '.($exists ? 'YES' : 'NO')."\n";

    // Get file size
    $size = Storage::disk('public')->size($testFilePath);
    echo 'File size: '.$size." bytes\n";

    // Get full path
    $fullPath = storage_path('app/public/'.$testFilePath);
    echo 'Full path: '.$fullPath."\n";

    // Check if file exists on disk
    $fileExists = file_exists($fullPath);
    echo 'File exists on disk: '.($fileExists ? 'YES' : 'NO')."\n";

    // List files in publications directory
    $files = Storage::disk('public')->files('publications');
    echo 'Files in publications directory: '.count($files)."\n";
    foreach ($files as $file) {
        echo '  - '.$file."\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}

echo "\nTest completed.\n";
