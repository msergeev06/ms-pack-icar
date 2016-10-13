<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Lib\TableHelper;

class CarBrandTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_car_brand';
	}
	public static function getTableTitle() {
		return 'Бренды автомобилей';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_my_car' => 'CAR_BRAND_ID',
				'ms_icar_car_model' => 'CAR_BRAND_ID',
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID Бренда'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Имя бренда'
			)),
			new Entity\StringField('CODE',array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'unique' => true,
				'title' => 'Код бренда'
			))
		);
	}

	public static function getArrayDefaultValues()
	{
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Datsun',
				'CODE' => 'datsun'
			)
		);
	}
}