<?php
require_once __DIR__.'/common.php';only_method('GET');api_auth();$id=filter_var($_GET['id']??null,FILTER_VALIDATE_INT);
if($id===false || $id===null || $id<1)result_json(422,['error'=>'ID inválido']);
try {$removed=modify_data('participantes',function(&$data) use($id){foreach($data as $i=>$p){if((int)$p['id']===$id){array_splice($data,$i,1);return true;}}return false;});
 if(!$removed)result_json(404,['error'=>'Participante no encontrado']);result_json(200,['mensaje'=>'Participante eliminado','id'=>$id]);}
catch(Throwable $e){result_json(500,['error'=>'Error al eliminar']);}
