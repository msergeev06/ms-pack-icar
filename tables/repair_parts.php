<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;

class RepairPartsTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_repair_parts';
	}
	public static function getTableTitle() {
		return 'Запчасти';
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID запчасти'
			)),
			new Entity\IntegerField ('MY_CAR_ID', array(
				'required' => true,
				'link' => 'ms_icar_my_car.ID',
				'title' => 'ID автомобиля'
			)),
			new Entity\DateField ('DATE', array(
				'title' => 'Дата покупки'
			)),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название запчасти'
			)),
			new Entity\IntegerField ('STORAGE_ID', array(
				'required' => true,
				'link' => 'ms_icar_storage.ID',
				'title' => 'ID места хранения'
			)),
			new Entity\StringField ('CATALOG_NUMBER', array(
				'title' => 'Каталожный номер'
			)),
			new Entity\FloatField ('NUMBER', array(
				'required' => true,
				'default_value' => 1,
				'title' => 'Количество запчастей'
			)),
			new Entity\FloatField ('COST', array(
				'title' => 'Цена'
			)),
			new Entity\IntegerField ('REASON_REPLACEMENT_ID', array(
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
			new Entity\IntegerField('REPAIR_ID',array(
				'required' => true,
				'default_value' => 0,
				'link' => 'ms_icar_repair.ID',
				'title' => 'ID записи о ремонте'
			)),
			new Entity\IntegerField ('WHO_PAID_ID', array(
				'link' => 'ms_icar_who_paid.ID',
				'title' => 'ID плательщика'
			)),
			new Entity\FloatField ('ODO', array(
				'title' => 'Пробег'
			)),
			new Entity\IntegerField ('POINTS_ID', array(
				'link' => 'ms_icar_points.ID',
				'title' => 'ID путевой точки'
			)),
			new Entity\TextField('DESCRIPTION', array(
				'title' => 'Комментарий'
			))
		);
	}
}