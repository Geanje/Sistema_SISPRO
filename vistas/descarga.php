<?php
// Configuraci車n de la conexi車n FTP
$ftp_server = 'sistemasubditex.com';
$ftp_username = 'sistemas';
$ftp_password = 'g1;)F3DMSamh10';
$ftp_folder = 'public_html/constructor/files/txt';

// Conexi車n FTP
$conn_id = ftp_connect($ftp_server);
$login = ftp_login($conn_id, $ftp_username, $ftp_password);

if ($conn_id && $login) {
    // Cambiar al directorio correcto
    ftp_chdir($conn_id, $ftp_folder);

    // Obtener lista de archivos en el directorio
    $files = ftp_nlist($conn_id, '.');
var_dump($files);
    // Descargar cada archivo
    foreach ($files as $file) {
        // Ruta local para guardar los archivos descargados
       // $local_file = 'C:/Users/SISTEMA/Documents/' . basename($file);
        $local_file = 'F:/#6_Rafael/SFS_v1.3.3/sunat_archivos/sfs/DATA' . basename($file);

        // Descargar archivo
        if (ftp_get($conn_id, $local_file, $file, FTP_BINARY)) {
            echo 'Archivo descargado: ' . $file . '<br>';
        } else {
            echo 'Error al descargar el archivo: ' . $file . '<br>';
        }
    }

    // Cerrar la conexi車n FTP
    ftp_close($conn_id);
} else {
    echo 'Error al conectar al servidor FTP';
}
?>
