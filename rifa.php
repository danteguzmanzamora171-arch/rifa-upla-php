<?php
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/store.php';
require_web_login();
if (empty($_SESSION['reservation_owner'])) $_SESSION['reservation_owner']=bin2hex(random_bytes(16));
$owner=$_SESSION['reservation_owner'];
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!valid_csrf()) { $_SESSION['rifa_error']='Formulario vencido. Inténtalo de nuevo.'; redirect('rifa.php'); }
    $number=filter_var($_POST['numero']??null,FILTER_VALIDATE_INT);
    $action=$_POST['accion']??'';
    if ($number===false || $number===null || $number<1 || $number>100 || !in_array($action,['agregar','quitar'],true)) {
        $_SESSION['rifa_error']='Selecciona un número válido entre 1 y 100.'; redirect('rifa.php');
    }
    try {
        modify_data('reservas',function (&$reservas) use ($number,$action,$owner) {
            if ($action==='agregar') {
                foreach (read_data('participantes') as $p) if ((int)($p['numero_rifa']??0)===$number) throw new InvalidArgumentException('Ese número ya está ocupado por un participante.');
                foreach ($reservas as $r) if ((int)($r['numero']??0)===$number) throw new InvalidArgumentException('Ese número ya está reservado. Elige otro.');
                $reservas[]=['numero'=>$number,'owner'=>$owner,'fecha'=>date('c')];
            } else {
                $found=false;
                foreach ($reservas as $i=>$r) if ((int)($r['numero']??0)===$number && ($r['owner']??'')===$owner) {array_splice($reservas,$i,1);$found=true;break;}
                if (!$found) throw new InvalidArgumentException('Solo puedes quitar tus propias reservas de esta sesión.');
            }
        });
    } catch(InvalidArgumentException $e) {$_SESSION['rifa_error']=$e->getMessage();}
      catch(Throwable $e) {$_SESSION['rifa_error']='No fue posible guardar la reserva. Revisa los permisos de data/.';}
    redirect('rifa.php');
}
if (isset($_SESSION['rifa_error'])) {$error=$_SESSION['rifa_error'];unset($_SESSION['rifa_error']);}
$reservas=read_data('reservas');
$occupied=[]; $mine=[];
foreach (read_data('participantes') as $p) {$n=(int)($p['numero_rifa']??0);if($n>=1 && $n<=100) $occupied[$n]='ocupado';}
foreach ($reservas as $r) {$n=(int)($r['numero']??0);if($n>=1 && $n<=100) {$occupied[$n]='ocupado';if(($r['owner']??'')===$owner)$mine[$n]=true;}}
$numbers=array_keys($mine);sort($numbers,SORT_NUMERIC);
$quantity=count($numbers);$available=100-count($occupied);
page_header('Reservar números'); ?>
<section class="hero"><div class="badge">Reserva de rifa</div><h1>Elige tu número de la suerte</h1><p class="muted">Los números ocupados no se pueden seleccionar. La ocupación se guarda en un archivo JSON compartido, incluso al actualizar con F5.</p></section>
<?php if($error): ?><div class="alert" role="alert"><?=e($error)?></div><?php endif; ?>
<div class="grid"><div class="card"><div class="muted">Mis números reservados</div><div class="stat"><?=$quantity?></div></div><div class="card"><div class="muted">Precio por número</div><div class="stat">$<?=number_format(PRICE,0,',','.')?></div></div><div class="card"><div class="muted">Total a pagar</div><div class="stat price">$<?=number_format($quantity*PRICE,0,',','.')?></div></div></div>
<div class="card"><div class="row occupied-header"><div><h2>Tablero de números</h2><p class="muted">Selecciona un número disponible. Los tuyos están marcados en azul.</p></div><div class="legend"><span><i class="dot available-dot"></i> Disponible</span><span><i class="dot occupied-dot"></i> Ocupado</span><span><i class="dot mine-dot"></i> Mío</span></div></div>
<div class="number-board">
<?php for($n=1;$n<=100;$n++): ?>
<?php if(isset($mine[$n])): ?><span class="board-num mine" title="Reservado por ti" aria-label="Número <?=$n?> reservado por ti"><?=e($n)?></span>
<?php elseif(isset($occupied[$n])): ?><span class="board-num occupied" title="Número ocupado" aria-label="Número <?=$n?> ocupado"><?=e($n)?></span>
<?php else: ?><form method="post" class="board-form"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="accion" value="agregar"><input type="hidden" name="numero" value="<?=$n?>"><button type="submit" class="board-num available" title="Reservar número <?=$n?>" aria-label="Reservar número <?=$n?>"><?=$n?></button></form><?php endif; ?>
<?php endfor; ?>
</div><p class="muted"><strong><?=$available?></strong> disponibles · <strong><?=count($occupied)?></strong> ocupados (incluye tus reservas)</p></div>
<div class="card"><h2>Mis números</h2><?php if(!$numbers): ?><p class="muted">Todavía no has reservado números. Elige uno en el tablero.</p><?php endif; ?><div class="num-list"><?php foreach($numbers as $n): ?><form method="post" class="num"><span>N.º <?=e($n)?></span><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="accion" value="quitar"><input type="hidden" name="numero" value="<?=e($n)?>"><button class="danger" type="submit">Quitar</button></form><?php endforeach; ?></div><p class="muted">Si cierras sesión, las reservas siguen ocupadas; solo se pueden quitar mientras conserves la misma sesión.</p></div>
<?php page_footer(); ?>
