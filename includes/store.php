<?php
function data_file(string $name): string {return dirname(__DIR__).'/data/'.$name.'.json';}
function read_data(string $name): array {
    $path=data_file($name);
    if (!is_file($path)) return [];
    $f=fopen($path,'rb'); if (!$f) return [];
    flock($f,LOCK_SH); $raw=stream_get_contents($f); flock($f,LOCK_UN); fclose($f);
    $a=json_decode($raw,true); return is_array($a) ? $a : [];
}
// Atomic read-modify-write protected by a per-file lock.
function modify_data(string $name, callable $callback): mixed {
    $file=data_file($name); $lock=fopen($file.'.lock','c');
    if (!$lock || !flock($lock,LOCK_EX)) throw new RuntimeException('No se pudo bloquear el archivo');
    try {
        $data=read_data($name);
        $result=$callback($data);
        $temp=tempnam(dirname($file),'tmp_');
        if ($temp===false) throw new RuntimeException('No se pudo crear archivo temporal');
        $content=json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if ($content===false || file_put_contents($temp,$content)===false || !rename($temp,$file)) { @unlink($temp); throw new RuntimeException('Error al guardar JSON'); }
        return $result;
    } finally {flock($lock,LOCK_UN); fclose($lock);}
}
