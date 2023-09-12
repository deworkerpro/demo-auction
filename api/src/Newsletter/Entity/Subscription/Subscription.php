<?php

declare(strict_types=1);

namespace App\Newsletter\Entity\Subscription;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'newsletter_subscriptions')]
final class Subscription
{
    #[ORM\Column(type: 'newsletter_subscription_id')]
    #[ORM\Id]
    private Id $id;

    #[ORM\Column(type: 'newsletter_subscription_email')]
    private Email $email;

    public function __construct(Id $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
