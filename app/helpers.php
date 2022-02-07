<?php

declare(strict_types=1);

/**
 * @param float $amount
 * 
 * @return string
 */
function formatDollarAmount(float $amount): string
{
	$isNegative = $amount < 0;

	return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
}

/**
 * @param string $date
 * 
 * @return string
 */
function formatDate(string $date): string
{
	return date('M d, Y', strtotime($date));
}

/**
 * @param float $price
 * 
 * @return string
 */
function negativity(float $price): string
{
	if ($price > 0) {
		return 'green';
	} elseif ($price < 0) {
		return 'red';
	} else {
		return '';
	}
}