<?php

namespace   MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class CarGearboxTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_car_gearbox';
	}
	public static function getTableTitle() {
	return 'Тип коробки передач';
	}
	public static function getTableLinks() {
		return array(
			'ID'=> array(
				'ms_icar_my_car' => 'CAR_GEARBOX_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID коробки передач'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название коробки передач'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код коробки передач'
			))
		);
	}
	public static function getArrayDefaultValues () {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Механика',
				'CODE' => 'mechanics',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'Автомат',
				'CODE' => 'automatic',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'Вариатор',
				'CODE' => 'cvt',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Робот',
				'CODE' => 'robot',
				'SORT' => 40
			)
		);
	}
}