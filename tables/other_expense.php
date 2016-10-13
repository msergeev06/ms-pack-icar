<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class OtherExpenseTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_other_expense';
	}
	public static function getTableTitle() {
		return 'Прочие расходы';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID прочего расхода'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField('DATE',array(
				'required' => true,
				'title' => 'Дата расхода'
			)),
			new Entity\FloatField('SUM',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Сумма'
			)),
			new Entity\IntegerField('FLOW_TYPE_ID',array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_flow_type.ID',
				'title' => 'Тип расходв'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название'
			)),
			new Entity\FloatField('NUM',array(
				'required' => true,
				'default_value' => 1,
				'title' => 'Количество'
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
			))
		);
	}
}