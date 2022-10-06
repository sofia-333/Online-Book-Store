<?php

class Page extends IteratorIterator
{
    private $page;
    private $currentItem;
    public $itemsPerPage;


    public function __construct(Iterator $iterator,
                                         $page,
                                         $itemsPerPage)
    {
        parent::__construct($iterator);
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->rewind();
    }

    public function getFirstIndex()
    {
        return ($this->page - 1) * $this->itemsPerPage;
    }

    public function getLastIndex()
    {
        return $this->getFirstIndex() + $this->itemsPerPage;
    }

    public function rewind()
    {
        $this->currentItem = 0;
        $this->getInnerIterator()->seek($this->getFirstIndex());
    }

    public function valid()
    {
        return ($this->currentItem != $this->itemsPerPage && $this->getInnerIterator()->key() !== null);
    }

    public function current()
    {
        return ($this->currentItem != $this->itemsPerPage ? $this->getInnerIterator()->current() : null);
    }

    public function key()
    {
        return ($this->currentItem != $this->itemsPerPage ? $this->getInnerIterator()->key() : null);
    }

    public function next()
    {
        if ($this->currentItem < $this->itemsPerPage) {
            $this->currentItem++;
            $this->getInnerIterator()->next();
        }
    }
}