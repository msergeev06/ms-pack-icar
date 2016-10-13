<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class StorageTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_storage';
	}
	public static function getTableTitle() {
		return 'Склады';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_repair_parts' => 'STORAGE_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID склада'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название склада'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код склада'
			))
		);
	}
	public static function getArrayDefaultValues() {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Установлено',
				'CODE' => 'established',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'На складе',
				'CODE' => 'instock',
				'SORT' => 20
			)
		);
	}
}