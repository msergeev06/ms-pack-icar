<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class WhoPaidTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_who_paid';
	}
	public static function getTableTitle() {
		return 'Тип плательщика';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'WHO_PAID_ID',
				'ms_icar_accident' => 'WHO_PAID_ID',
				'ms_icar_repair' => 'WHO_PAID_ID'
			)
		);
	}
	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID плательщика'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название плательщика'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код плательщика'
			))
	);
	}
	public static function getArrayDefaultValues() {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Сам платил',
				'CODE' => 'hepaid',
				'SORT' => 10,
			),
			array(
				'ID' => 2,
				'NAME' => 'ОСАГО',
				'CODE' => 'osago',
				'SORT' => 20,
			),
			array(
				'ID' => 3,
				'NAME' => 'КАСКО',
				'CODE' => 'kasko',
				'SORT' => 30
			)
		);
	}
}