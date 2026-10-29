<?php
require_once __DIR__.'/common.php';only_method('POST');api_auth();$a=api_input();$id=filter_var($a['id']??null,FILTER_VALIDATE_INT);
if($id===false || $id===null || $id<1)result_json(422,['error'=>'ID inválido']);
$f=fields($a,['nombre','apellido','numero_rifa']);
if(filter_var($f['numero_rifa'],FILTER_VALIDATE_INT)===false || (int)$f['numero_rifa']<1)result_json(422,['error'=>'numero_rifa debe ser un entero positivo']);
$f['numero_rifa']=(int)$f['numero_rifa'];
try {$updated=modify_data('participantes',function(&$data) use($id,$f){
 foreach($data as $p){if((int)$p['id']!==$id && (int)$p['numero_rifa']===$f['numero_rifa'])result_json(409,['error'=>'Número de rifa usado por otro participante']);}
 foreach($data as &$p){if((int)$p['id']===$id){$p=array_merge($p,$f);return $p;}}return null;
 });if($updated===null)result_json(404,['error'=>'Participante no encontrado']);result_json(200,['mensaje'=>'Participante actualizado','participante'=>$updated]);}
 catch(Throwable $e){result_json(500,['error'=>'Error al actualizar']);}
