<?php

require_once __DIR__ . '/session.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php', true, 302);
    exit;
}
