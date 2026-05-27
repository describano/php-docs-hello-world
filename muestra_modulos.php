<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');
$dbName = "prueba";         
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

if (!$dbHost || !$dbUser || $dbPass === false) {
    throw new \RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

// DSN con charset utf8mb4
$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

try {
    $options = [
        // Excepciones en errores
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Fetch como array asociativo
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Desactivar emulación de prepares
        PDO::ATTR_EMULATE_PREPARES   => false,

        // Asegurar la conexión TLS hacia Azure Database for MySQL
        PDO::MYSQL_ATTR_SSL_CA        => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem',
        // Desactivamos la validación del certificado SSL
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    // Crear la conexión PDO
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    // Consulta a la tabla modulos_asir
    $stmt = $pdo->query('SELECT * FROM modulos_asir;');
    $modulos = $stmt->fetchAll();

    echo "Conectado correctamente.<br><br>";

    echo "<h2>Contenido de la tabla modulos_asir</h2>";

    foreach ($modulos as $modulo) {
        echo "ID: " . htmlspecialchars($modulo['id']) . "<br>";
        echo "Nombre: " . htmlspecialchars($modulo['nombre']) . "<br>";
        echo "Horas: " . htmlspecialchars($modulo['horas']) . "<br>";
        echo "Curso: " . htmlspecialchars($modulo['curso']) . "<br>";
        echo "Profesor responsable: " . htmlspecialchars($modulo['profesor_responsable']) . "<br>";
        echo "<hr>";
    }

} catch (PDOException $e) {
    error_log('Error de conexión PDO: ' . $e->getMessage());
    echo "Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
