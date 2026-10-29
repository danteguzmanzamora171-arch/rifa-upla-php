<?php
require_once __DIR__.'/config.php';
function page_header(string $title): void { ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=e($title)?> · Rifa UPLA</title><link rel="stylesheet" href="assets/style.css?v=20261029-fix2"></head><body>
<header class="sitebar"><div class="wrap nav"><a class="brand" href="panel.php"><span class="logo">R</span> Rifa UPLA</a><nav><?php if(!empty($_SESSION['web_auth'])): ?><a href="panel.php">Panel</a><a href="rifa.php">Rifa</a><a href="buscar.php">Buscar</a><a href="administrar.php">Premios</a><a href="subir_comprobante.php">Subir</a><a href="archivos.php">Archivos</a><a class="out" href="salir.php">Salir</a><?php endif; ?></nav></div></header><main class="wrap">
<?php }
function page_footer(): void {echo '</main><footer class="wrap footer">Proyecto de evaluación PHP y Servicios Web · UPLA</footer></body></html>';}
