<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','rp_title')." - Удаление информации о приобретенных зап.частях");

?>
<? $curDir = basename(__DIR__); ?>
<?if(!isset($_POST["rp_id"])):?>
	<p><?=CoreLib\Loc::getPackMessage('icar','rp_delete_confirm',array('ID'=>$_GET["id"]))?></p>
	<p><form action="" method="post">
		<input type="hidden" name="rp_id" value="<?=$_GET["id"]?>">
		<input type="submit" value="<?=CoreLib\Loc::getPackMessage('icar','rp_yes_delete')?>">&nbsp;&nbsp;&nbsp;<a href="<?$curDir?>/index.php"><?=CoreLib\Loc::getPackMessage('icar','rp_cancel')?></a>
	</form></p>
<?else:?>
	<?
	if ($res = Lib\RepairParts::deleteRecord($_POST["rp_id"])) {
		?><span style="color: green;"><?=CoreLib\Loc::getPackMessage('icar','rp_delete_success')?></span><?
	}
	else {
		?><span style="color: red;"><?=CoreLib\Loc::getPackMessage('icar','rp_delete_error')?></span><?
	}
	?>
<?endif;?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
