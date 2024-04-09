<?php

namespace App\Rector\Tests\ConstructorPromotionExceptRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    private int $id;

    #[ORM\Column]
    private string $email;

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

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    private int $id;

    #[ORM\Column]
    private string $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}

?>
