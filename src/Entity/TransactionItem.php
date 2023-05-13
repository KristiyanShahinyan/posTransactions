<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Phos\Entity\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="t_transaction_item", schema="transactions")
 */
class TransactionItem implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction", inversedBy="transactionItems")
     * @ORM\JoinColumn(name="transaction", referencedColumnName="id", nullable=false)
     */
    protected $transaction;

    /**
     * @ORM\Column(name="item_name", type="string", nullable=false)
     */
    protected $itemName;

    /**
     * @ORM\Column(name="item_info", type="text", nullable=true)
     */
    protected $itemInfo;

    /**
     * @ORM\Column(name="item_amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $itemAmount;

    /**
     * @ORM\Column(name="item_quantity", type="float", nullable=false)
     */
    protected $itemQuantity;

    public function __construct()
    {
        $this->itemQuantity = 1;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction): void
    {
        $this->transaction = $transaction;
    }

    /**
     * @return mixed
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param mixed $itemName
     */
    public function setItemName($itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * @return mixed
     */
    public function getItemInfo()
    {
        return $this->itemInfo;
    }

    /**
     * @param mixed $itemInfo
     */
    public function setItemInfo($itemInfo): void
    {
        $this->itemInfo = $itemInfo;
    }

    /**
     * @return mixed
     */
    public function getItemAmount()
    {
        return $this->itemAmount;
    }

    /**
     * @param mixed $itemAmount
     */
    public function setItemAmount($itemAmount): void
    {
        $this->itemAmount = $itemAmount;
    }

    public function getItemQuantity(): int
    {
        return $this->itemQuantity;
    }

    public function setItemQuantity(int $itemQuantity): void
    {
        $this->itemQuantity = $itemQuantity;
    }
}
