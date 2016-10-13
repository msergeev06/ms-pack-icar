<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Мои машины - Удаление автомобиля");

use \MSergeev\Packages\Icar\Lib;

if (!isset($_POST['step']))
{
	if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
	{
		$carID = intval($_REQUEST['car']);

		$canDelete = Lib\MyCar::canDeleteCar($carID);
		$carInfo = Lib\MyCar::getCarByID($carID);
		if (!$canDelete)
		{
			?><p>Данный автомобиль удалить невозможно, так как существуют данные, ссылающиеся на него.</p><?
		}
		else
		{
			?>

			<form method="post" action="" name="delete_car">
				<input type="hidden" name="step" value="1">
				<input type="hidden" name="car_id" value="<?=$carID?>">
				<p><input class="check_confirm" type="checkbox" name="confirm" value="1">&nbsp;Я подтверждаю, что хочу удалить автомобиль "<?=$carInfo['NAME']?>", c номером "<?=$carInfo['CAR_NUMBER']?>"!</p>
				<input class="delete_submit" type="submit" name="submit_delete_car" value="Удалить автомобиль">
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
			?><p>Автомобиль успешно удален</p><?
		}
		else
		{
			?><p>При удалении возникла ошибка. Автомобиль не был удален</p><?
		}
	}
	else
	{
		?><p>При удалении возникла ошибка. Автомобиль не был удален</p><?
	}
}
?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
