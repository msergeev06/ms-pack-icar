<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title_delete'));

use \MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (!isset($_POST['step']))
{
	if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
	{
		$fuelID = intval($_REQUEST['id']);
	?>

		<form method="post" action="" name="delete_fuel">
			<input type="hidden" name="step" value="1">
			<input type="hidden" name="fuel_id" value="<?=$fuelID?>">
			<p><input class="check_confirm" type="checkbox" name="confirm" value="1">&nbsp;<?=Loc::getPackMessage('icar','fuel_confirm_delete',array('ID'=>$fuelID))?></p>
			<input class="delete_submit" type="submit" name="submit_delete_car" value="<?=Loc::getPackMessage('icar','all_delete')?>">
		</form>
		<script type="text/javascript">
			$(document).on("ready",function(){
				$('.delete_submit').attr('disabled',true);
				$('.check_confirm').on('click',function(){
					if ($('.check_confirm').is(':checked'))
					{
						$('.delete_submit').attr("disabled",false);
					}
					else
					{
						$('.delete_submit').attr("disabled",true);
					}
				});
			});
		</script>
	<?
	}
}
else
{
	if (isset($_POST['confirm']) && intval($_POST['confirm'])==1)
	{
		$res = Lib\Fuel::deleteFuel(intval($_POST['fuel_id']));
		if ($res)
		{
			?><p><?=Loc::getPackMessage('icar','fuel_delete_success')?></p><?
		}
		else
		{
			?><p><?=Loc::getPackMessage('icar','fuel_delete_error')?></p><?
		}
	}
	else
	{
		?><p><?=Loc::getPackMessage('icar','fuel_delete_error')?></p><?
	}
}
?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
