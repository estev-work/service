<?php

namespace Project\Base\Domain;

interface AggregateRootInterface
{
    public function pullEvents(): array;
}
