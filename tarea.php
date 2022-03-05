<?php
session_start();

if (isset($_GET['id'])) {
    $_SESSION['diaMostrado'] = $_GET['id'];
}
$id = $_SESSION['diaMostrado'];
if (!isset($_SESSION['tareas'][$id])) {
    $_SESSION['tareas'][$id] = array();
}
if (isset($_POST['add'])) {
    array_push($_SESSION['tareas'][$id], $_POST['tarea']);
}
if (isset($_POST['delete'])) {
    foreach ($_SESSION['tareas'][$id] as $key => $value) {
        if (isset($_POST[$key])) {
            unset($_SESSION['tareas'][$id][$key]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="calendario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea</title>
</head>

<body>
    <a href="index.php">Volver al calendario</a>
    <h1>Tareas del día <?php echo $id ?></h1>
    <form action="tarea.php" method="POST">
        <?php
        foreach ($_SESSION['tareas'][$id] as $key => $value) {
            echo "<input type=\"checkbox\" value=\"$value\" name=\"$key\" id=\"$key\">$value<br>";
        }
        ?>
        <input type="submit" name="delete" value="Eliminar">
    </form>
    <h3>Añadir tarea</h3>
    <form action="tarea.php" method="POST">
        <input type="text" name="tarea" placeholder="Tarea">
        <input type="submit" name="add" value="Añadir">
    </form>
</body>

</html>