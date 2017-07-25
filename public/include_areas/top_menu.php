<?
use MSergeev\Core\Lib;
$path = Lib\Tools::getSitePath(Lib\Loader::getPublic("icar"));
$imgPath = Lib\Tools::getSitePath(Lib\Loader::getTemplate("icar")."images/");
$imgWidth = $imgHeight = 30;
?>
<div class="dropdown visible-xs">
	<br><button type="button" data-toggle="dropdown" class="btn btn-info">Меню</button>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
		<li class="visible-xs" style="min-height: 30px;"><a href="<?=$path?>fuel/add.php"><span class="glyphicon glyphicon-tint"></span> Заправился</a></li>
		<li class="visible-xs" style="min-height: 30px;"><a href="<?=$path?>mileage/add.php"><span class="glyphicon glyphicon-send"></span> Маршруты</a></li>
		<li class="divider visible-xs"></li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/mileage/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>mileage/">
				<img src="<?=$imgPath?>route.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_mileage')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_mileage')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/fuel/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>fuel/">
				<img src="<?=$imgPath?>fuel.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_fuel')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_fuel')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/ts/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>ts/">
				<img src="<?=$imgPath?>ts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_ts')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_ts')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/repair_parts/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>repair_parts/">
				<img src="<?=$imgPath?>repair_parts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/repair/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>repair/">
				<img src="<?=$imgPath?>repair.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repair')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_repair')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/additional_parts/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>additional_parts/">
				<img src="<?=$imgPath?>add_parts.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_parts')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_parts')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/other/'))?' active':''?>" style="text-align: left;">
			<a href="<?=$path?>other/">
				<img src="<?=$imgPath?>other.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_other')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_other')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/credit/'))?' active':''?>">
			<a href="<?=$path?>credit/">
				<img src="<?=$imgPath?>credit.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_credit')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_credit')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/accident/'))?' active':''?>">
			<a href="<?=$path?>accident/">
				<img src="<?=$imgPath?>accident.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_accident')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_accident')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/income/'))?' active':''?>">
			<a href="<?=$path?>income/">
				<img src="<?=$imgPath?>income.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_income')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_income')?>
			</a>
		</li>
		<li class="divider"></li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/my_cars/'))?' active':''?>">
			<a href="<?=$path?>my_cars/">
				<img src="<?=$imgPath?>car.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_cars')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_cars')?>
			</a>
		</li>
		<li class="visible-xs<?=(Lib\Tools::isDir('/points/'))?' active':''?>">
			<a href="<?=$path?>points/">
				<img src="<?=$imgPath?>points.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_points')?>">
				<?=Lib\Loc::getPackMessage('icar','menu_points')?>
			</a>
		</li>
	</ul>
</div>

