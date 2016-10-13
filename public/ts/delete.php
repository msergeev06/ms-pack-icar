<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("ТО - Удаление данных о прохождении ТО");

use MSergeev\Packages\Icar\Lib;

?>
<? $curDir = basename(__DIR__); ?>
<?if(!isset($_POST["ts_id"])):?>
	<p>Вы уверены, что хотите удалить запись с ID=<?=$_GET["id"]?>?</p>
	<p><form action="" method="post">
		<input type="hidden" name="ts_id" value="<?=$_GET["id"]?>">
		<input type="submit" value="Да, удалить">&nbsp;&nbsp;&nbsp;<a href="<?$curDir?>/index.php">Отменить</a>
	</form></p>
<?else:?>
	<?
	if ($res = Lib\Ts::deleteTs($_POST["ts_id"])) {
		?><span style="color: green;">Успешно удалено</span><?
	}
	else {
		?><span style="color: red;">Ошибка удаления данных</span><?
	}
	?>
<?endif;?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
