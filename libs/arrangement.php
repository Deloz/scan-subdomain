<?php

/*
 * $length = 3;
 * $items = array('a', 'b', 'c', 'd', ..., 'x', 'y', 'z');
 * 
 * $output = [
        [abc],
        [abb],
        [aba],
        [acc],
        [acb],
        [aca],
        ...
    ]
 *
 */


class Arrangement {
    private $result = array();
    private $length = 0;
    private $items  = array();

    public function __construct(array $values, $length)
    {
        $this->items = $values;
        $this->length = $length;
    }

    public function getAll()
    {
        return $this->process($this->items, $this->length);
    }

    protected function process(array $values, $length, array $current = array())
    {
        if ($length > 0) {
            foreach ($values as $val) {
                $new_current = $current;
                $new_current[] = $val;
                $this->process($values, $length - 1, $new_current);
            }
        } else {
            $this->result[] = $current;
        }

        return $this->result;
    }
}