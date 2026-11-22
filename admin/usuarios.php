<?php

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login_admin.php");
    exit();
}

require_once("../config/database.php");

/* BUSCADOR */

$busqueda = $_GET['buscar'] ?? '';

$sql = "

SELECT *

FROM usuarios

WHERE nombre LIKE ?
OR email LIKE ?

ORDER BY id DESC

";

$like = "%".$busqueda."%";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $like,
    $like
);

$stmt->execute();

$usuarios =
$stmt->get_result();

/* TOTAL USUARIOS */

$totalUsuarios =
$conn
->query("
SELECT COUNT(*) total
FROM usuarios
")
->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="es">

<head>


<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>

MindEdu Admin - Usuarios

</title>

<link
rel="stylesheet"
href="../css/admin.css"
>

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>

            MindEdu Admin

        </h2>

        <nav>

            <ul>

                <li>

                    <a href="dashboard_admin.php">

                        Dashboard

                    </a>

                </li>

                <li>

                    <a
                        href="usuarios.php"
                        class="active"
                    >

                        Usuarios

                    </a>

                </li>

                <li>

                    <a href="subir_recurso.php">

                        Recursos

                    </a>

                </li>

                <li>

                    <a href="subir_ejercicio.php">

                        Ejercicios

                    </a>

                </li>

            </ul>

        </nav>

        <div class="support">

            <p>

                Gestión administrativa
                de usuarios, recursos
                y ejercicios de MindEdu.

            </p>

        </div>

        <div class="user">

            <p>

                <strong>

                    <?php
                    echo $_SESSION['admin_nombre'];
                    ?>

                </strong>

            </p>

            <small>

                Administrador

            </small>

            <p>

                <a
                    href="../config/logout_admin.php"
                >

                    Cerrar sesión

                </a>

            </p>

        </div>

    </aside>

    <!-- CONTENIDO -->

    <main class="admin-main">

        <div class="page-header">

            <h1>

                Administración de usuarios

            </h1>

            <p>

                Gestiona los usuarios registrados
                en la plataforma.

            </p>

        </div>
<?php if(isset($_GET['eliminado'])): ?>

<div class="success-message">

    Usuario eliminado correctamente.

</div>

<?php endif; ?>
        <!-- ESTADÍSTICA -->

        <div class="stats-card">

            <h3>

                Total de usuarios

            </h3>

            <span>

                <?php echo $totalUsuarios; ?>

            </span>

        </div>

        <!-- BUSCADOR -->

        <form
            method="GET"
            class="search-form"
        >

            <input

                type="text"

                name="buscar"

                value="<?php echo htmlspecialchars($busqueda); ?>"

                placeholder="Buscar usuario por nombre o correo"

            >

            <button
                type="submit"
            >

                Buscar

            </button>

        </form>

        <!-- TARJETAS DE USUARIOS -->

        <div class="users-grid">

        <?php if($usuarios->num_rows > 0): ?>

            <?php while($usuario = $usuarios->fetch_assoc()): ?>

                <div class="user-card">

                    <div class="user-info">

                        <h3>

                            👤

                            <?php
                            echo htmlspecialchars(
                                $usuario['nombre']
                            );
                            ?>

                        </h3>

                        <p>

                            📧

                            <?php
                            echo htmlspecialchars(
                                $usuario['email']
                            );
                            ?>

                        </p>

                        <p>

                            🎂

                            <?php
                            echo $usuario['edad'];
                            ?>

                            años

                        </p>

                        <p>

                            ID:

                            <?php
                            echo $usuario['id'];
                            ?>

                        </p>

                    </div>

                    <div class="user-actions">

                        <a

                            href="editar_usuario.php?id=<?php echo $usuario['id']; ?>"

                            class="edit-btn"

                        >

                            Editar

                        </a>

                        <a

                            href="../config/eliminar_usuario.php?id=<?php echo $usuario['id']; ?>"

                            class="delete-btn"

                            onclick="return confirm('¿Deseas eliminar este usuario?');"

                        >

                            Eliminar

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="no-users">

                No se encontraron usuarios.

            </div>

        <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>