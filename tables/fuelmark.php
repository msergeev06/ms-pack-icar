<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class FuelmarkTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_fuelmark';
	}
	public static function getTableTitle () {
		return 'Типы топлива';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_fuel' => 'FUELMARK_ID'
			)
		);
	}
	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID типа топлива'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название типа топлива'
			)),
			new Entity\StringField ('SHORT_NAME', array(
				'size' => 20,
				'required' => true,
				'title' => 'Краткое название типа топлива'
			)),
			new Entity\StringField ('CODE', array(
				'size' => 20,
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode",
					'column' => 'SHORT_NAME'
				),
				'title' => 'Код типа топлива'
			))
		);
	}
	public static function getArrayDefaultValues () {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Бензин 80',
				'SHORT_NAME' => '80',
				'CODE' => '80',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'Бензин 92',
				'SHORT_NAME' => '92',
				'CODE' => '92',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'Бензин 92 Улучшенный',
				'SHORT_NAME' => '92 Улучшенный',
				'CODE' => '92improved',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Бензин 95',
				'SHORT_NAME' => '95',
				'CODE' => '95',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Бензин 95 Улучшенный',
				'SHORT_NAME' => '95 Улучшенный',
				'CODE' => '95improved',
				'SORT' => 50
			),
			array(
				'ID' => 6,
				'NAME' => 'Бензин 95 Био',
				'SHORT_NAME' => '95 Био',
				'CODE' => '95bio',
				'SORT' => 60,
			),
			array(
				'ID' => 7,
				'NAME' => 'Бензин 98',
				'SHORT_NAME' => '98',
				'CODE' => '98',
				'SORT' => 70,
			),
			array(
				'ID' => 8,
				'NAME' => 'Дизельное топливо',
				'SHORT_NAME' => 'ДТ',
				'CODE' => 'disel',
				'SORT' => 80
			),
			array(
				'ID' => 9,
				'NAME' => 'Газ',
				'SHORT_NAME' => 'Газ',
				'CODE' => 'gas',
				'SORT' => 90
			)
		);
	}
}