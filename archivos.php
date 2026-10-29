<?php
require_once __DIR__.'/includes/layout.php';require_web_login();
$items=[];foreach(scandir(__DIR__.'/comprobantes')?:[] as $file){if($file[0]!=='.' && preg_match('/^\d{8}_\d{6}_[a-f0-9]{12}\.(pdf|png|jpg|jpeg)$/',$file))$items[]=$file;}
rsort($items);page_header('Archivos subidos'); ?>
<div class="badge">Comprobantes</div><h1>Archivos cargados</h1><p class="muted">Lista de comprobantes guardados en el servidor.</p>
<div class="card"><?php if(!$items):?><p>Todavía no hay archivos cargados.</p><?php else:?><table><thead><tr><th>Archivo</th><th>Acción</th></tr></thead><tbody><?php foreach($items as $f): ?><tr><td><?=e($f)?></td><td><a href="ver_comprobante.php?archivo=<?=urlencode($f)?>" target="_blank" rel="noopener">Ver</a></td></tr><?php endforeach; ?></tbody></table><?php endif;?></div>
<?php page_footer(); ?>
