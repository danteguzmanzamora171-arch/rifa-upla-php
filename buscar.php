<?php
require_once __DIR__.'/includes/layout.php';require_web_login();
$participantes=[
 ['nombre'=>'Universidad','apellido'=>'Playa Ancha','ciudad'=>'Valparaiso','categoria'=>'Grupo A'],
 ['nombre'=>'Ana','apellido'=>'Perez','ciudad'=>'Vina del Mar','categoria'=>'Grupo B'],
 ['nombre'=>'Carlos','apellido'=>'Soto','ciudad'=>'Santiago','categoria'=>'Grupo A'],
 ['nombre'=>'Maria','apellido'=>'Gonzalez','ciudad'=>'Quilpue','categoria'=>'Grupo C']
];
function normalize_search(string $s): string {return mb_strtolower(trim($s),'UTF-8');}
$didSearch=isset($_GET['buscar']);$found=false;
if($didSearch){foreach($participantes as $p){
  if(normalize_search($p['nombre'])===normalize_search((string)($_GET['nombre']??'')) && normalize_search($p['apellido'])===normalize_search((string)($_GET['apellido']??'')) && normalize_search($p['ciudad'])===normalize_search((string)($_GET['ciudad']??'')) && normalize_search($p['categoria'])===normalize_search((string)($_GET['categoria']??''))){$found=true;break;}
}}
page_header('Buscar participantes'); ?>
<div class="badge">Consulta de participantes</div><h1>Buscar participantes</h1><p class="muted">Completa los cuatro campos. Se deben cumplir todas las condiciones.</p>
<div class="card"><form method="get"><div class="grid"><div><label>Nombre</label><input name="nombre" required value="<?=e($_GET['nombre']??'')?>"></div><div><label>Apellido</label><input name="apellido" required value="<?=e($_GET['apellido']??'')?>"></div><div><label>Ciudad</label><input name="ciudad" required value="<?=e($_GET['ciudad']??'')?>"></div><div><label>Categoría</label><select name="categoria" required><option value="">Seleccionar</option><?php foreach(['Grupo A','Grupo B','Grupo C'] as $c): ?><option value="<?=e($c)?>" <?=($_GET['categoria']??'')===$c?'selected':''?>><?=e($c)?></option><?php endforeach; ?></select></div></div><button name="buscar" value="1">Buscar</button></form></div>
<?php if($didSearch): ?><div class="alert <?=$found?'success':''?>"><strong><?=$found?'Participante encontrado':'Participante no encontrado'?></strong></div><?php endif; ?>
<?php page_footer(); ?>
