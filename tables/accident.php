<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;

class AccidentTable extends DataManager
{
	public static function getTableName()
	{
		return 'ms_icar_accident';
	}
	public static function getTableTitle()
	{
		return 'Информация о ДТП';
	}
	public static function getTableLinks()
	{
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'ACCIDENT_ID',
				'ms_icar_repair' => 'ACCIDENT_ID'
			)
		);
	}
	public static function getMap()
	{
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID ДТП'
			)),
			new Entity\IntegerField('MY_CAR_ID',array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField('ACCIDENT_DATE',array(
				'required' => true,
				'title' => 'Дата ДТП'
			)),
			new Entity\FloatField('ODO',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег'
			)),
			new Entity\IntegerField('YOU_INSURANCE_ID',array(
				'link' => 'ms_icar_insurance.ID',
				'title' => 'ID Вашей страховой компании'
			)),
			new Entity\IntegerField('SECOND_INSURANCE_ID',array(
				'link' => 'ms_icar_insurance.ID',
				'title' => 'ID страховой второго участника'
			)),
			new Entity\StringField('DAMAGE_PARTS',array(
				'title' => 'Поврежденные детали'
			)),
			new Entity\IntegerField('EXECUTOR_ID',array(
				'required' => true,
				'link' => 'ms_icar_executor.ID',
				'title' => 'ID исполнителя работ'
			)),
			new Entity\IntegerField('WHO_PAID_ID',array(
				'required' => true,
				'link' => 'ms_icar_who_paid.ID',
				'title' => 'ID плательщика'
			)),
			new Entity\FloatField('INSURANCE_PAID',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Оплачено страховой'
			)),
			new Entity\IntegerField('POINTS_ID',array(
				'required' => true,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Краткое описание произошедшего'
			))
		);
	}
}