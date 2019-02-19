<?php

namespace SykesCottages\PDFUtils\Value;

class Page
{
    private $pageNumber;

    private $pageContents;

    public function __construct(int $pageNumber, string $pageContents)
    {
        $this->pageNumber = $pageNumber;
        $this->pageContents = $pageContents;
    }

    public function contains(string $needle): bool
    {
        return strpos($this->pageContents, $needle) !== false;
    }

    public function getPageContents(): string
    {
        return $this->pageContents;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }


}