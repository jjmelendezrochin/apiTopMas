<?php

class Enum {

    private $m_valueName = NULL;

    private function __construct($valueName) {
        $this->m_valueName = $valueName;
    }

    public static function __callStatic($methodName, $arguments) {
        $className = get_called_class();
        return new $className($methodName);
    }

    function __toString() {
        return $this->m_valueName;
    }

}