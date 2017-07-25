<?php

namespace MSergeev\Packages\Icar\Lib;

class Statistics
{
	/**
	 * Расходы по автомобилю
	*/

	/**
	 * Возвращает отформатированную общую сумму расходов по автомобилю за все время
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int   $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param array $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Statistics::getCarTotalCosts
	 * @uses Main::formatMoney
	 *
	 * @return string
	 */
	public static function getCarTotalCostsFormatted ($carID=null, $arPlus=array(), $arMinus=array())
	{
		return Main::formatMoney(self::getCarTotalCosts($carID, $arPlus, $arMinus));
	}

	/**
	 * Возвращает общую сумму расходов по автомобилю за все время
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int   $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param array $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Fuel::getTotalCosts
	 * @uses OptionalEquip::getTotalCosts
	 * @uses Repair::getTotalCosts
	 * @uses RepairParts::getTotalCosts
	 * @uses OtherExpense::getTotalCosts
	 *
	 * @return float
	 */
	public static function getCarTotalCosts ($carID=null, $arPlus=array(), $arMinus=array())
	{
		$sum = 0;
		if (!is_array($arPlus))
		{
			$arPlus=array();
		}
		if (!is_array($arMinus))
		{
			$arMinus=array();
		}
		if (!empty($arPlus))
		{
			if (in_array('fuel',$arPlus))
			{
				$sum += Fuel::getTotalCosts($carID);
			}
			if (in_array('optional',$arPlus))
			{
				$sum += OptionalEquip::getTotalCosts($carID);
			}
			if (in_array('repair',$arPlus))
			{
				$sum += Repair::getTotalCosts($carID);
			}
			if (in_array('parts',$arPlus))
			{
				$sum += RepairParts::getTotalCosts($carID);
			}
			if (in_array('other',$arPlus))
			{
				$sum += OtherExpense::getTotalCosts($carID);
			}
		}
		elseif (!empty($arMinus))
		{
			if (!in_array('fuel',$arMinus))
			{
				$sum += Fuel::getTotalCosts($carID);
			}
			if (!in_array('optional',$arMinus))
			{
				$sum += OptionalEquip::getTotalCosts($carID);
			}
			if (!in_array('repair',$arMinus))
			{
				$sum += Repair::getTotalCosts($carID);
			}
			if (!in_array('parts',$arMinus))
			{
				$sum += RepairParts::getTotalCosts($carID);
			}
			if (!in_array('other',$arMinus))
			{
				$sum += OtherExpense::getTotalCosts($carID);
			}
		}
		else
		{
			$sum = Fuel::getTotalCosts($carID);
			$sum += OptionalEquip::getTotalCosts($carID);
			$sum += Repair::getTotalCosts($carID);
			$sum += RepairParts::getTotalCosts($carID);
			$sum += OtherExpense::getTotalCosts($carID);
		}

		return floatval($sum);
	}

	/**
	 * Возвращает отформатированную общую сумму расходов по автомобилю за Год
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int|null  $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now        true - текущий год, false - прошлый год
	 * @param array     $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array     $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Main::formatMoney
	 * @uses Statistics::getCarTotalCostsYear
	 *
	 * @return string
	 */
	public static function getCarTotalCostsYearFormatted ($carID=null, $now=true, $arPlus=array(), $arMinus=array())
	{
		return Main::formatMoney(self::getCarTotalCostsYear($carID, $now, $arPlus, $arMinus));
	}

