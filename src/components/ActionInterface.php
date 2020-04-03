<?php

namespace rootlocal\crud\components;

/**
 * Interface ActionInterface
 * Base Action Interface
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\components
 */
interface ActionInterface
{

    /**
     * @return string
     */
    public function getViewName();

    /**
     * @param string $viewName
     */
    public function setViewName($viewName);

}