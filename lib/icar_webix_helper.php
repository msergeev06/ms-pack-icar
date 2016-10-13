<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;

class IcarWebixHelper extends CoreLib\WebixHelper
{
	public function __construct ()
	{
		$arConstruct = array(
			'ODO' => array(
				'id' => "odo",
				'tooltip' => '=false',
				'header' => "Пробег",
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'FUELMARK_NAME' => array(
				'id' => "fuelmark_name",
				'tooltip' => '=false',
				'header' => "Тип топлива",
				'adjust'=>'=true',
				'sort' => 'string'
			),
			'LITER' => array(
				'id' => "liter",
				'tooltip' => '=false',
				'header' => "Литров",
				'adjust'=>'=true',
				'sort' => 'int',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
					})'
			),
			'LITER_COST' => array(
				'id' => "liter_cost",
				'tooltip' => '=false',
				'header' => "р/л.",
				'adjust'=>'=true',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
					})',
				'sort' => 'int'
			),
			'LITER_COST_SUM' => array(
				'id' => "sum",
				'tooltip' => '=false',
				'header' => "Сумма",
				'adjust'=>'=true',
				'sort' => 'int',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
				})'
			),
			'FULL' => array(
				'id' => "full",
				'tooltip' => '=false',
				'header' => "Полный",
				'adjust'=>'=true'
			),
			'EXPENCE' => array(
				'id' => "expence",
				'tooltip' => '=false',
				'header' => "Расход",
				'adjust'=>'=true',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
					})',
				'sort' => 'int'
			),
			'POINT' => array(
				'id' => "point_name",
				'tooltip' => 'Имя точки: #point_name#<br>'
					.'Тип точки: #point_type#<br>'
					.'Широта: #point_latitude#<br>'
					.'Долгота: #point_longitude#',
				'header' => "Точка",
				'adjust'=>'=true',
				'sort' => 'string'
			),
			'INFO' => array(
				'id' => "info",
				'tooltip' => "#comment#",
				'header' => "Инфо",
				'adjust'=>'=true'
			),
			'TS' => array(
				'id'=>'ts',
				'header'=>'ТО',
				'sort'=>'int',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'NAME' => array(
				'id'=>'name',
				'header'=>'Название',
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'CATALOG_NUMBER' => array(
				'id'=>'catalog_num',
				'header'=>'Каталог №',
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'EXECUTORS' => array(
				'id'=>'executors_name',
				'header'=>'Исполнитель работ',
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true',
			),
			'COST' => array(
				'id'=>'cost',
				'header'=>'Стоимость',
				'sort'=>'int',
				'tooltip'=>'=false',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
					})',
				'adjust'=>'true'
			),
			'SUM' => array(
				'id'=>'sum',
				'header'=>'Сумма',
				'sort'=>'int',
				'tooltip'=>'=false',
				'format' => '=webix.Number.numToStr({
						groupDelimiter:" ",
						groupSize:3,
						decimalDelimiter:",",
						decimalSize:2
					})',
				'adjust'=>'true'
			),
			'NUMBER' => array(
				'id'=>'number',
				'header'=>'Количество',
				'sort'=>'int',
				'tooltip'=>'=false',
				'adjust'=>'true'
			)
		);
		$this->init();
		$this->columnsValues = array_merge($this->columnsValues,$arConstruct);
	}
}