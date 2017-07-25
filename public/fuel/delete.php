<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title_delete'));

use \MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (!isset($_POST['step']))
{
	if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
	{
		$fuelID = intval($_REQUEST['id']);
	?>

		<form class="form-horizontal" role="form" name="fuel_delete" method="post" action="">
			<input type="hidden" name="step" value="1">
			<input type="hidden" name="fuel_id" value="<?=$fuelID?>">
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input class="check_confirm" type="checkbox" name="confirm" value="1">&nbsp;<?=Loc::getPackMessage('icar','all_delete_confirm',array('ID'=>$fuelID))?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="delete_submit submit btn btn-success"><?=Loc::getPackMessage('icar','all_delete')?></button>
				</div>
			</div>
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
			?><div class="text-success"><?=Loc::getPackMessage('icar','all_delete_success')?></div><?
		}
		else
		{
			?><div class="text-danger"><?=Loc::getPackMessage('icar','all_delete_error')?></div><?
		}
	}
	else
	{
		?><div class="text-danger"><?=Loc::getPackMessage('icar','all_delete_error')?></div><?
	}
}
?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