	/**
	 * Возвращает общую сумму расходов по автомобилю за Год
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int|null  $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now        true - текущий год, false - прошлый год
	 * @param array     $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array     $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Fuel::getTotalCostsYear
	 * @uses OptionalEquip::getTotalCostsYear
	 * @uses Repair::getTotalCostsYear
	 * @uses RepairParts::getTotalCostsYear
	 * @uses OtherExpense::getTotalCostsYear
	 *
	 * @return float
	 */
	public static function getCarTotalCostsYear ($carID=null, $now=true, $arPlus=array(), $arMinus=array())
	{
		$sum = 0;
		if (!is_array($arPlus))
		{
			$arPlus=array();
		}
		if (!is_array($arMinus))
		{
			$arMinus=array();
		}
		if (!empty($arPlus))
		{
			if (in_array('fuel',$arPlus))
			{
				$sum += Fuel::getTotalCostsYear ($carID, $now);
			}
			if (in_array('optional',$arPlus))
			{
				$sum += OptionalEquip::getTotalCostsYear ($carID, $now);
			}
			if (in_array('repair',$arPlus))
			{
				$sum += Repair::getTotalCostsYear ($carID, $now);
			}
			if (in_array('parts',$arPlus))
			{
				$sum += RepairParts::getTotalCostsYear ($carID, $now);
			}
			if (in_array('other',$arPlus))
			{
				$sum += OtherExpense::getTotalCostsYear ($carID, $now);
			}
		}
		elseif (!empty($arMinus))
		{
			if (!in_array('fuel',$arMinus))
			{
				$sum += Fuel::getTotalCostsYear ($carID, $now);
			}
			if (!in_array('optional',$arMinus))
			{
				$sum += OptionalEquip::getTotalCostsYear ($carID, $now);
			}
			if (!in_array('repair',$arMinus))
			{
				$sum += Repair::getTotalCostsYear ($carID, $now);
			}
			if (!in_array('parts',$arMinus))
			{
				$sum += RepairParts::getTotalCostsYear ($carID, $now);
			}
			if (!in_array('other',$arMinus))
			{
				$sum += OtherExpense::getTotalCostsYear ($carID, $now);
			}
		}
		else
		{
			$sum += Fuel::getTotalCostsYear ($carID, $now);
			$sum += OptionalEquip::getTotalCostsYear ($carID, $now);
			$sum += Repair::getTotalCostsYear ($carID, $now);
			$sum += RepairParts::getTotalCostsYear ($carID, $now);
			$sum += OtherExpense::getTotalCostsYear ($carID, $now);
		}

		return floatval($sum);
	}

	/**
	 * Возвращает отформатированную общую сумму расходов по автомобилю за Месяц
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int|null  $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now        true - текущий месяц, false - прошлый месяц
	 * @param array     $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array     $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Main::formatMoney
	 * @uses Statistics::getCarTotalCostsMonth
	 *
	 * @return string
	 */
	public static function getCarTotalCostsMonthFormatted ($carID=null, $now=true, $arPlus=array(), $arMinus=array())
	{
		return Main::formatMoney(self::getCarTotalCostsMonth($carID, $now, $arPlus, $arMinus));
	}

	/**
	 * Возвращает общую сумму расходов по автомобилю за Месяц
	 *
	 * Если указан массив $arPlus, то суммируются только указанные типы расходов
	 * Если указан массив $arMinus, то суммируются все види расходов, кроме указанных
	 * Если указан и массив $arPlus и $arMinus - учитывается только массив $arPlus
	 * Если не $arPlus, не $arMinus не указаны - суммируются все виды расходов
	 *
	 * @param int|null  $carID      ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now        true - текущий месяц, false - прошлый месяц
	 * @param array     $arPlus     Массив типов расходов, которые необходимо считать
	 * @param array     $arMinus    Массив типов расходов, которые не нужно считать
	 *
	 * @uses Fuel::getTotalCostsMonth
	 * @uses OptionalEquip::getTotalCostsMonth
	 * @uses Repair::getTotalCostsMonth
	 * @uses RepairParts::getTotalCostsMonth
	 * @uses OtherExpense::getTotalCostsMonth
	 *
	 * @return float
	 */
	public static function getCarTotalCostsMonth ($carID=null, $now=true, $arPlus=array(), $arMinus=array())
	{
		$sum = 0;
		if (!is_array($arPlus))
		{
			$arPlus=array();
		}
		if (!is_array($arMinus))
		{
			$arMinus=array();
		}
		if (!empty($arPlus))
		{
			if (in_array('fuel',$arPlus))
			{
				$sum += Fuel::getTotalCostsMonth ($carID, $now);
			}
			if (in_array('optional',$arPlus))
			{
				$sum += OptionalEquip::getTotalCostsMonth ($carID, $now);
			}
			if (in_array('repair',$arPlus))
			{
				$sum += Repair::getTotalCostsMonth ($carID, $now);
			}
			if (in_array('parts',$arPlus))
			{
				$sum += RepairParts::getTotalCostsMonth ($carID, $now);
			}
			if (in_array('other',$arPlus))
			{
				$sum += OtherExpense::getTotalCostsMonth ($carID, $now);
			}
		}
		elseif (!empty($arMinus))
		{
			if (!in_array('fuel',$arMinus))
			{
				$sum += Fuel::getTotalCostsMonth ($carID, $now);
			}
			if (!in_array('optional',$arMinus))
			{
				$sum += OptionalEquip::getTotalCostsMonth ($carID, $now);
			}
			if (!in_array('repair',$arMinus))
			{
				$sum += Repair::getTotalCostsMonth ($carID, $now);
			}
			if (!in_array('parts',$arMinus))
			{
				$sum += RepairParts::getTotalCostsMonth ($carID, $now);
			}
			if (!in_array('other',$arMinus))
			{
				$sum += OtherExpense::getTotalCostsMonth ($carID, $now);
			}
		}
		else
		{
			$sum += Fuel::getTotalCostsMonth ($carID, $now);
			$sum += OptionalEquip::getTotalCostsMonth ($carID, $now);
			$sum += Repair::getTotalCostsMonth ($carID, $now);
			$sum += RepairParts::getTotalCostsMonth ($carID, $now);
			$sum += OtherExpense::getTotalCostsMonth ($carID, $now);
		}

		return floatval($sum);
	}

