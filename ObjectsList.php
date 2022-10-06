<?php

class ObjectsList implements Countable, SeekableIterator
{
    private $pages = array();
    private $currentPage = 1;
    private $database = null;
    public $booksPerPage = 10;
    private $totalBooks = 0;
    private $deletions = array();
    private $tableName = null;
    private $query = null;

    public function __construct($database, $tableName, $query='')
    {
        $this->database = $database;
        $this->tableName = $tableName;
        $this->query = $query;
        $sql = "SELECT count(*) FROM " . $this->tableName;
        if ($result = $database->query($sql)) {
            if ($row = $result->fetch()) {
                $this->totalBooks = $row[0];
            }
        }
    }

    public function rewind()
    {
        $this->currentPage = 1;
        if (array_key_exists(1, $this->pages))
            reset($this->pages[$i]);
    }

    public function valid()
    {
        $page = &$this->touchPage();
        return (key($page) !== null);
    }

    public function current()
    {
        return current($this->touchPage());
    }

    public function key()
    {
        return key($this->touchPage()) +
            $this->booksPerPage *
            ($this->currentPage - 1);
    }

    public function next()
    {
        $page = &$this->touchPage();
        next($page);
        if (key($page) === null && count($page) == $this->booksPerPage) {
            $this->currentPage++;
            $page = &$this->touchPage();
            reset($page);
        }
        return current($page);
    }

    private function &touchPage($pageNo = false)
    {
        if ($pageNo === false) {
            $pageNo = $this->currentPage;
        }
        if (!array_key_exists($pageNo, $this->pages)) {
            if ($pageNo > ceil($this->count() /
                    $this->booksPerPage)) {
                $this->pages[$pageNo] = array();
            } else {
                $start = ($pageNo - 1) * $this->booksPerPage +
                    $this->getAdjustmentForPage($pageNo);
                if (!$this->query) {
                    $query = "SELECT * FROM " . $this->tableName . " LIMIT $start, {$this->booksPerPage}";
                }else {
                    $query = $this->query;
                }
                $result = $this->database->query($query);
                $this->pages[$pageNo] = $result->fetchAll();
            }


        }
        $tmp = &$this->pages[$pageNo];
        return $tmp;
    }

    private function getAdjustmentForPage($pageNo)
    {
        $adjust = 0;
        for (reset($this->deletions);
             key($this->deletions) !== null &&
             key($this->deletions) <= $pageNo;
             next($this->deletions))
            $adjust += current($this->deletions);
        return $adjust;
    }

    public function count()
    {
        return $this->totalBooks;
    }

    public function seek($offset)
    {
        if ($offset < 0 || $offset > $this->totalBooks) {
            throw new OutOfBoundsException();
        }
        $this->currentPage = (int)floor($offset / $this->booksPerPage) + 1;
        $page = &$this->touchPage();
        reset($page);
        for ($i = $offset % $this->booksPerPage; $i > 0; $i--) {
            next($page);
        }
    }
}