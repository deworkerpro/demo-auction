<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public int $id;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        echo $email;
    }
}

?>
-----
<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public int $id;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        echo $email;
    }
}

?>