	/**
	 * Возвращает отформатированную среднюю стоимость в день
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getOwnershipDays
	 * @uses Statistics::getCarTotalCosts
	 * @uses Main::formatMoneyPerDay
	 *
	 * @return mixed|string
	 */
	public static function getAverageCostPerDayFormatted ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$days = MyCar::getOwnershipDays($carID);
		if ($days>0)
		{
			$cost = self::getCarTotalCosts($carID);

			return Main::formatMoneyPerDay(floatval($cost/$days));
		}

		return Main::formatMoneyPerDay(0);
	}

	/**
	 * Возвращает отформатированную среднюю стоимость в месяц
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getOwnershipDays
	 * @uses Statistics::getCarTotalCosts
	 * @uses Main::formatMoneyPerMonth
	 *
	 * @return mixed|string
	 */
	public static function getAverageCostPerMonthFormatted ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$days = MyCar::getOwnershipDays($carID);
		$month = $days / 30;
		if ($month>0)
		{
			$cost = Statistics::getCarTotalCosts($carID);
			return Main::formatMoneyPerMonth($cost/$month);
		}

		return Main::formatMoneyPerMonth(0);
	}

	/**
	 * Возвращает отформатированное значение максимального чека (расхода)
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMoney
	 * @uses Fuel::getMaxCheck
	 * @uses OptionalEquip::getMaxCheck
	 * @uses Repair::getMaxCheck
	 * @uses RepairParts::getMaxCheck
	 * @uses OtherExpense::getMaxCheck
	 *
	 * @return string
	 */
	public static function getMaxCheckFormatted ($carID=null)
	{
		$maxCheck = 0;
		$max = Fuel::getMaxCheck($carID);
		if ($max > $maxCheck)
		{
			$maxCheck = $max;
		}
		$max = OptionalEquip::getMaxCheck($carID);
		if ($max > $maxCheck)
		{
			$maxCheck = $max;
		}
		$max = Repair::getMaxCheck($carID);
		if ($max > $maxCheck)
		{
			$maxCheck = $max;
		}
		$max = RepairParts::getMaxCheck($carID);
		if ($max > $maxCheck)
		{
			$maxCheck = $max;
		}
		$max = OtherExpense::getMaxCheck($carID);
		if ($max > $maxCheck)
		{
			$maxCheck = $max;
		}

		return Main::formatMoney(round($maxCheck,2));
	}

	/**
	 * Возвращает отформатированное значение минимального чека (расхода)
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMoney
	 * @uses Fuel::getMinCheck
	 * @uses OptionalEquip::getMinCheck
	 * @uses Repair::getMinCheck
	 * @uses RepairParts::getMinCheck
	 * @uses OtherExpense::getMinCheck
	 *
	 * @return string
	 */
	public static function getMinCheckFormatted ($carID=null)
	{
		$minCheck = Fuel::getMinCheck($carID);
		$min = OptionalEquip::getMinCheck($carID);
		if ($min < $minCheck)
		{
			$minCheck = $min;
		}
		$min = Repair::getMinCheck($carID);
		if ($min < $minCheck)
		{
			$minCheck = $min;
		}
		$min = RepairParts::getMinCheck($carID);
		if ($min < $minCheck)
		{
			$minCheck = $min;
		}
		$min = OtherExpense::getMinCheck($carID);
		if ($min < $minCheck)
		{
			$minCheck = $min;
		}

		return Main::formatMoney(round($minCheck,2));
	}

	/**
	 * Возвращает отформатированную сумму расходов на Дополнительное оборудование
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMoney
	 * @uses OptionalEquip::getTotalCosts
	 *
	 * @return string
	 */
	public static function getOptionalEquipTotalCostsFormatted ($carID=null)
	{
		return Main::formatMoney(OptionalEquip::getTotalCosts($carID));
	}

	/**
	 * Возвращает отформатированную сумму Прочих расходов
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Main::formatMoney
	 * @uses OtherExpense::getTotalCosts
	 *
	 * @return string
	 */
	public static function getOtherExpenseTotalCostsFormatted ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		return Main::formatMoney(OtherExpense::getTotalCosts($carID));
	}

	/**
	 * Возвращает отформатированную сумму расходов произведенных ремонтов
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Main::formatMoney
	 * @uses Repair::getTotalCosts
	 *
	 * @return string
	 */
	public static function getRepairTotalCostsFormatted ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		return Main::formatMoney(Repair::getTotalCosts($carID));
	}

	/**
	 * Возвращает отформатированную общую сумму расходов на ТО для автомобиля
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMoney
	 * @uses Ts::getTotalMaintenanceCosts
	 *
	 * @return string
	 */
	public static function getTotalMaintenanceCostsFormatted($carID=null)
	{
		return Main::formatMoney(Ts::getTotalMaintenanceCosts($carID));
	}

	/**
	 * Возвращает отформатированную сумму расходов на запчасти
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Main::formatMoney
	 * @uses RepairParts::getTotalCosts
	 *
	 * @return string
	 */
	public static function getRepairPartsTotalCostsFormatted($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		return Main::formatMoney(RepairParts::getTotalCosts($carID));
	}


	/**
	 * Топливо, заправки, средний расход
	 */

	/**
	 * Возвращает общее количество совершенных заправок за Все время
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Fuel::getNumberOfRefills
	 *
	 * @return int
	 */
	public static function getNumberOfRefills ($carID=null)
	{
		return intval(Fuel::getNumberOfRefills($carID));
	}

	/**
	 * Возвращает общее количество совершенных заправок за Год
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий год, false - прошлый год
	 *
	 * @uses Fuel::getNumberOfRefillsYear
	 *
	 * @return int
	 */
	public static function getNumberOfRefillsYear ($carID=null, $now=true)
	{
		return intval(Fuel::getNumberOfRefillsYear($carID, $now));
	}

	/**
	 * Возвращает общее количество совершенных заправок за Месяц
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий месяц, false - прошлый месяц
	 *
	 * @uses Fuel::getNumberOfRefillsMonth
	 *
	 * @return int
	 */
	public static function getNumberOfRefillsMonth ($carID=null, $now=true)
	{
		return intval(Fuel::getNumberOfRefillsMonth($carID, $now));
	}

	/**
	 * Возвращает отформатированное значение максимальной заправки
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getMaxRefills
	 *
	 * @return string
	 */
	public static function getMaxRefillsFormatted ($carID=null)
	{
		return Main::formatLiter(round(Fuel::getMaxRefills($carID),2));
	}

	/**
	 * Возвращает отформатированное значение минимальной заправки
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getMinRefills
	 *
	 * @return string
	 */
	public static function getMinRefillsFormatted ($carID=null)
	{
		return Main::formatLiter(round(Fuel::getMinRefills($carID),2));
	}

	/**
	 * Возвращает отформатированное среднее количество заправляемых литров топлива
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getAverageFuelRefills
	 *
	 * @return string
	 */
	public static function getAverageFuelRefillsFormatted ($carID=null)
	{
		return Main::formatLiter(Fuel::getAverageFuelRefills($carID));
	}


	/**
	 * Возвращает отформатированный средний расход топлива автомобиля
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatAverageLiter100Km
	 * @uses Fuel::getAverageFuelConsumption
	 *
	 * @return string
	 */
	public static function getCarAverageFuelFormatted ($carID=null)
	{
		return Main::formatAverageLiter100Km(Fuel::getAverageFuelConsumption($carID));
	}

	/**
	 * Возвращает отформатированное значение израсходованного топлива за все время
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getCarTotalSpentFuel
	 *
	 * @return string
	 */
	public static function getCarTotalSpentFuelFormatted ($carID=null)
	{
		return Main::formatLiter(round(Fuel::getCarTotalSpentFuel($carID),2));
	}

	/**
	 * Возвращает отформатированное значение израсходованного топлива за Год
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий год, false - предыдущий год
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getCarTotalSpentFuelYear
	 *
	 * @return string
	 */
	public static function getCarTotalSpentFuelYearFormatted ($carID=null, $now=true)
	{
		return Main::formatLiter(round(Fuel::getCarTotalSpentFuelYear($carID,$now),2));
	}

	/**
	 * Возвращает отформатированное значение израсходованного топлива за Месяц
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий месяц, false - предыдущий месяц
	 *
	 * @uses Main::formatLiter
	 * @uses Fuel::getCarTotalSpentFuelMonth
	 *
	 * @return string
	 */
	public static function getCarTotalSpentFuelMonthFormatted ($carID=null, $now=true)
	{
		return Main::formatLiter(round(Fuel::getCarTotalSpentFuelMonth($carID,$now),2));
	}

	/**
	 * Возвращает отформатированное значение средней стоимости километра (по заправкам)
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerKm::moneyPerKmFormat
	 * @uses Fuel::getAverageFuelCostKm
	 *
	 * @return string
	 */
	public static function getAverageFuelCostKmFormatted($carID=null)
	{
		return Main::formatMoneyPerKm(Fuel::getAverageFuelCostKm($carID));
	}

	/**
	 * Возвращает отформатированное значение наименьшего расхода топлива
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatAverageLiter100Km
	 * @uses Fuel::getMinFuelConsumption
	 *
	 * @return string
	 */
	public static function getMinFuelConsumptionFormatted ($carID=null)
	{
		return Main::formatAverageLiter100Km(Fuel::getMinFuelConsumption($carID));
	}

	/**
	 * Возвращает отформатированное значение наибольшего расхода топлива
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatAverageLiter100Km
	 * @uses Fuel::getMaxFuelConsumption
	 *
	 * @return string
	 */
	public static function getMaxFuelConsumptionFormatted ($carID=null)
	{
		return Main::formatAverageLiter100Km(Fuel::getMaxFuelConsumption($carID));
	}

	/**
	 * Возвращает отформатированное значение минимальной цены на топливо
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerLiter::moneyPerLiterFormat
	 * @uses Fuel::getMinFuelCost
	 *
	 * @return string
	 */
	public static function getMinFuelCostFormatted ($carID=null)
	{
		return Main::formatMoneyPerLiter(round(Fuel::getMinFuelCost($carID),2));
	}

	/**
	 * Возвращает отформатированное значение максимальной цены на топливо
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerLiter::moneyPerLiterFormat
	 * @uses Fuel::getMaxFuelCost
	 *
	 * @return string
	 */
	public static function getMaxFuelCostFormatted ($carID=null)
	{
		return Main::formatMoneyPerLiter(round(Fuel::getMaxFuelCost($carID),2));
	}

	/**
	 * Возвращает отформатированное среднее значение цены на заправку топливом
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMoney
	 * @uses Fuel::getAverageFuelCosts
	 *
	 * @return string
	 */
	public static function getAverageFuelCostsFormatted ($carID=null)
	{
		return Main::formatMoney(Fuel::getAverageFuelCosts($carID));
	}


	/**
	 * Пробег, значение одометра
	 */

	/**
	 * Возвращает отформатированное текущее значение одометра автомобиля
	 *
	 * Возвращает значение одометра.
	 * Пробег автомобиля может отличаться - если вы купили авто с пробегом, либо заменили на машине одометр.
	 * Для получения значения пробега воспользуйтесь функциями:
	 * @see Statistics::getCarCurrentMileageFormatted
	 * @see Odo::getCurrentMileage
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMileage
	 * @uses Odo::getCurrentOdo
	 *
	 * @return string
	 */
	public static function getCarCurrentOdoFormatted ($carID=null)
	{
		return Main::formatMileage(floatval(Odo::getCurrentOdo($carID)));
	}

	/**
	 * Выводит отформатированное текущее значение пробега автомобиля
	 *
	 * Возвращает значение пробега.
	 * Пробег автомобиля может отличаться от значения одометра - если вы купили авто с пробегом, либо заменили на
	 * машине одометр.
	 * Для получения значения одометра воспользуйтесь функциями:
	 * @see Statistics::getCarCurrentOdoFormatted
	 * @see Odo::getCurrentOdo
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMileage
	 * @uses Odo::getCurrentMileage
	 *
	 * @return string
	 */
	public static function getCarCurrentMileageFormatted ($carID=null)
	{
		return Main::formatMileage(floatval(Odo::getCurrentMileage($carID)));
	}

	/**
	 * Возвращает отформатированное значение средней стоимости километра (по всем расходам)
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerKm::moneyPerKmFormat
	 * @uses self::getAverageCostKm
	 *
	 * @return string
	 */
	public static function getAverageCostKmFormatted ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$mileage = Odo::getCurrentMileage($carID);
		$cost = Statistics::getCarTotalCosts($carID);

		if (floatval($mileage)>0)
		{
			return Main::formatMoneyPerKm(floatval($cost/$mileage));
		}
		else
		{
			return Main::formatMoneyPerKm(0);
		}
	}

	/**
	 * Возвращает отформатированный средний пробег в день
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMileagePerDay
	 * @uses Odo::getAverageMileageDay
	 *
	 * @return string
	 */
	public static function getAverageMileageDayFormatted ($carID=null)
	{
		return Main::formatMileagePerDay(Odo::getAverageMileageDay($carID));
	}

	/**
	 * Возвращает отформатированный средний пробег в месяц
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMileagePerMonth
	 * @uses Odo::getAverageMileageMonth
	 *
	 * @return string
	 */
	public static function getAverageMileageMonthFormatted ($carID=null)
	{
		return Main::formatMileagePerMonth(Odo::getAverageMileageMonth($carID));
	}

	/**
	 * Возвращает отформатированное значение максимальной стоимости километра
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerKm::moneyPerKmFormat
	 * @uses Fuel::getMaxCostByKm
	 *
	 * @return string
	 */
	public static function getMaxCostByKmFormatted ($carID=null)
	{
		return Main::formatMoneyPerKm(Fuel::getMaxCostByKm($carID));
	}

	/**
	 * Возвращает отформатированное значение минимальной стоимости километра
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses formatMoneyPerKm::moneyPerKmFormat
	 * @uses Fuel::getMinCostByKm
	 *
	 * @return string
	 */
	public static function getMinCostByKmFormatted ($carID=null)
	{
		return Main::formatMoneyPerKm(Fuel::getMinCostByKm($carID));
	}


	/**
	 * Данные по автомобилю
	 */

	/**
	 * Возвращает отформатированное значение количества дней владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatDays
	 * @uses MyCar::getOwnershipDays
	 *
	 * @return string
	 */
	public static function getOwnershipDaysFormatted ($carID=null)
	{
		return Main::formatDays(MyCar::getOwnershipDays($carID));
	}

	/**
	 * Возвращает отформатированное значение количества месяцев владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::formatMonths
	 * @uses MyCar::getOwnershipMonths
	 *
	 * @return int|string
	 */
	public static function getOwnershipMonthsFormatted ($carID=null)
	{
		return Main::formatMonths(MyCar::getOwnershipMonths($carID));
	}

	/**
	 * Возвращает отформатированное значение количества лет владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses Main::yearsFormat
	 * @uses MyCar::getOwnershipYears
	 *
	 * @return int|string
	 */
	public static function getOwnershipYearsFormatted ($carID=null)
	{
		return Main::yearsFormat(MyCar::getOwnershipYears($carID));
	}

	/**
	 * Возвращает отформатированное значение количества лет, месяцев и дней владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getOwnershipDays
	 * @uses Main::yearsFormat
	 * @uses Main::formatMonths
	 * @uses Main::formatDays
	 *
	 * @return int|string
	 */
	public static function getOwnershipYearsMonthsDaysFormatted ($carID=null)
	{
		$days = MyCar::getOwnershipDays($carID);
		$years = intval($days / 365);
		$days -= intval($years*365);
		$months = intval($days / 30.4);
		$days -= intval($months*30.4);

		return Main::yearsFormat($years).",&nbsp;".Main::formatMonths($months).",&nbsp;".Main::formatDays($days);
	}

}