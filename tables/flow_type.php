<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class FlowTypeTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_flow_type';
	}
	public static function getTableTitle () {
		return 'Типы расходов';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_other_expense' => 'FLOW_TYPE_ID'
			)
		);
	}
	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID расхода'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название типа расхода'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код типа расхода'
			))
		);
	}
	public static function getArrayDefaultValues() {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Мойка',
				'CODE' => 'washing',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'Штраф',
				'CODE' => 'fine',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'ОСАГО',
				'CODE' => 'osago',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'КАСКО',
				'CODE' => 'kasko',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Д(СА)ГО',
				'CODE' => 'dsago',
				'SORT' => 50
			),
			array(
				'ID' => 6,
				'NAME' => 'Стоянка',
				'CODE' => 'parking',
				'SORT' => 60
			),
			array(
				'ID' => 7,
				'NAME' => 'Прочее',
				'CODE' => 'other',
				'SORT' => 70
			)
		);
	}
}