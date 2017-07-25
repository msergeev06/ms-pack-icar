<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class FuelTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_fuel';
	}
	public static function getTableTitle() {
		return 'Заправки';
	}
	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID заправки'
			)),
			new Entity\IntegerField ('MY_CAR_ID', array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField ('DATE', array(
				'required' => true,
				'title' => 'Дата заправки'
			)),
			new Entity\FloatField ('ODO', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег'
			)),
			new Entity\IntegerField ('FUELMARK_ID', array(
				'required' => true,
				'link' => 'ms_icar_fuelmark.ID',
				'title' => 'ID типа топлива'
			)),
			new Entity\FloatField ('LITER', array(
				'required' => true,
				'title' => 'Количество литров'
			)),
			new Entity\FloatField ('LITER_COST', array(
				'required' => true,
				'title' => 'Цена литра'
			)),
			new Entity\FloatField ('SUM', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::multiplication",
					'array_column' => 'LITER,LITER_COST'
				),
				'title' => 'Сумма'
			)),
			new Entity\BooleanField ('FULL', array(
				'required' => true,
				'default_value' => false,
				'title' => 'Полный бак'
			)),
			new Entity\BooleanField('MISSING',array(
				'required' => true,
				'default_value' => false,
				'title' => 'Была ли пропущена предыдущая заправка'
			)),
			new Entity\IntegerField ('POINTS_ID', array(
				'required' => true,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION', array(
				'title' => 'Комментарий'
			)),
			new Entity\IntegerField('CHECK',array(
				'size' => 18,
				'link' => 'ms_core_file.ID',
				'title' => 'Фотография чека'
			)),
			new Entity\FloatField ('EXPENCE', array(
				'title' => 'Расход'
			)),
			new Entity\FloatField('DISTANCE',array(
				'title' => 'Расстояние между заправками'
			)),
			new Entity\FloatField('COST_KM',array(
				'title' => 'Стоимость за км.'
			)),
			new Entity\FloatField('TANK_LITER',array(
				'title' => 'Литров в баке после заправки'
			)),
			new Entity\FloatField('TANK_COST',array(
				'title' => 'Цена литра бензина в баке'
			))
		);
	}
}