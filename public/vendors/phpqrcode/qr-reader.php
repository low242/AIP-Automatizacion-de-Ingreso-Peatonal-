<?php
// vendors/phpqrcode/qr-reader.php
// Wrapper para la librería PHP QR Code Reader (https://github.com/khanamiryan/php-qrcode-detector-decoder)
// Debes descargar la librería real y colocar los archivos aquí para producción.
// Este archivo es solo un stub de ejemplo.
class QRReader {
    public static function decode($file) {
        // Aquí deberías usar la librería real para decodificar el QR
        // Ejemplo: $qrcode = new QrReader($file); return $qrcode->text();
        return 'Simulado: ' . basename($file);
    }
}
?>
