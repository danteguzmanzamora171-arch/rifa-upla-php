<?php
require_once __DIR__.'/includes/config.php';require_web_login();
$name=(string)($_GET['archivo']??'');
if(!preg_match('/^\d{8}_\d{6}_[a-f0-9]{12}\.(pdf|png|jpg|jpeg)$/',$name)){http_response_code(404);exit('No encontrado');}
$file=__DIR__.'/comprobantes/'.$name;
if(!is_file($file)){http_response_code(404);exit('No encontrado');}
header('Content-Type: '.(new finfo(FILEINFO_MIME_TYPE))->file($file));header('Content-Length: '.filesize($file));header('X-Content-Type-Options: nosniff');header('Content-Disposition: inline; filename="'.$name.'"');readfile($file);
