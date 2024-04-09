<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Data
{
    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $name;

    public function __construct(string $email, string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }
}

?>
-----
<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Data
{
    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $name;

    public function __construct(string $email, string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }
}

?>
