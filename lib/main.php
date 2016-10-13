<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Exception\ArgumentNullException;
use MSergeev\Core\Lib as CoreLib;

class Main
{
	/**
	 * Возвращает отформатированное значение Денег
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