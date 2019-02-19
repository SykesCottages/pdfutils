<?php

namespace SykesCottages\PDFUtils;

use SykesCottages\PDFUtils\Constant\BinPath;
use SykesCottages\PDFUtils\Constant\EscapeCharacter;
use SykesCottages\PDFUtils\Exception\CouldNotConvertToTextException;
use SykesCottages\PDFUtils\Exception\CouldNotSplitPDFException;
use SykesCottages\PDFUtils\Value\Page;
use Symfony\Component\Process\Process;

class PDF
{
    private $pdfToTextBinPath = BinPath::PDF_TO_TEXT;

    private $splitPdfBinPath = BinPath::SPLIT_PDF_PATH;

    private $path;

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setPdfToTextBinPath(string $pdfToTextBinPath): self
    {
        $this->pdfToTextBinPath = $pdfToTextBinPath;

        return $this;
    }

    public function setSplitPdfBinPath(string $splitPdfBinPath): self
    {
        $this->splitPdfBinPath = $splitPdfBinPath;

        return $this;
    }

    public function toText(): string
    {
        $process = new Process([
            $this->pdfToTextBinPath,
            $this->path,
            '-'
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new CouldNotConvertToTextException($process);
        }

        return trim($process->getOutput(), EscapeCharacter::FORM_FEED);
    }

    public function getPages(): array
    {
        $splitPages = explode(EscapeCharacter::FORM_FEED, $this->toText());
        $pages = [];

        foreach ($splitPages as $pageNumber => $splitPage) {
            $pages[] = new Page($pageNumber + 1, $splitPage);
        }

        return $pages;
    }

    public function split(int $startPage, int $endPage, string $outputPath = '/tmp/output.pdf'): string
    {
        $process = new Process([
            $this->splitPdfBinPath,
            $this->path,
            'cat',
            $startPage . '-' . $endPage,
            'output',
            $outputPath
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new CouldNotSplitPDFException($process);
        }

        return $process->getOutput();
    }
}
