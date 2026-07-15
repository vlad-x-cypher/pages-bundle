<?php

namespace VladX\PagesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PagesBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
