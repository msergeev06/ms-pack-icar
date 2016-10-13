<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class ReasonReplacementTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_reason_replacement';
	}
	public static function getTableTitle () {
		return 'Причина замены';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'REASON_REPLACEMENT_ID',
				'ms_icar_repair' => 'REASON_REPLACEMENT_ID'
			)
		);
	}
	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID причины замены'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название причины замены'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код причины замены'
			))
		);
	}
	public static function getArrayDefaultValues() {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Плановая или ТО',
				'CODE' => 'ts',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'Поломка',
				'CODE' => 'breakdown',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'Тюнинг',
				'CODE' => 'tuning',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Апгрейд',
				'CODE' => 'upgrade',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Шиномонтаж',
				'CODE' => 'tire',
				'SORT' => 50
			),
			array(
				'ID' => 6,
				'NAME' => 'ДТП',
				'CODE' => 'accident',
				'SORT' => 60
			)
		);
	}
}