<?php

declare(strict_types=1);

/**
 * @param string $dirPath
 * 
 * @return array of file path in a directory
 */
function getTransactionFiles(string $dirPath): array
{
	//empty array to store file path
	$files = [];

	//loop through directory path for files
	foreach (scandir($dirPath) as $file) {
		if (is_dir($file)) {
			continue;
		}

		// push file path and file name into $files array
		$files[] = $dirPath . $file;
	}

	return $files;
}

/**
 * @param string $fileName
 * @param callable|null $transactionHandler
 * 
 * @return array of transactions read from csv files
 */
function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
	if (!file_exists($fileName)) {
		trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
	}
	$file = fopen($fileName, 'r');

	//removes the first line from the file (header line)
	fgetcsv($file);

	$transactions = [];

	while (($transaction = fgetcsv($file)) !== false) {
		if ($transactionHandler !== null) {
			$transaction = $transactionHandler($transaction);
		}
		$transactions[] = $transaction;
	}
	return $transactions;
}

/**
 * @param array $transactionRow
 * 
 * @return array
 */
function extractTransaction(array $transactionRow): array
{
	[$date, $checkNumber, $description, $amount] = $transactionRow;

	$amount = (float)str_replace(['$', ','], '', $amount);
	return [
		'date' => $date,
		'checkNumber' => $checkNumber,
		'description' => $description,
		'amount' => $amount
	];
}

/**
 * @param array $transactions
 * 
 * @return array
 */
function  calculateTotal(array $transactions): array
{
	$totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

	foreach ($transactions as $transaction) {
		$totals['netTotal'] += $transaction['amount'];

		if ($transaction['amount'] >= 0) {
			$totals['totalIncome'] += $transaction['amount'];
		} else {
			$totals['totalExpense'] += $transaction['amount'];
		}
	}

	return $totals;
}