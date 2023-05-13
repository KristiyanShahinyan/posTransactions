<?php

namespace App\Message;

class ReconciliationMessage
{
    private ?string $acquirerCode;

    private ?string $affiliateCode;

    private string $filename;

    private bool $replacePan;

    private int $batch;

    private ?bool $compress = false;

    /**
     * @return string|null
     */
    public function getAcquirerCode(): ?string
    {
        return $this->acquirerCode;
    }

    /**
     * @param string|null $acquirerCode
     */
    public function setAcquirerCode(?string $acquirerCode): void
    {
        $this->acquirerCode = $acquirerCode;
    }

    /**
     * @return string|null
     */
    public function getAffiliateCode(): ?string
    {
        return $this->affiliateCode;
    }

    /**
     * @param string|null $affiliateCode
     */
    public function setAffiliateCode(?string $affiliateCode): void
    {
        $this->affiliateCode = $affiliateCode;
    }

    /**
     * @return bool
     */
    public function isReplacePan(): bool
    {
        return $this->replacePan;
    }

    /**
     * @param bool $replacePan
     */
    public function setReplacePan(bool $replacePan): void
    {
        $this->replacePan = $replacePan;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getBatch(): int
    {
        return $this->batch;
    }

    /**
     * @param int $batch
     */
    public function setBatch(int $batch): void
    {
        $this->batch = $batch;
    }

    /**
     * @return bool|null
     */
    public function getCompress(): ?bool
    {
        return $this->compress;
    }

    /**
     * @param bool|null $compress
     */
    public function setCompress(?bool $compress): void
    {
        $this->compress = $compress;
    }
}