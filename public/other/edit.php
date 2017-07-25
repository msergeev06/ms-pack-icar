<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle("Прочие расходы - Редактирование прочих расходов");

$carID = (isset($_REQUEST['car'])?intval($_REQUEST['car']):Lib\MyCar::getDefaultCarID());

if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\OtherExpense::updateFromPost($_POST)) {
		?><span class="ok">Данные успешно сохранены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'other/',3);
		$bError = false;
	}
	else {
		?><span class="error">Ошибка сохранения данных</span><?
		$bError = true;
		//msDebug($res);
	}
}
if (Lib\Errors::issetWarningsAndErrors())
{
	echo Lib\Errors::showErrorList();
}
$arData = false;
if (isset($_REQUEST['id']))
{
	Lib\Fields::validateInt($_REQUEST['id']);
	$arData = Lib\OtherExpense::getList($carID,$_REQUEST['id']);
}
if ($arData):?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField($arData['MY_CAR_ID'])?>
		<?=Lib\Fields::showDateField($arData['DATE'])?>
		<?=Lib\Fields::showNameField($arData['NAME'])?>
		<?=Lib\Fields::showCatalogNumberField($arData['CATALOG_NUMBER'])?>
		<?=Lib\Fields::showFlowTypeField($arData['FLOW_TYPE_ID'])?>
		<?=Lib\Fields::showCostField($arData['COST'])?>
		<?=Lib\Fields::showNumberField($arData['NUMBER'])?>
		<?=Lib\Fields::showOdoField($arData['ODO'])?>
		<?=Lib\Fields::showStartPointField(
			$arData['POINTS_ID'],
			true,
			false,
			'Путевая точка'
		)?>
		<?=Lib\Fields::showCommentField($arData['INFO'])?>
		<?=Lib\Fields::showCheckField($arData['CHECK'])?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Сохранить"></td>
		</tr>
	</table>
</form>
<?endif;?>
<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
