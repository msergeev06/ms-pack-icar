<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class OdoTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_odo';
	}
	public static function getTableTitle() {
		return 'Пробег за день';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID записи о пробеге'
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
			new Entity\FloatField ('ODO', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег за день'
			))
		);
	}
}