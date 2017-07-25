<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle("Прочие расходы - Добавление прочих расходов");

$carID = (isset($_REQUEST['car'])?intval($_REQUEST['car']):Lib\MyCar::getDefaultCarID());

if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\OtherExpense::addFromPost($_POST)) {
		?><span class="ok">Данные успешно добавлены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'other/',3);
		$bError = false;
	}
	else {
		?><span class="error">Ошибка добавления данных</span><?
		$bError = true;
		//msDebug($res);
	}
}
if (Lib\Errors::issetWarningsAndErrors())
{
	echo Lib\Errors::showErrorList();
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
<form class="form-horizontal" role="form" name="fuel_add" method="post" action="" enctype="multipart/form-data">
	<?=Lib\Fields::showCarIdField((($bError)?intval($_POST['my_car']):$carID))?>
	<?=Lib\Fields::showDateField($date)?>
	<?=Lib\Fields::showNameField((($bError)?$_POST['name']:''))?>
	<?=Lib\Fields::showCatalogNumberField((($bError)?$_POST['catalog_number']:''))?>
	<?=Lib\Fields::showFlowTypeField((($bError)?$_POST['flow_type']:'null'))?>
	<?=Lib\Fields::showCostField((($bError)?$_POST['cost']:''),'Стоимость за единицу')?>
	<?=Lib\Fields::showNumberField((($bError)?$_POST['number']:''))?>
	<?=Lib\Fields::showOdoField((($bError)?$_POST['odo']:''))?>
	<?=Lib\Fields::showStartPointField(
		(($bError && intval($_POST['start_point'])>0)?intval($_POST['start_point']):'null'),
		true,
		false,
		'Путевая точка'
	)?>
	<?=Lib\Fields::showCommentField((($bError)?$_POST['comment']:''))?>
	<?=Lib\Fields::showCheckField()?>
	<input type="hidden" name="car" value="<?=$carID?>">
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
