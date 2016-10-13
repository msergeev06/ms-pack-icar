<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class PointsTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_points';
	}
	public static function getTableTitle() {
		return 'Путевые точки';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_fuel' => 'POINTS_ID',
				'ms_icar_repair_parts' => 'POINTS_ID',
				'ms_icar_accident' => 'POINTS_ID',
				'ms_icar_ts' => 'POINTS_ID',
				'ms_icar_routs' => array('START_POINTS_ID','END_POINTS_ID'),
				'ms_icar_other_expense' => 'POINTS_ID',
				'ms_icar_optional_equip' => 'POINTS_ID',
				'ms_icar_planer' => 'POINTS_ID',
				'ms_icar_repair' => 'POINTS_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID путевой точки'
			)),
			TableHelper::activeField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название путевой точки'
			)),
			new Entity\IntegerField ('POINT_TYPES_ID', array(
				'required' => true,
				'link' => 'ms_icar_point_types.ID',
				'title' => 'ID типа путевой точки'
			)),
			new Entity\StringField ('ADDRESS', array(
				'title' => 'Адрес путевой точки'
			)),
			new Entity\StringField ('LATITUDE', array(
				'title' => 'Широта'
			)),
			new Entity\StringField ('LONGITUDE', array(
				'title' => 'Долгота'
			)),
			new Entity\IntegerField ('POPULAR', array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Популярность точки'
			))
		);
	}
}