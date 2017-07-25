<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','ts_title')." - ".CoreLib\Loc::getPackMessage('icar','ts_title_edit'));

if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
{
	$editID = intval($_REQUEST['id']);
}
else
{
	die("ERROR");
}

if (!isset($_POST["action"]))
{
	if (!$arTs = Lib\Ts::getList (NULL, $editID))
	{
		die("ERROR");
	}
	else
	{
		$arTs = $arTs[0];
	}
	//msDebug($arTs);
	?>
	<form action="" method="post">
		<table class="table_form">
			<?=Lib\Fields::showCarIdField($arTs["MY_CAR_ID"])?>
			<?=Lib\Fields::showTsField($arTs["TS_NUM"])?>
			<?=Lib\Fields::showDateField($arTs["DATE"])?>
			<?=Lib\Fields::showExecutorsField($arTs["EXECUTORS_ID"])?>
			<?=Lib\Fields::showCostField($arTs["COST"])?>
			<?=Lib\Fields::showOdoField($arTs["ODO"])?>
			<?=Lib\Fields::showStartPointField($arTs["POINTS_ID"])?>
			<?=Lib\Fields::showCommentField($arTs["INFO"])?>
			<tr>
				<td class="center" colspan="2">
					<input type="hidden" name="action" value="1">
					<input type="hidden" name="edit_id" value="<?=$editID?>">
					<input type="submit" value="<?=CoreLib\Loc::getPackMessage('icar','all_edit')?>">
				</td>
			</tr>
		</table>
	</form>

<?
}
else {
	if ($res = Lib\Ts::updateFromPost(intval($_POST['edit_id']),$_POST)) {
		?><span style="color: green;"><?=CoreLib\Loc::getPackMessage('icar','ts_edit_success')?></span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'ts/',3);
	}
	else {
		?><span style="color: red;"><?=CoreLib\Loc::getPackMessage('icar','ts_edit_error')?></span><?
	}
	//echo "<pre>"; print_r($arPost); echo "</pre>";
}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
