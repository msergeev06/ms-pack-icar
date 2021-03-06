<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_my_cars')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_car_delete'));

use \MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (!isset($_POST['step']))
{
	if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
	{
		$carID = intval($_REQUEST['car']);

		$canDelete = Lib\MyCar::canDeleteCar($carID);
		$carInfo = Lib\MyCar::getCarByID($carID);
		if (!$canDelete)
		{
			?><p><?=Loc::getPackMessage('icar','mycars_car_no_delete')?></p><?
		}
		else
		{
			?>

			<form class="form-horizontal" role="form" method="post" action="" name="delete_car">
				<input type="hidden" name="step" value="1">
				<input type="hidden" name="car_id" value="<?=$carID?>">
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<input class="check_confirm" type="checkbox" name="confirm" value="1">&nbsp;<?=Loc::getPackMessage('icar','mycars_car_confirm_delete',array('CAR_NAME'=>$carInfo['NAME'],'CAR_NUMBER'=>$carInfo['CAR_NUMBER']))?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="submit btn btn-danger delete_submit"><?=Loc::getPackMessage('icar','mycars_button_car_delete')?></button>
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
}
else
{
	if (isset($_POST['confirm']) && (isset($_POST['car_id']) && intval($_POST['car_id'])))
	{
		$res = Lib\MyCar::deleteCar(intval($_POST['car_id']));
		if ($res)
		{
			?><div class="text-success"><?=Loc::getPackMessage('icar','mycars_car_del_success')?></div><?
		}
		else
		{
			?><div class="text-danger"><?=Loc::getPackMessage('icar','mycars_car_del_error')?></div><?
		}
	}
	else
	{
		?><p><?=Loc::getPackMessage('icar','mycars_car_del_error')?></p><?
	}
}
?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
