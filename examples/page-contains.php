<?php

use SykesCottages\PDFUtils\PDF;

include '../vendor/autoload.php';

$pdf = new PDF();
$pages = $pdf
    ->setPath('files/example.pdf')
    ->getPages();

foreach($pages as $page) {
    var_dump($page->contains('Dummy'));
}
