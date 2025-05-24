<?php
// Datos de conexión extraídos del URL PostgreSQL
$host     = "switchback.proxy.rlwy.net";
$port     = "30265";
$dbname   = "railway";
$user     = "postgres";
$password = "VVFSjknzEbQwXjeSxjpafCliGeTAIvgA";

// Construcción del string de conexión
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Intentar la conexión
$conn = pg_connect($conn_string);

// Resultado
if ($conn) {
    echo "✅ Conexión exitosa a PostgreSQL";
} else {
    echo "❌ Error al conectar a PostgreSQL";
}
?>
