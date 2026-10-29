<?php
require_once dirname(__DIR__).'/includes/config.php';require_once dirname(__DIR__).'/includes/store.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');
function result_json(int $status,array $body): never {http_response_code($status);echo json_encode($body,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);exit;}
function only_method(string $method): void {if($_SERVER['REQUEST_METHOD']!==$method){header('Allow: '.$method);result_json(405,['error'=>'Método no permitido; use '.$method]);}}
function api_auth(): void {if(empty($_SESSION['api_auth']))result_json(401,['error'=>'Debes iniciar sesión en la API']);}
function api_input(): array {
 $type=$_SERVER['CONTENT_TYPE']??'';
 if(str_contains($type,'application/json')){$data=json_decode(file_get_contents('php://input'),true);return is_array($data)?$data:[];}
 return $_POST;
}
function fields(array $src,array $keys): array {
 $out=[];foreach($keys as $key){if(!isset($src[$key]) || !is_scalar($src[$key]) || trim((string)$src[$key])==='')result_json(422,['error'=>'Campo obligatorio: '.$key]);$out[$key]=trim((string)$src[$key]);if(mb_strlen($out[$key])>120) result_json(422,['error'=>'Campo demasiado largo: '.$key]);}return $out;
}
