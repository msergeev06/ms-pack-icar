<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class CreditTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_credit';
	}
	public static function getTableTitle() {
		return 'Расходы на кредит';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID расхода на кредит'
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
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Примечание'
			))
		);
	}
}