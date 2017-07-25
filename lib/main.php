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

use MSergeev\Core\Lib as CoreLib;

/**
 * Class Main
 *
 * @static
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
	 * @uses MSergeev\Core\Lib\Options::getOptionStr
	 * @uses MSergeev\Core\Lib\Options::getOptionInt
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMoney ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			if (!$currency = CoreLib\Options::getOptionStr('icar_money_currency'))
			{
				$currency = 'rub';
			}
			if ($first = CoreLib\Options::getOptionInt('icar_money_first'))
			{
				$strMoney = CoreLib\Loc::getPackMessage('icar','all_'.$currency);
				$strMoney .= "&nbsp;".str_replace(' ', "&nbsp;", number_format($value,2,','," "));
			}
			else
			{
				$strMoney = str_replace(' ', "&nbsp;", number_format($value,2,','," "));
				$strMoney .= "&nbsp;".CoreLib\Loc::getPackMessage('icar','all_'.$currency);
			}

			return $strMoney;
		}
	}

	/**
	 * Возвращает отформатированное значение Денег/л.
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Options::getOptionStr
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMoneyPerLiter ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			if (!$currency = CoreLib\Options::getOptionStr('icar_money_currency'))
			{
				$currency = 'rub';
			}
			$strMoney = str_replace(' ', "&nbsp;", number_format($value,2,','," "));
			$strMoney .= "&nbsp;".CoreLib\Loc::getPackMessage('icar','all_'.$currency)."/"
				.CoreLib\Loc::getPackMessage('icar','all_l');

			return $strMoney;
		}
	}

	/**
	 * Возвращает отформатированное значение Денег/км.
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Options::getOptionStr
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMoneyPerKm ($value, $input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			if (!$currency = CoreLib\Options::getOptionStr('icar_money_currency'))
			{
				$currency = 'rub';
			}
			$strMoney = str_replace(' ', "&nbsp;", number_format($value,2,','," "));
			$strMoney .= "&nbsp;".CoreLib\Loc::getPackMessage('icar','all_'.$currency)."/"
				.CoreLib\Loc::getPackMessage('icar','all_km');

			return $strMoney;
		}
	}

	/**
	 * Возвращает отформатированное значение Денег/день
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Options::getOptionStr
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMoneyPerDay ($value, $input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			if (!$currency = CoreLib\Options::getOptionStr('icar_money_currency'))
			{
				$currency = 'rub';
			}
			$strMoney = str_replace(' ', "&nbsp;", number_format($value,2,','," "));
			$strMoney .= "&nbsp;".CoreLib\Loc::getPackMessage('icar','all_'.$currency)."/"
				.CoreLib\Loc::getPackMessage('icar','all_day');

			return $strMoney;
		}
	}

	/**
	 * Возвращает отформатированное значение Денег/месяц
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Options::getOptionStr
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMoneyPerMonth ($value, $input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			if (!$currency = CoreLib\Options::getOptionStr('icar_money_currency'))
			{
				$currency = 'rub';
			}
			$strMoney = str_replace(' ', "&nbsp;", number_format($value,2,','," "));
			$strMoney .= "&nbsp;".CoreLib\Loc::getPackMessage('icar','all_'.$currency)."/"
				.CoreLib\Loc::getPackMessage('icar','all_month');

			return $strMoney;
		}
	}

	/**
	 * Возвращает отформатированное значение Пробега
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMileage ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,1,'.','');
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,1,','," "))."&nbsp;"
				.CoreLib\Loc::getPackMessage('icar','all_km');
		}
	}

	/**
	 * Возвращает отформатированное значение Пробега/день
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMileagePerDay ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,1,'.','');
		}
		else
		{
			return str_replace(' ',"&nbsp;",number_format($value,1,',',' '))."&nbsp;"
				.CoreLib\Loc::getPackMessage('icar','all_km').'/'
				.CoreLib\Loc::getPackMessage('icar','all_day');
		}
	}

	/**
	 * Возвращает отформатированное значение Пробега/месяц
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMileagePerMonth ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,1,'.','');
		}
		else
		{
			return str_replace(' ',"&nbsp;",number_format($value,1,',',' '))."&nbsp;"
			.CoreLib\Loc::getPackMessage('icar','all_km').'/'
			.CoreLib\Loc::getPackMessage('icar','all_month');
		}
	}

	/**
	 * Возвращает отформатированное значение Литров
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatLiter ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,2,','," "))."&nbsp;"
				.CoreLib\Loc::getPackMessage('icar','all_l');
		}
	}

	/**
	 * Возвращает отформатированное значение Расхода среднего
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatAverageLiter100Km ($value,$input=false)
	{
		if ($input)
		{
			return number_format($value,2,'.','');
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,2,','," "))."&nbsp;"
				.CoreLib\Loc::getPackMessage('icar','all_l')."/100"
				.CoreLib\Loc::getPackMessage('icar','all_km');
		}
	}

	/**
	 * Возвращает отформатированное значение дней
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Tools::sayRusRight
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatDays ($value,$input=false)
	{
		if ($input)
		{
			return intval($value);
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,0,',',' '))."&nbsp;"
				.CoreLib\Tools::sayRusRight(
					$value,
					CoreLib\Loc::getPackMessage('icar','all_day'),
					CoreLib\Loc::getPackMessage('icar','all_days1'),
					CoreLib\Loc::getPackMessage('icar','all_days2')
				);
		}
	}

	/**
	 * Возвращает отформатированное значение месяцев
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Tools::sayRusRight
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function formatMonths ($value,$input=false)
	{
		if ($input)
		{
			return intval($value);
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,0,',',' '))."&nbsp;"
			.CoreLib\Tools::sayRusRight(
				$value,
				CoreLib\Loc::getPackMessage('icar','all_month'),
				CoreLib\Loc::getPackMessage('icar','all_month1'),
				CoreLib\Loc::getPackMessage('icar','all_month2')
			);
		}
	}

	/**
	 * Возвращает отформатированное значение лет
	 *
	 * @api
	 *
	 * @param float     $value      Число
	 * @param bool      $input      Флаг использования в <input>
	 *
	 * @uses MSergeev\Core\Lib\Tools::sayRusRight
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function yearsFormat ($value,$input=false)
	{
		if ($input)
		{
			return intval($value);
		}
		else
		{
			return str_replace(' ', "&nbsp;", number_format($value,0,',',' '))."&nbsp;"
			.CoreLib\Tools::sayRusRight(
				$value,
				CoreLib\Loc::getPackMessage('icar','all_year'),
				CoreLib\Loc::getPackMessage('icar','all_year1'),
				CoreLib\Loc::getPackMessage('icar','all_year2')
			);
		}
	}

}