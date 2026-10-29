<?php
require_once __DIR__.'/includes/layout.php';
if (!empty($_SESSION['web_auth'])) redirect('panel.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $correct = isset($_SESSION['captcha_answer']) && filter_var($_POST['captcha'] ?? '', FILTER_VALIDATE_INT) === $_SESSION['captcha_answer'];
    $credentials = hash_equals(APP_USER,(string)($_POST['usuario']??'')) && hash_equals(APP_PASSWORD,(string)($_POST['contrasena']??''));
    if ($credentials && $correct) { session_regenerate_id(true); $_SESSION['web_auth']=true; unset($_SESSION['captcha_answer'],$_SESSION['captcha_question']); redirect('panel.php'); }
    $error='Usuario, contraseña o CAPTCHA incorrectos.';
    unset($_SESSION['captcha_answer'],$_SESSION['captcha_question']);
}
if (!isset($_SESSION['captcha_answer'])) {
    $a=random_int(1,12);$b=random_int(1,12);
    $_SESSION['captcha_question']="$a + $b"; $_SESSION['captcha_answer']=$a+$b;
}
page_header('Iniciar sesión'); ?>
<section class="card formbox"><div class="badge">Acceso seguro</div><h1>Iniciar sesión</h1><p class="muted">Ingresa tus datos y resuelve el CAPTCHA para continuar.</p>
<?php if($error): ?><div class="alert"><?=e($error)?></div><?php endif; ?>
<form method="post"><label for="u">Usuario</label><input id="u" name="usuario" required autocomplete="username"><label for="p">Contraseña</label><input id="p" name="contrasena" type="password" required autocomplete="current-password"><label for="c">CAPTCHA: ¿cuánto es <?=e($_SESSION['captcha_question'])?>?</label><input id="c" name="captcha" type="number" required><button type="submit">Entrar al sistema</button></form></section>
<?php page_footer(); ?>
