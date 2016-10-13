<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class PointTypesTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_point_types';
	}
	public static function getTableTitle () {
		return 'Типы путевых точек';
	}
	public static function getTableLinks () {
		return array(
			'ID' => array(
				'ms_icar_points' => 'POINT_TYPES_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID типа путевых точек'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название типа путевых точек'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode",
					'column' => 'NAME'
				),
				'title' => 'Код типа путевых точек'
			)),
			new Entity\BooleanField ('DEFAULT', array(
				'required' => true,
				'default_value' => false,
				'title' => 'Тип путевой точки по-умолчанию'
			))
		);
	}
	public static function getArrayDefaultValues () {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Путевая точка',
				'CODE' => 'waypoint',
				'SORT' => 10,
				'DEFAULT' => true
			),
			array(
				'ID' => 2,
				'NAME' => 'Заправка',
				'CODE' => 'gasstation',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'Сервис',
				'CODE' => 'service',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Автомагазин',
				'CODE' => 'shop',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Мойка',
				'CODE' => 'wash',
				'SORT' => 50
			),
			array(
				'ID' => 6,
				'NAME' => 'Прочее',
				'CODE' => 'other',
				'SORT' => 10000
			)
		);
	}
}