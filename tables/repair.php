<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Exception;
use MSergeev\Core\Lib\DataManager;

class RepairTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_icar_repair';
	}

	public static function getTableTitle ()
	{
		return 'Затраты на ремонт';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'REPAIR_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID записи'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField ('DATE', array(
				'required' => true,
				'title' => 'Дата ремонта'
			)),
			new Entity\FloatField ('COST', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Стоимость'
			)),
			new Entity\IntegerField ('EXECUTOR_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_executor.ID',
				'title' => 'ID исполнителя работ'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'default_value' => 'Ремонт',
				'title' => 'Наименование работ'
			)),
			new Entity\FloatField ('ODO', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег'
			)),
			new Entity\IntegerField ('REASON_REPLACEMENT_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_reason_replacement.ID',
				'title' => 'ID причины замены'
			)),
			new Entity\IntegerField ('TS_ID', array(
				'link' => 'ms_icar_ts.ID',
				'required' => true,
				'default_value' => 0,
				'title' => 'ID причина замены ТО'
			)),
			new Entity\IntegerField ('ACCIDENT_ID', array(
				'link' => 'ms_icar_accident.ID',
				'required' => true,
				'default_value' => 0,
				'title' => 'ID причина замены ДТП'
			)),
			new Entity\IntegerField ('WHO_PAID_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_who_paid.ID',
				'title' => 'ID плательщика'
			)),
			new Entity\IntegerField ('POINTS_ID', array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION', array(
				'title' => 'Комментарий'
			))
		);
	}
}