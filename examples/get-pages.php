<?php

use SykesCottages\PDFUtils\PDF;

include '../vendor/autoload.php';

$pdf = new PDF();
$pages = $pdf
    ->setPath('files/example.pdf')
    ->getPages();

var_dump($pages);
