<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class RoutsTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_routs';
	}
	public static function getTableTitle() {
		return 'Маршруты';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID маршрута'
			)),
			new Entity\IntegerField ('MY_CAR_ID', array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField ('DATE', array(
				'required' => true,
				'title' => 'Дата поездки'
			)),
			new Entity\IntegerField ('START_POINTS_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID начальной путевой точки'
			)),
			new Entity\BooleanField ('END_START', array(
				'required' => true,
				'default_value' => false,
				'title' => 'Конец в той же точке, что и начало'
			)),
			new Entity\IntegerField ('END_POINTS_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID конечной путевой точки'
			)),
			new Entity\FloatField ('ODO', array(
				'title' => 'Пробег'
			))
	);
	}
}