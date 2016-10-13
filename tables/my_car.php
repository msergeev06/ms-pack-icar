<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class MyCarTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_my_car';
	}
	public static function getTableTitle() {
		return 'Мои автомобили';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array (
				'ms_icar_fuel' => 'MY_CAR_ID',
				'ms_icar_odo' => 'MY_CAR_ID',
				'ms_icar_repair_parts' => 'MY_CAR_ID',
				'ms_icar_accident' => 'MY_CAR_ID',
				'ms_icar_ts' => 'MY_CAR_ID',
				'ms_icar_routs' => 'MY_CAR_ID',
				'ms_icar_other_expense' => 'MY_CAR_ID',
				'ms_icar_optional_equip' => 'MY_CAR_ID',
				'ms_icar_credit' => 'MY_CAR_ID',
				'ms_icar_planer' => 'MY_CAR_ID',
				'ms_icar_income' => 'MY_CAR_ID',
				'ms_icar_repair' => 'MY_CAR_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID автомобиля'
			)),
			new Entity\BooleanField('ACTIVE',array(
				'required' => true,
				'default_value' => true,
				'title' => 'Активность'
			)),
			new Entity\IntegerField('SORT',array(
				'required' => true,
				'default_value' => 500,
				'title' => 'Сортировка'
			)),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название автомобиля'
			)),
			new Entity\IntegerField ('CAR_BRANDS_ID', array(
				'link' => 'ms_icar_car_brands.ID',
				'title' => 'ID бренда автомобиля'
			)),
			new Entity\IntegerField ('CAR_MODEL_ID', array(
				'link' => 'ms_icar_car_model.ID',
				'title' => 'ID модели автомобиля'
			)),
			new Entity\IntegerField ('YEAR', array(
				'title' => 'Год выпуска'
			)),
			new Entity\StringField ('VIN', array(
				'size' => 20,
				'title' => 'VIN код'
			)),
			new Entity\StringField ('CAR_NUMBER', array(
				'size' => 20,
				'title' => 'Гос. номер'
			)),
			new Entity\FloatField ('ENGINE_CAPACITY', array(
				'scale' => 1,
				'title' => 'Объем двигателя'
			)),
			new Entity\IntegerField ('CAR_GEARBOX_ID', array(
				'link' => 'ms_icar_car_gearbox.ID',
				'title' => 'ID типа КПП'
			)),
			new Entity\IntegerField ('CAR_BODY_ID', array(
				'link' => 'ms_icar_car_body.ID',
				'title' => 'ID типа кузова'
			)),
			new Entity\FloatField ('INTERVAL_TS', array(
				'title' => 'Интервал ТО'
			)),
			new Entity\FloatField ('COST', array(
				'title' => 'Стоимость автомобиля'
			)),
			new Entity\FloatField ('MILEAGE', array(
				'title' => 'Пробег при покупке'
			)),
			new Entity\BooleanField ('CREDIT', array(
				'required' => true,
				'default_value' => false,
				'title' => 'Машина куплена в кредит'
			)),
			new Entity\FloatField ('CREDIT_COST', array(
				'title' => 'Сумма кредита'
			)),
			new Entity\DateField ('DATE_OSAGO_END', array(
				'title' => 'Дата окончания ОСАГО'
			)),
			new Entity\DateField ('DATE_GTO_END', array(
				'title' => 'Дата окончания ГТО'
			)),
			new Entity\BooleanField ('DEFAULT', array(
				'required' => true,
				'default_value' => false,
				'title' => 'Автомобиль по-умолчанию'
			))
		);
	}
}