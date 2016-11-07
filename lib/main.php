<?php
/**
 * MSergeev\Packages\Icar\Lib\Main
 * Главный класс. Содержит общие функции
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Exception\ArgumentNullException;
use MSergeev\Core\Lib as CoreLib;

/**
 * Class Main
 * @package MSergeev\Packages\Icar\Lib
 */
class Main
{
	/**
	 * Возвращает отформатированное значение Денег
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @return string
	 */
	public static function moneyFormat ($value,$input=false)
	{
		if ($input)
			return number_format($value,2,'.','');
		else
			return str_replace(' ', "&nbsp;", number_format($value,2,','," "));
	}

	/**
	 * Возвращает отформатированное значение Пробега
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @return string
	 */
	public static function mileageFormat ($value,$input=false)
	{
		if ($input)
			return number_format($value,1,'.','');
		else
			return str_replace(' ', "&nbsp;", number_format($value,1,','," "));
	}

	/**
	 * Возвращает отформатированное значение Литров
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @return string
	 */
	public static function literFormat ($value,$input=false)
	{
		if ($input)
			return number_format($value,2,'.','');
		else
			return str_replace(' ', "&nbsp;", number_format($value,2,','," "));
	}

	/**
	 * Возвращает отформатированное значение Расхода среднего
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @return string
	 */
	public static function averageFormat ($value,$input=false)
	{
		if ($input)
			return number_format($value,2,'.','');
		else
			return str_replace(' ', "&nbsp;", number_format($value,2,','," "));
	}

}