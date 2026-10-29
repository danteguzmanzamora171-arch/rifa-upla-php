<?php
require_once __DIR__.'/includes/layout.php';require_web_login();
$message='';$ok=false;
if($_SERVER['REQUEST_METHOD']==='POST') {
 if(!valid_csrf()){$message='Solicitud inválida.';}
 elseif(!isset($_FILES['comprobante']) || $_FILES['comprobante']['error']!==UPLOAD_ERR_OK){$message='Error al recibir el archivo.';}
 else {
  $f=$_FILES['comprobante'];$ext=strtolower(pathinfo((string)$f['name'],PATHINFO_EXTENSION));
  $allowed=['pdf'=>'application/pdf','png'=>'image/png','jpg'=>'image/jpeg','jpeg'=>'image/jpeg'];
  $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  if($f['size']>5*1024*1024 || $f['size']===0){$message='El archivo debe pesar entre 1 byte y 5 MB.';}
  elseif(!isset($allowed[$ext]) || $allowed[$ext]!==$mime){$message='Solo se aceptan PDF, JPG y PNG válidos.';}
  else {
   $name=date('Ymd_His').'_'.bin2hex(random_bytes(6)).'.'.$ext;
   $ok=move_uploaded_file($f['tmp_name'],__DIR__.'/comprobantes/'.$name);
   $message=$ok?'Archivo cargado correctamente.':'Error al guardar el archivo.';
  }
 }
}
page_header('Subir comprobante'); ?>
<div class="badge">Comprobantes</div><h1>Subir comprobante</h1><p class="muted">El archivo quedará almacenado en la carpeta <code>comprobantes</code>.</p>
<?php if($message): ?><div class="alert <?=$ok?'success':''?>"><?=e($message)?></div><?php endif; ?>
<div class="card"><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label for="f">Seleccionar archivo (PDF, PNG o JPG · máximo 5 MB)</label><input id="f" name="comprobante" type="file" accept=".pdf,.png,.jpg,.jpeg" required><button>Subir comprobante</button></form></div>
<?php page_footer(); ?>
