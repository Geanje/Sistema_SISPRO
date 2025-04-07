<?php
$folder = '../files/txt';
$zip_file = 'TXT_Soluciones.zip';

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folder),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$zip = new ZipArchive();
$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $file_path = $file->getRealPath();
       $relative_path = basename($file_path);
        $zip->addFile($file_path, $relative_path);
    }
}

$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($zip_file) . '"');
header('Content-Length: ' . filesize($zip_file));

readfile($zip_file);
unlink($zip_file);

// Eliminar los archivos del directorio 'txt'
foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        unlink($file->getPathname());
    }
}
?>