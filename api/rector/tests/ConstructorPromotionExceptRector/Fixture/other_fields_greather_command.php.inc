<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public int $id;
    public string $email;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}

?>
-----
<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public int $id;
    public string $email;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}

?>
