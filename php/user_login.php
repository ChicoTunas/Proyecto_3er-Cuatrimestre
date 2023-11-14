<?php

$user = "root";
$pass = "";
$host = "localhost";
$datab = "esrs";
$port = 3308;

$conn = new mysqli($host, $user, $pass, $datab, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email_login']) ? $_POST['email_login'] : "";
    $password = isset($_POST['password_login']) ? $_POST['password_login'] : "";

    $sql = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
	
    $sql->bind_param("s", $email);


    $sql->execute();

    $sql->bind_result($db_email, $db_password);

    $sql->fetch();

    if ($db_email && password_verify($password, $db_password)) {
        echo "Inicio de sesión exitoso";
    
    } else {
        echo "Correo electrónico o contraseña incorrectos";

    }

    $sql->close();
}

$conn->close();

?>

