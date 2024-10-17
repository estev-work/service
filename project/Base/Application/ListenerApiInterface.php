<?php

namespace Project\Base\Application;

interface ListenerApiInterface
{
    /**
     * @return string[]
     */
    public function getListeners(): array;
}