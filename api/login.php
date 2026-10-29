<?php
require_once __DIR__.'/common.php';only_method('POST');$a=api_input();
if(hash_equals(APP_USER,(string)($a['usuario']??'')) && hash_equals(APP_PASSWORD,(string)($a['contrasena']??''))){session_regenerate_id(true);$_SESSION['api_auth']=true;result_json(200,['mensaje'=>'Sesión API iniciada correctamente']);}
result_json(401,['error'=>'Credenciales incorrectas']);
