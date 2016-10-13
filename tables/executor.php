<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class ExecutorTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_executor';
	}
	public static function getTableTitle() {
		return 'Исполнители работ';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_accident' => 'EXECUTOR_ID',
				'ms_icar_ts' => 'EXECUTOR_ID',
				'ms_icar_repair' => 'EXECUTOR_ID'
			)
		);
	}
	public static function getMap() {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID исполнителя работ'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'required' => true,
				'title' => 'Название исполнителя работ'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код исполнителя работ'
			))
		);
	}
	public static function getArrayDefaultValues() {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Не дилер',
				'CODE' => 'nodiler',
				'SORT' => 10,
			),
			array(
				'ID' => 2,
				'NAME' => 'Дилер',
				'CODE' => 'diler',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'СТО',
				'CODE' => 'sto',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Делал сам',
				'CODE' => 'myself',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Частный сеовис',
				'CODE' => 'service',
				'SORT' => 50
			)
		);
	}
}