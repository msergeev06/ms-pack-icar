<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class TsTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_ts';
	}
	public static function getTableTitle() {
		return 'Техосмотр';
	}
	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'TS_ID',
				'ms_icar_repair' => 'TS_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID техосмотра'
			)),
			new Entity\IntegerField ('TS_NUM', array(
				'required' => true,
				'title' => 'Номер ТО'
			)),
			new Entity\IntegerField ('MY_CAR_ID', array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField ('DATE', array(
				'required' => true,
				'title' => 'Дата прохождения ТО'
			)),
			new Entity\IntegerField ('EXECUTORS_ID', array(
				'required' => true,
				'link' => 'ms_icar_executor.ID',
				'title' => 'ID исполнителя работ'
			)),
			new Entity\FloatField ('COST', array(
				'required' => true,
				'title' => 'Стоимость'
			)),
			new Entity\FloatField ('ODO', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Пробег'
			)),
			new Entity\IntegerField ('POINTS_ID', array(
				'required' => true,
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION', array(
				'title' => 'Комментарий'
			))
		);
	}
}