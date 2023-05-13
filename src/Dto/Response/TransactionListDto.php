<?php

namespace App\Dto\Response;

use Symfony\Component\Serializer\Annotation\Groups;

class TransactionListDto extends ListDto
{
    /**
     * @var array
     * @Groups({"list"})
     */
    protected $items;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
