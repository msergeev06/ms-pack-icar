<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class OptionalEquipTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_optional_equip';
	}
	public static function getTableTitle() {
		return 'Дополнительное оборудование';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID рахода на доп. оборудование'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField('DATE',array(
				'required' => true,
				'title' => 'Дата'
			)),
			new Entity\FloatField('SUM',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Сумма'
			)),
			new Entity\FloatField('NUM',array(
				'required' => true,
				'default_value' => 1,
				'title' => 'Количество'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название'
			)),
			new Entity\FloatField('ODO',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег'
			)),
			new Entity\StringField('CATALOG_NUMBER',array(
				'title' => 'Каталожный номер'
			)),
			new Entity\IntegerField('POINTS_ID',array(
				'required' => true,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Примечание'
			)),
		);
	}
}