<?
use MSergeev\Core\Lib;
$path = Lib\Tools::getSitePath(Lib\Loader::getPublic("icar"));
$imgPath = Lib\Tools::getSitePath(Lib\Loader::getTemplate("icar")."images/");
$imgWidth = $imgHeight = 50;
Lib\Loc::setModuleMessages('icar');
?>
<table class="top_menu">
	<tr>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>" id="main" class="top_menu_link">
					<img src="<?=$imgPath?>main.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Главная">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_main')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>my_cars/" id="my_cars" class="top_menu_link">
					<img src="<?=$imgPath?>car.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Мои машины">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_cars')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>mileage/" id="mileage" class="top_menu_link">
					<img src="<?=$imgPath?>route.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Пробег">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_mileage')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>ts/" id="ts" class="top_menu_link">
					<img src="<?=$imgPath?>ts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="ТО">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_ts')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>fuel/" id="fuel" class="top_menu_link">
					<img src="<?=$imgPath?>fuel.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Топливо">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_fuel')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>rapair_parts/" id="rapair_parts" class="top_menu_link">
					<img src="<?=$imgPath?>repair_parts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Запчасти">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_repairparts')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>repair/" id="repair" class="top_menu_link">
					<img src="<?=$imgPath?>repair.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Ремонт">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_repair')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>additional_parts/" id="additional_parts" class="top_menu_link">
					<img src="<?=$imgPath?>add_parts.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Дополнительное оборудование">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_parts')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>credit/" id="credit" class="top_menu_link">
					<img src="<?=$imgPath?>credit.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Кредит">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_credit')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>other/" id="other" class="top_menu_link">
					<img src="<?=$imgPath?>other.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Прочее">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_other')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>accident/" id="accident" class="top_menu_link">
					<img src="<?=$imgPath?>accident.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="ДТП">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_accident')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>income/" id="income" class="top_menu_link">
					<img src="<?=$imgPath?>income.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Доход">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_income')?>
				</a>
			</div>
		</td>
		<td>
			<div style="text-align: center;">
				<a href="<?=$path?>points/" id="points" class="top_menu_link">
					<img src="<?=$imgPath?>points.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" border="0" alt="Путевые точки">
					<br><?=Lib\Loc::getMessage('ms_icar_menu_points')?>
				</a>
			</div>
		</td>
	</tr>
</table>
<style>
	.top_menu table {
		width: 900px;
	}
	.top_menu td {
		border: 1px solid cornflowerblue;
		display: block;
		float: left;
		margin: 2px;
	}
</style>