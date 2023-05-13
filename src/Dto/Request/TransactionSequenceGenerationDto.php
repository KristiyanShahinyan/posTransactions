<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionSequenceGenerationDto implements DtoInterface
{
    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $sequenceNumber;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $generationNumber;

    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    public function setSequenceNumber(?string $sequenceNumber): void
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getGenerationNumber(): ?string
    {
        return $this->generationNumber;
    }

    public function setGenerationNumber(?string $generationNumber): void
    {
        $this->generationNumber = $generationNumber;
    }
}
