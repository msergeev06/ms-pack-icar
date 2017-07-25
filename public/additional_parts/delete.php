<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle('Дополнительное оборудование - Удаление записи о расходе на дополнительное оборудование');

?>
<? $curDir = basename(__DIR__); ?>
<?if(!isset($_POST["a_id"])):?>
	<p><?=CoreLib\Loc::getPackMessage('icar','rp_delete_confirm',array('ID'=>$_GET["id"]))?></p>
	<p><form action="" method="post">
		<input type="hidden" name="a_id" value="<?=$_GET["id"]?>">
		<input type="submit" value="<?=CoreLib\Loc::getPackMessage('icar','rp_yes_delete')?>">&nbsp;&nbsp;&nbsp;<a href="index.php"><?=CoreLib\Loc::getPackMessage('icar','rp_cancel')?></a>
	</form></p>
<?else:?>
	<?
	if ($res = Lib\OptionalEquip::deleteRecord($_POST["a_id"])) {
		?><span style="color: green;"><?=CoreLib\Loc::getPackMessage('icar','rp_delete_success')?></span><?
	}
	else {
		?><span style="color: red;"><?=CoreLib\Loc::getPackMessage('icar','rp_delete_error')?></span><?
	}
	?>
<?endif;?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
