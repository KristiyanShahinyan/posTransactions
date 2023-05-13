<?php

namespace App\Entity;

use App\Repository\ReconciliationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReconciliationRepository::class)
 * @ORM\Table(name="t_reconciliation", schema="transactions")
 */
class Reconciliation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acquirer;

    /**
     * @ORM\Column(type="integer")
     */
    private $batch;

    /**
     * @ORM\Column(type="boolean")
     */
    private $replacePan;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $options = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastGenerated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $affiliate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $batchSize;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $compress;

    public function incrementBatch(): int
    {
        $this->batch++;

        return $this->batch;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcquirer(): ?string
    {
        return $this->acquirer;
    }

    public function setAcquirer(?string $acquirer): self
    {
        $this->acquirer = $acquirer;

        return $this;
    }

    public function getBatch(): ?int
    {
        return $this->batch;
    }

    public function setBatch(int $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getReplacePan(): ?bool
    {
        return $this->replacePan;
    }

    public function setReplacePan(bool $replacePan): self
    {
        $this->replacePan = $replacePan;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getLastGenerated(): ?\DateTimeInterface
    {
        return $this->lastGenerated;
    }

    public function setLastGenerated(?\DateTimeInterface $lastGenerated): self
    {
        $this->lastGenerated = $lastGenerated;

        return $this;
    }

    public function getAffiliate(): ?string
    {
        return $this->affiliate;
    }

    public function setAffiliate(?string $affiliate): self
    {
        $this->affiliate = $affiliate;

        return $this;
    }

    public function getBatchSize(): ?int
    {
        return $this->batchSize;
    }

    public function setBatchSize(?int $batchSize): self
    {
        $this->batchSize = $batchSize;

        return $this;
    }

    public function getCompress(): ?bool
    {
        return $this->compress;
    }

    public function setCompress(?bool $compress): self
    {
        $this->compress = $compress;

        return $this;
    }
}
