<?php
/**
 * MSergeev\Packages\Icar\Lib\IcarWebixHelper
 * Помощник в работе с библиотекой Webix
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Lib\Loc;

/**
 * Class IcarWebixHelper
 *
 * @static
 * @extends MSergeev\Core\Lib\WebixHelper
 */
class IcarWebixHelper extends CoreLib\WebixHelper
{
	/**
	 * Конструктор. Добавляет используемые пакетом поля для таблицы в помощник
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 */
	public function __construct ()
	{
		$arConstruct = array(
			'ODO' => array(
				'id' => "odo",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_odo'),
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'FUELMARK_NAME' => array(
				'id' => "fuelmark_name",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_fuel_type'),
				'adjust'=>'=true',
				'sort' => 'string'
			),
			'LITER' => array(
				'id' => "liter",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_liters'),
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'LITER_COST' => array(
				'id' => "liter_cost",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_rub').'/'.Loc::getPackMessage('icar','all_l'),
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'LITER_COST_SUM' => array(
				'id' => "sum",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_sum'),
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'FULL' => array(
				'id' => "full",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_full'),
				'adjust'=>'=true'
			),
			'EXPENCE' => array(
				'id' => "expence",
				'tooltip' => '=false',
				'header' => Loc::getPackMessage('icar','all_expence'),
				'adjust'=>'=true',
				'sort' => 'int'
			),
			'POINT' => array(
				'id' => "point_name",
				'tooltip' => Loc::getPackMessage('icar','all_point_name').': #point_name#<br>'
					.Loc::getPackMessage('icar','all_point_type').': #point_type#<br>'
					.Loc::getPackMessage('icar','all_lat').': #point_latitude#<br>'
					.Loc::getPackMessage('icar','all_lon').': #point_longitude#<br>'
					.((CoreLib\Loader::issetPackage('yandexmap'))?Loc::getPackMessage('icar','all_map').':<br>#yandex_map#':''),
				'header' => Loc::getPackMessage('icar','all_point'),
				'adjust'=>'=true',
				'sort' => 'string'
			),
			'INFO' => array(
				'id' => "info",
				'tooltip' => "#comment#",
				'header' => Loc::getPackMessage('icar','all_info'),
				'adjust'=>'=true'
			),
			'TS' => array(
				'id'=>'ts',
				'header'=> Loc::getPackMessage('icar','all_ts'),
				'sort'=>'int',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'NAME' => array(
				'id'=>'name',
				'header'=> Loc::getPackMessage('icar','all_name'),
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'CATALOG_NUMBER' => array(
				'id'=>'catalog_num',
				'header'=> Loc::getPackMessage('icar','all_catalog_num'),
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'EXECUTORS' => array(
				'id'=>'executors_name',
				'header'=> Loc::getPackMessage('icar','all_executor'),
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true',
			),
			'REASON_REPLACEMENT' => array(
				'id'=>'reason_replacement_name',
				'header'=> Loc::getPackMessage('icar','all_executor'),
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true',
			),
			'COST' => array(
				'id'=>'cost',
				'header'=> Loc::getPackMessage('icar','all_cost'),
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
				'header'=>Loc::getPackMessage('icar','all_sum'),
				'sort'=>'float',
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
				'header'=>Loc::getPackMessage('icar','all_number'),
				'sort'=>'int',
				'tooltip'=>'=false',
				'adjust'=>'true'
			),
			'STRING' => array(
				'id'=>'string',
				'header'=> 'STRING',
				'sort'=>'string',
				'tooltip'=>'=false',
				'adjust'=>'=true'
			),
			'BOOL' => array(
				'id' => "bool",
				'tooltip' => '=false',
				'header' => 'BOOL',
				'adjust'=>'=true'
			),
			'INT' => array(
				'id'=>'int',
				'header'=> 'INT',
				'sort'=>'int',
				'tooltip'=>'=false',
				'adjust'=>'true'
			),
		);
		$this->init();
		$this->columnsValues = array_merge($this->columnsValues,$arConstruct);
	}
}