<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class IncomeTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_income';
	}
	public static function getTableTitle() {
		return 'Информация о доходах';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID дохода'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField('DATE',array(
				'required' => true,
				'title' => 'Дата получения дохода'
			)),
			new Entity\FloatField('SUM',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Сумма'
			)),
			new Entity\StringField('FROM',array(
				'required' => true,
				'title' => 'Откуда получен доход'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Примечание'
			)),
		);
	}
}