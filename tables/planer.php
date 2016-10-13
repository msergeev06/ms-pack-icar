<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class PlanerTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_planer';
	}
	public static function getTableTitle() {
		return 'Планировщик покупок';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID запланированной покупки'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField('DATE',array(
				'required' => true,
				'title' => 'Дата добавления'
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
			new Entity\StringField('CATALOG_NUMBER',array(
				'title' => 'Каталожный номер'
			)),
			new Entity\IntegerField('POINTS_ID',array(
				'required' => true,
				'link' => 'ms_icar_points.ID',
				'title' => 'Путевая точка'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Примечание'
			))
		);
	}
}