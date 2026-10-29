<?php require_once __DIR__.'/common.php';only_method('GET');api_auth();result_json(200,['participantes'=>read_data('participantes')]);
