<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public int $id;
    public string $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}

?>
-----
<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

class Command
{
    public function __construct(public int $id, public string $email)
    {
    }
}

?>
