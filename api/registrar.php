<?php
require_once __DIR__.'/common.php';only_method('POST');api_auth();$f=fields(api_input(),['nombre','apellido','numero_rifa']);
if(filter_var($f['numero_rifa'],FILTER_VALIDATE_INT)===false || (int)$f['numero_rifa']<1) result_json(422,['error'=>'numero_rifa debe ser un entero positivo']);
$f['numero_rifa']=(int)$f['numero_rifa'];
try {$new=modify_data('participantes',function(&$data) use ($f){
 foreach($data as $p){if((int)$p['numero_rifa']===$f['numero_rifa'])result_json(409,['error'=>'Ese número de rifa ya está registrado']);}
 $ids=array_column($data,'id');$entry=['id'=>($ids?max($ids):0)+1]+$f;$data[]=$entry;return $entry;
});result_json(201,['mensaje'=>'Participante registrado','participante'=>$new]);}
catch(Throwable $e){result_json(500,['error'=>'No se pudo guardar el registro']);}
