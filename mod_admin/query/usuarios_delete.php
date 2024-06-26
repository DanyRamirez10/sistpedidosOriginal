<?php
@session_start();
include_once '../../lib/config.php';
include_once '../../lib/functions.php';
include_once '../../conexion/conectar.php';

$id = $_POST['id'];

// Verificar si el usuario actual es superusuario
$sql = "SELECT superusuario FROM usuario WHERE codUsu = '".$id."'";
$result = $conector->query($sql);
$row = $result->fetch_assoc();

if ($row['superusuario'] == 0) {
    // Si el usuario es superusuario, permitir operaciones especiales
    if (empty($_POST["id"])) {
        $errors[] = "Seleccione el Registro";
    } else {
        $sql = "SELECT * FROM usuario_login WHERE codUsu='" . $id . "'";
        $query = $conector->query($sql);
        $exit_logeo = $query->num_rows;
        
        if ($exit_logeo > 0) {
            $errors[] = "El usuario ya tiene registros almacenados";
        } else {
            // Eliminar el usuario si no tiene registros almacenados
            $sql = "DELETE FROM usuario WHERE codUsu='" . $id . "'";
            $delete = $conector->query($sql);
            if ($delete) {
                $messages[] = "Registro Eliminado correctamente";
            } else {
                $errors[] = "No se eliminó el registro";
            }
        }
    }
} else {
    // Si el usuario no es superusuario, mostrar un error
    $errors[] = "No tienes permisos para realizar esta acción.";
}

if (isset($errors)) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<b>Error</b>! ';
    foreach ($errors as $error) {
        echo $error;
    }
    echo '</div>';
}

if (isset($messages)) {
    echo '<div class="alert alert-success" role="alert">';
    echo '<b>Bien</b>! ';
    foreach ($messages as $sms) {
        echo $sms;
    }
    echo '</div>';
}
?>
