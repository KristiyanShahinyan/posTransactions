<?php

namespace App\Dto\Response;

use App\Dto\DtoInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class ListDto
 * @package App\Dto\Response
 */
abstract class ListDto implements DtoInterface
{
    /**
     * @var int
     *
     * @Groups({"list"})
     */
    protected $totalItems;

    /**
     * @var int
     *
     * @Groups({"list"})
     */
    protected $totalPages;

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     */
    public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = $totalPages;
    }
}
