<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(\MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title')." - ".\MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title_delete'));

use MSergeev\Packages\Icar\Lib;
use \MSergeev\Core\Lib\Loc;

?>
<? $curDir = basename(__DIR__); ?>
<?if(!isset($_POST["ts_id"])):?>
	<p><?=Loc::getPackMessage('icar','ts_delete_confirm',array('ID'=>$_GET["id"]))?></p>
	<p><form action="" method="post">
		<input type="hidden" name="ts_id" value="<?=$_GET["id"]?>">
		<input type="submit" value="<?=Loc::getPackMessage('icar','ts_yes_delete')?>">&nbsp;&nbsp;&nbsp;<a href="<?$curDir?>/index.php"><?=Loc::getPackMessage('icar','ts_cancel')?></a>
	</form></p>
<?else:?>
	<?
	if ($res = Lib\Ts::deleteTs($_POST["ts_id"])) {
		?><span style="color: green;"><?=Loc::getPackMessage('icar','ts_delete_success')?></span><?
	}
	else {
		?><span style="color: red;"><?=Loc::getPackMessage('icar','ts_delete_error')?></span><?
	}
	?>
<?endif;?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
