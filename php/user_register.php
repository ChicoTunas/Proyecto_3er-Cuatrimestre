<?php

$user = "root";
$pass = "";
$host = "localhost";
$datab = "esrs";
$port = 3308;

$conn = new mysqli($host, $user, $pass, $datab, $port);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    $password1 = isset($_POST["password1"]) ? $_POST["password1"] : "";
    $password2 = isset($_POST["password2"]) ? $_POST["password2"] : "";

    if ($password1 !== $password2) {

    } else {
        $user_name = $_POST['user_name'];
        $user_lastname = $_POST['user_lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = password_hash($_POST['password1'], PASSWORD_BCRYPT);
        $user_type = $_POST['user_type'];

        $check_email_query = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email_query->bind_param("s", $email);
        $check_email_query->execute();
        $check_email_query->store_result();
        
        if ($check_email_query->num_rows > 0) {
			echo '
			<script>
			alert("El correo ingresado ya se encuentra registrado")
			window.location = Inicio de Sesion.html;
			</script>
			';
            $check_email_query->close();
        } else {

            $check_email_query->close();

            $insert_user_query = $conn->prepare("INSERT INTO users(user_name, user_lastname, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?, ?)");

            $insert_user_query->bind_param("ssssss", $user_name, $user_lastname, $email, $phone, $password, $user_type);

            if ($insert_user_query->execute()) {
                echo "Registro insertado con éxito";
            } else {
                echo "Error al insertar el registro: " . $insert_user_query->error;
            }

            $insert_user_query->close();
        }
    }
}

$conn->close();

?>


