<?php
require_once __DIR__.'/common.php';only_method('GET');
unset($_SESSION['api_auth']);result_json(200,['mensaje'=>'Sesión de API cerrada']);
