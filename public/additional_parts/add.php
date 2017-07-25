<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle("Дополнительное оборудование - Добавление расходов на дополнительное оборудование");

$carID = (isset($_REQUEST['car'])?intval($_REQUEST['car']):Lib\MyCar::getDefaultCarID());

if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\OptionalEquip::addFromPost($_POST)) {
		?><span class="ok">Данные успешно добавлены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'additional_parts/',3);
		$bError = false;
	}
	else {
		?><span class="error">Ошибка добавления данных</span><?
		$bError = true;
		//msDebug($res);
	}
	if (Lib\Errors::issetWarningsAndErrors())
	{
		echo Lib\Errors::showErrorList();
	}
}

if (isset($_POST['date']))
{
	$date = $_POST['date'];
}
else
{
	$date = date('d.m.Y');
}
?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField((($bError)?intval($_POST['my_car']):$carID))?>
		<?=Lib\Fields::showDateField($date)?>
		<?=Lib\Fields::showCostField((($bError)?$_POST['cost']:''),'Стоимость за единицу')?>
		<?=Lib\Fields::showNumberField((($bError)?$_POST['number']:''))?>
		<?=Lib\Fields::showNameField((($bError)?$_POST['name']:''))?>
		<?=Lib\Fields::showOdoField((($bError)?$_POST['odo']:''))?>
		<?=Lib\Fields::showCatalogNumberField((($bError)?$_POST['catalog_number']:''))?>
		<?=Lib\Fields::showStartPointField(
			(($bError && intval($_POST['start_point'])>0)?intval($_POST['start_point']):'null'),
			true,
			false,
			'Путевая точка'
		)?>
		<?=Lib\Fields::showCommentField((($bError)?$_POST['comment']:''))?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Добавить запись"></td>
		</tr>
	</table>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