<nav class="navbar navbar-default visible-sm visible-md visible-lg" role="navigation">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
			<?/*
			<li<?=(Lib\Tools::getCurDir() == $path)?' class="active"':''?>>
				<a href="<?=$path?>">
					<img src="<?=$imgPath?>main.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_main')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_main')?>
				</a>
			</li>
			*/?>
			<li class="visible-md visible-lg<?=(Lib\Tools::isDir('/my_cars/'))?' active':''?>">
				<a href="<?=$path?>my_cars/">
					<img src="<?=$imgPath?>car.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_cars')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_cars')?>
				</a>
			</li>
			<li class="dropdown visible-sm<?=(Lib\Tools::isDir(array('/my_cars/','/points/')))?' active':''?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?=$imgPath?>car.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_cars')?>">
					<br>Общее <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li class="visible-sm<?=(Lib\Tools::isDir('/my_cars/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>my_cars/">
							<img src="<?=$imgPath?>car.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_cars')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_cars')?>
						</a>
					</li>
					<li class="visible-sm<?=(Lib\Tools::isDir('/points/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>points/">
							<img src="<?=$imgPath?>points.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_points')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_points')?>
						</a>
					</li>
					<li class="divider"></li>
				</ul>
			</li>
			<li class="dropdown<?=(Lib\Tools::isDir('/mileage/'))?' active':''?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?=$imgPath?>route.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_mileage')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_mileage')?> <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li style="text-align: left;" style="min-height: 30px;"><a href="<?=$path?>mileage/add.php"><span class="glyphicon glyphicon-send"></span> Добавить</a></li>
					<li class="divider"></li>
					<li<?=(Lib\Tools::isDir('/mileage/'))?' class="active"':''?> style="text-align: left;">
						<a href="<?=$path?>mileage/">
							<img src="<?=$imgPath?>route.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_mileage')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_mileage')?>
						</a>
					</li>
				</ul>
			</li>
			<li class="dropdown<?=(Lib\Tools::isDir('/fuel/'))?' active':''?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?=$imgPath?>fuel.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_fuel')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_fuel')?> <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li style="text-align: left;" style="min-height: 30px;"><a href="<?=$path?>fuel/add.php"><span class="glyphicon glyphicon-tint"></span> Добавить</a></li>
					<li class="divider"></li>
					<li<?=(Lib\Tools::isDir('/fuel/'))?' class="active"':''?> style="text-align: left;">
						<a href="<?=$path?>fuel/">
							<img src="<?=$imgPath?>fuel.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_fuel')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_fuel')?>
						</a>
					</li>
				</ul>
			</li>
			<li class="visible-lg<?=(Lib\Tools::isDir('/ts/'))?' active':''?>">
				<a href="<?=$path?>ts/">
					<img src="<?=$imgPath?>ts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_ts')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_ts')?>
				</a>
			</li>
			<li class="visible-lg<?=(Lib\Tools::isDir('/repair_parts/'))?' active':''?>">
				<a href="<?=$path?>repair_parts/">
					<img src="<?=$imgPath?>repair_parts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>
				</a>
			</li>
			<li class="visible-lg<?=(Lib\Tools::isDir('/repair/'))?' active':''?>">
				<a href="<?=$path?>repair/">
					<img src="<?=$imgPath?>repair.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repair')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_repair')?>
				</a>
			</li>
			<li class="visible-lg<?=(Lib\Tools::isDir('/additional_parts/'))?' active':''?>">
				<a href="<?=$path?>additional_parts/">
					<img src="<?=$imgPath?>add_parts.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_parts')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_parts')?>
				</a>
			</li>
			<li class="visible-lg<?=(Lib\Tools::isDir('/other/'))?' active':''?>">
				<a href="<?=$path?>other/">
					<img src="<?=$imgPath?>other.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_other')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_other')?>
				</a>
			</li>
			<li class="dropdown hidden-lg<?=(Lib\Tools::isDir(array('/ts/','/repair_parts/','/repair/','/additional_parts/','/other/')))?' active':''?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?=$imgPath?>ts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_ts')?>">
					<br>Уход <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li class="hidden-lg<?=(Lib\Tools::isDir('/ts/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>ts/">
							<img src="<?=$imgPath?>ts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_ts')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_ts')?>
						</a>
					</li>
					<li class="hidden-lg<?=(Lib\Tools::isDir('/repair_parts/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>repair_parts/">
							<img src="<?=$imgPath?>repair_parts.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_repairparts')?>
						</a>
					</li>
					<li class="hidden-lg<?=(Lib\Tools::isDir('/repair/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>repair/">
							<img src="<?=$imgPath?>repair.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_repair')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_repair')?>
						</a>
					</li>
					<li class="hidden-lg<?=(Lib\Tools::isDir('/additional_parts/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>additional_parts/">
							<img src="<?=$imgPath?>add_parts.png" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_parts')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_parts')?>
						</a>
					</li>
					<li class="hidden-lg<?=(Lib\Tools::isDir('/other/'))?' active':''?>" style="text-align: left;">
						<a href="<?=$path?>other/">
							<img src="<?=$imgPath?>other.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_other')?>">
							<?=Lib\Loc::getPackMessage('icar','menu_other')?>
						</a>
					</li>
					<li class="divider"></li>
				</ul>
			</li>
			<li<?=(Lib\Tools::isDir('/credit/'))?' class="active"':''?>>
				<a href="<?=$path?>credit/">
					<img src="<?=$imgPath?>credit.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_credit')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_credit')?>
				</a>
			</li>
			<li<?=(Lib\Tools::isDir('/accident/'))?' class="active"':''?>>
				<a href="<?=$path?>accident/">
					<img src="<?=$imgPath?>accident.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_accident')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_accident')?>
				</a>
			</li>
			<li<?=(Lib\Tools::isDir('/income/'))?' class="active"':''?>>
				<a href="<?=$path?>income/">
					<img src="<?=$imgPath?>income.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_income')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_income')?>
				</a>
			</li>
			<li class="visible-md visible-lg<?=(Lib\Tools::isDir('/points/'))?' active':''?>">
				<a href="<?=$path?>points/">
					<img src="<?=$imgPath?>points.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_points')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_points')?>
				</a>
			</li>
			<li class="dropdown<?=(Lib\Tools::isDir('/setup/'))?' active':''?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?=$imgPath?>setup.jpg" width="<?=$imgWidth?>" height="<?=$imgHeight?>" alt="<?=Lib\Loc::getPackMessage('icar','menu_setup')?>">
					<br><?=Lib\Loc::getPackMessage('icar','menu_setup')?> <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#"><?=Lib\Loc::getPackMessage('icar','menu_setup_first')?></a></li>
					<li class="divider"></li>
				</ul>
			</li>
		</ul>
	</div><!-- /.container-fluid -->
</nav>

<style>
ul.nav li {
	text-align: center;
}
</style>