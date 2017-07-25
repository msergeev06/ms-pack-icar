<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle("Дополнительное оборудование - Редактирование записи о расходе на дополнительное оборудование");

if (isset($_REQUEST['car']))
{
	Lib\Fields::validateInt($_REQUEST['car']);
	$carID = $_REQUEST['car'];
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\OptionalEquip::updateFromPost($_POST)) {
		?><span class="ok">Данные успешно сохранены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'additional_parts/',3);
		$bError = false;
	}
	else {
		?><span class="error">Ошибка сохранения данных</span><?
		$bError = true;
		//msDebug($res);
	}
}

$arOptionalEquip = array();
if (isset($_REQUEST['id']))
{
	Lib\Fields::validateInt($_REQUEST['id']);
	$arOptionalEquip = Lib\OptionalEquip::getList($carID,$_REQUEST['id']);
}
else
{
	Lib\Errors::addError('id','Не указан ID записи. Редактирование невозможно');
}

if (Lib\Errors::issetWarningsAndErrors())
{
	echo Lib\Errors::showErrorList();
}

if (!empty($arOptionalEquip)):?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField($arOptionalEquip['MY_CAR_ID'])?>
		<?=Lib\Fields::showDateField($arOptionalEquip['DATE'])?>
		<?=Lib\Fields::showCostField($arOptionalEquip['COST'],'Стоимость за единицу')?>
		<?=Lib\Fields::showNumberField($arOptionalEquip['NUMBER'])?>
		<?=Lib\Fields::showNameField($arOptionalEquip['NAME'])?>
		<?=Lib\Fields::showOdoField($arOptionalEquip['ODO'])?>
		<?=Lib\Fields::showCatalogNumberField($arOptionalEquip['CATALOG_NUMBER'])?>
		<?=Lib\Fields::showStartPointField(
			$arOptionalEquip['POINTS_ID'],
			true,
			false,
			'Путевая точка'
		)?>
		<?=Lib\Fields::showCommentField($arOptionalEquip['INFO'])?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Сохранить"></td>
		</tr>
	</table>
</form>
<?endif;?>
<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
