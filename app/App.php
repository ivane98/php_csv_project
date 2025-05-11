<?php

declare(strict_types = 1);

// Your Code
function getTransactionFiles(string $dirName): array {
    $files  = [];

    foreach(scandir($dirName) as $file){
        if(is_dir($file)){
            continue;
        }else {
            $files[] = $dirName . $file;
        } 
    }
    return $files;
}

function getTransactions(string $fileName): array{
    $file = fopen($fileName, 'r');
    fgetcsv($file);

    $transactions = [];

    while(($transaction = fgetcsv($file)) !== false){
        $transactions[] = extractTransactions($transaction);
    }

    return $transactions;
}

function extractTransactions(array $transactionRow): array {
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount
    ];
}

function calculateTotals(array $transactions): array {
    $totals = ['netTotal' => 0, 'netIncome' => 0, 'netExpense' => 0];

    foreach($transactions as $transaction){
        $totals['netTotal'] += $transaction['amount'];

        if($transaction['amount'] > 0){
            $totals['netIncome'] += $transaction['amount'];
        }else {
            $totals['netExpense'] += $transaction['amount'];
        }
    }

    return $totals;
}