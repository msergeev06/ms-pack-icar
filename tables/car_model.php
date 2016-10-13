<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Lib\TableHelper;

class CarModelTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_car_model';
	}
	public static function getTableTitle() {
		return 'Модели автомобилей';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_my_car' => 'CAR_MODEL_ID'
			)
		);
	}
	public static function getMap(){
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID модели автомобиля'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\IntegerField('BRANDS_ID',array(
				'link' => 'ms_icar_car_brands.ID',
				'required' => true,
				'default_value' => 0,
				'title' => 'ID бренда автомобиля'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название модели автомобиля'
			)),
			new Entity\StringField('CODE',array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode",
					'column' => 'NAME'
				),
				'title' => 'Код модели автомобиля'
			))
		);
	}
	public static function getArrayDefaultValues ()
	{
		return array(
			array(
				'BRANDS_ID' => 1,
				'NAME' => 'On Do',
				'CODE' => 'ondo'
			)
		);
	}
}