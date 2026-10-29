<?php
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/store.php';
require_web_login();
$premios=read_data('premios');
page_header('Premios de la rifa');
?>
<section class="hero prizes-hero"><div class="badge">Rifa UPLA · premios</div><h1>Premios que puedes ganar</h1><p class="muted">Conoce los premios de nuestra rifa. Cada número tiene un valor de <strong>$2.000</strong>.</p><a class="button" href="rifa.php">Elegir mi número →</a></section>
<section class="prize-grid" aria-label="Premios de la rifa">
<?php foreach ($premios as $p): ?>
<article class="prize-card"><div class="prize-image"><img src="<?=e($p['imagen']??'')?>?v=20261029-fix2" alt="<?=e($p['nombre']??'Premio')?>" loading="lazy"></div><div class="prize-info"><div class="prize-top"><span class="prize-tag">Premio <?=e($p['id']??'')?></span><span class="prize-status"><?=e($p['estado']??'Disponible')?></span></div><h2><?=e($p['nombre']??'Premio')?></h2><p class="muted"><?=e($p['descripcion']??'')?></p></div></article>
<?php endforeach; ?>
</section>
<div class="card prize-bottom"><div><h2>¿Ya escogiste tu número?</h2><p class="muted">Consulta cuáles están disponibles y reserva uno antes que se ocupe.</p></div><a class="button" href="rifa.php">Ver números disponibles</a></div>
<?php page_footer(); ?>
