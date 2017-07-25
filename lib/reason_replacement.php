<?php
/**
 * MSergeev\Packages\Icar\Lib\ReasonReplacement
 * Причины замены
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\ReasonReplacementTable;

/**
 * Class ReasonReplacement
 */
class ReasonReplacement
{
	/**
	 * Возвращает тег <select> со списком Причин замены
	 *
	 * @api
	 *
	 * @param string $strBoxName        Свойство name тега <select>
	 * @param string $strDetText        Текст пустого значения
	 * @param string $strSelectedVal    Значение по-умолчанию
	 * @param string $field1            Дополнительные свойства тега <select>
	 *
	 * @uses ReasonReplacementTable::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectReasonReplacementList ($strBoxName, $strDetText='--- Выбрать ---', $strSelectedVal = "null", $field1="class=\"reasonreplacementselect\"")
	{
		$arRes = ReasonReplacementTable::getList(array(
			'select' => array('ID'=>'VALUE','NAME'),
			'filter' => array('ACTIVE'=>true),
			'order' => array('SORT'=>'ASC')
		));

		return SelectBox($strBoxName, $arRes, $strDetText, $strSelectedVal, $field1);
	}

	/**
	 * Возвращает код Причины замены по его ID
	 *
	 * @api
	 *
	 * @param int $reasonID ID причины замены
	 *
	 * @uses ReasonReplacementTable::getList
	 *
	 * @return bool|string
	 */
	public static function getCodeById ($reasonID=0)
	{
		if (intval($reasonID)>0)
		{
			$arRes = ReasonReplacementTable::getList(array(
				'select' => array('CODE'),
				'filter' => array('ID'=>intval($reasonID)),
				'limit' => 1
			));
			if ($arRes && isset($arRes[0]))
			{
				$arRes = $arRes[0];
			}
			if ($arRes)
			{
				return $arRes['CODE'];
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}

}