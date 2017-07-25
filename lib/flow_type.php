<?php
/**
 * MSergeev\Packages\Icar\Lib\FlowType
 * Типы дополнительных расходов
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;

/**
 * Class FlowType
 *
 * @static
 */
class FlowType
{
	/**
	 * @var array Массив получаемых из таблицы полей
	 */
	protected static $arTableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'CODE'
	);

	/**
	 * Возвращает тег <select> со списком типов прочих расходов
	 *
	 * @api
	 *
	 * @param string $strBoxName        Свойство name тега <select>
	 * @param string $strSelectedVal    Значение по-умолчанию
	 * @param string $strDetText        Текст пустого значения по-умолчанию
	 * @param string $field1            Прочие свойства тега <select>
	 *
	 * @uses FlowTypeTable::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectFlowTypeList ($strBoxName, $strSelectedVal = "null", $strDetText='--- Выбрать ---', $field1="class=\"flowtypeselect\"")
	{
		$arRes = Tables\FlowTypeTable::getList(array(
			'select' => array('ID'=>'VALUE','NAME'),
			'filter' => array('ACTIVE'=>true),
			'order' => array('SORT'=>'ASC')
		));

		return SelectBox($strBoxName, $arRes, $strDetText, $strSelectedVal, $field1);
	}

	/**
	 * Возвращает код типа прочего расхода по его ID
	 *
	 * @api
	 *
	 * @param int $flowTypeID ID типа прочего расхода
	 *
	 * @uses FlowTypeTable::getList
	 *
	 * @throw MSergeev\Core\Exception\ArgumentNullException Если ID типа не указан
	 *
	 * @return string|bool
	 */
	public static function getCodeById ($flowTypeID=null)
	{
		try
		{
			if (is_null($flowTypeID))
			{
				throw new Exception\ArgumentNullException('$flowTypeID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}

		$arRes = Tables\FlowTypeTable::getList(array(
			'select'    => array('CODE'),
			'filter'    => array('ID'=>$flowTypeID),
			'limit'     => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if (isset($arRes['CODE']))
		{
			return $arRes['CODE'];
		}
		else
		{
			return false;
		}
	}
}