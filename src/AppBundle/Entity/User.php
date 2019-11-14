<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */

class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order", mappedBy="user")
     */
    private $orders;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }
}
