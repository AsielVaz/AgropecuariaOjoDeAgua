<?php
require_once __DIR__ . '/session.php';
$_SESSION = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#183c2d">
    <title>Acceso administrativo · AOA</title>
    <link rel="icon" type="image/x-icon" href="../logo.png">
    <link rel="stylesheet" href="css/style.css?v=20260903-3">
</head>
<body class="berry-login-page">
    <main class="login-shell">
        <section class="login-story" aria-label="Agropecuaria Ojo de Agua">
            <div class="login-story__overlay"></div>
            <div class="login-story__top">
                <span class="login-story__seal"><img src="../logo.png" alt="Logotipo de Agropecuaria Ojo de Agua"></span>
                <span class="login-story__brand"><strong>AOA</strong><small>Berry Fields</small></span>
            </div>
            <div class="login-story__copy">
                <span class="login-story__eyebrow"><i></i> Gestión desde la raíz</span>
                <h2>Control claro para una cosecha extraordinaria.</h2>
                <p>Proveedores, facturas y pagos reunidos en un espacio inspirado en nuestra huerta.</p>
            </div>
            <div class="login-story__status">
                <span><i></i> Huerta conectada</span>
                <span>Ojo de Agua · México</span>
            </div>
        </section>

        <section class="login-access">
            <div class="login-access__inner">
                <div class="login-mobile-brand">
                    <img src="../logo.png" alt="AOA">
                    <span><strong>AOA</strong><small>Berry Fields</small></span>
                </div>

                <div class="login-heading">
                    <span class="login-heading__kicker">Panel administrativo</span>
                    <h1>Bienvenido a la huerta</h1>
                    <p>Ingresa tus datos para continuar con la gestión.</p>
                </div>

                <form id="formularioUsuario" class="login-form">
                    <div class="form-field">
                        <label for="usuario">Correo de usuario</label>
                        <div class="input-shell">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4zM4 8l8 6 8-6" /></svg>
                            <input id="usuario" type="email" placeholder="nombre@empresa.com" required name="usuario" autocomplete="username">
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="contrasena">Contraseña</label>
                        <div class="input-shell">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 10h12v10H6zM9 10V7a3 3 0 0 1 6 0v3M12 14v2" /></svg>
                            <input id="contrasena" type="password" placeholder="Ingresa tu contraseña" required name="contrasena" autocomplete="current-password">
                        </div>
                    </div>

                    <p id="loginFeedback" class="login-feedback" role="status" aria-live="polite"></p>

                    <button class="login-submit" type="submit">
                        <span>Entrar al sistema</span>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M14 7l5 5-5 5" /></svg>
                    </button>
                </form>

                <div class="login-access__footer">
                    <span class="berry-mark"><i></i><i></i><i></i></span>
                    Acceso seguro · Agropecuaria Ojo de Agua
                </div>
            </div>
        </section>
    </main>

    <script>
        const formUsuario = document.getElementById('formularioUsuario');
        const feedback = document.getElementById('loginFeedback');
        const submitButton = formUsuario.querySelector('button[type="submit"]');

        formUsuario.addEventListener('submit', async function (event) {
            event.preventDefault();
            feedback.className = 'login-feedback';
            feedback.textContent = 'Validando acceso…';
            submitButton.disabled = true;

            const datosCuenta = new FormData(formUsuario);
            datosCuenta.append('accion', 'inicio');

            try {
                const respuesta = await fetch('api/apiUsuarios.php', {
                    method: 'POST',
                    body: datosCuenta
                });
                const data = await respuesta.json();

                if (data.estatus === 'exito') {
                    feedback.className = 'login-feedback is-success';
                    feedback.textContent = data.mensaje || 'Acceso correcto. Abriendo el panel…';
                    window.setTimeout(function () {
                        window.location.href = 'index.php';
                    }, 700);
                    return;
                }

                feedback.className = 'login-feedback is-error';
                feedback.textContent = data.mensaje || 'No fue posible iniciar sesión. Revisa tus datos.';
            } catch (error) {
                feedback.className = 'login-feedback is-error';
                feedback.textContent = 'No fue posible conectar con el sistema. Intenta nuevamente.';
            } finally {
                submitButton.disabled = false;
            }
        });
    </script>
</body>
</html>
