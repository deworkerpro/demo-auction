<?php

declare(strict_types=1);

namespace App;

interface Flusher
{
    public function flush(): void;
}
