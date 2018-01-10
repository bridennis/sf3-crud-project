<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Order entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 * @HasLifecycleCallbacks
 */

class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="order_date", type="date", )
     */
    private $order_date;

    /**
     * @ORM\Column(name="descr", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $descr;

    /**
     * @ORM\Column(name="cost", type="decimal", scale=2)
     * @Assert\GreaterThanOrEqual(0)
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOrderDate()
    {
        return $this->order_date;
    }

    /**
     * @return mixed
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $order_date
     */
    public function setOrderDate($order_date)
    {
        $this->order_date = $order_date;
    }

    /**
     * @param mixed $descr
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->order_date = new \DateTime();
    }
}