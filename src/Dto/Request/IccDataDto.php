<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;

class IccDataDto implements DtoInterface
{

    private ?string $terminalVerificationResult = null;

    private ?string $applicationInterchangeProfile = null;

    private ?string $terminalCapabilities = null;

    private ?string $cvmList = null;

    private ?string $cvmResults = null;

    private ?string $formFactorIndicator = null;

    private ?string $issuerApplicationData = null;

    /**
     * @return string|null
     */
    public function getTerminalVerificationResult(): ?string
    {
        return $this->terminalVerificationResult;
    }

    /**
     * @param string|null $terminalVerificationResult
     */
    public function setTerminalVerificationResult(?string $terminalVerificationResult): void
    {
        $this->terminalVerificationResult = $terminalVerificationResult;
    }

    /**
     * @return string|null
     */
    public function getApplicationInterchangeProfile(): ?string
    {
        return $this->applicationInterchangeProfile;
    }

    /**
     * @param string|null $applicationInterchangeProfile
     */
    public function setApplicationInterchangeProfile(?string $applicationInterchangeProfile): void
    {
        $this->applicationInterchangeProfile = $applicationInterchangeProfile;
    }

    /**
     * @return string|null
     */
    public function getTerminalCapabilities(): ?string
    {
        return $this->terminalCapabilities;
    }

    /**
     * @param string|null $terminalCapabilities
     */
    public function setTerminalCapabilities(?string $terminalCapabilities): void
    {
        $this->terminalCapabilities = $terminalCapabilities;
    }

    /**
     * @return string|null
     */
    public function getCvmList(): ?string
    {
        return $this->cvmList;
    }

    /**
     * @param string|null $cvmList
     */
    public function setCvmList(?string $cvmList): void
    {
        $this->cvmList = $cvmList;
    }

    /**
     * @return string|null
     */
    public function getCvmResults(): ?string
    {
        return $this->cvmResults;
    }

    /**
     * @param string|null $cvmResults
     */
    public function setCvmResults(?string $cvmResults): void
    {
        $this->cvmResults = $cvmResults;
    }

    /**
     * @return string|null
     */
    public function getFormFactorIndicator(): ?string
    {
        return $this->formFactorIndicator;
    }

    /**
     * @param string|null $formFactorIndicator
     */
    public function setFormFactorIndicator(?string $formFactorIndicator): void
    {
        $this->formFactorIndicator = $formFactorIndicator;
    }

    /**
     * @return string|null
     */
    public function getIssuerApplicationData(): ?string
    {
        return $this->issuerApplicationData;
    }

    /**
     * @param string|null $issuerApplicationData
     */
    public function setIssuerApplicationData(?string $issuerApplicationData): void
    {
        $this->issuerApplicationData = $issuerApplicationData;
    }

    public function asArray(): ?array
    {
        $array = [];

        if (!is_null($this->terminalVerificationResult))
            $array['terminal_verification_result'] = $this->terminalVerificationResult;
        if (!is_null($this->applicationInterchangeProfile))
            $array['application_interchange_profile'] = $this->applicationInterchangeProfile;
        if (!is_null($this->terminalCapabilities))
            $array['terminal_capabilities'] = $this->terminalCapabilities;
        if (!is_null($this->cvmList))
            $array['cvm_list'] = $this->cvmList;
        if (!is_null($this->cvmResults))
            $array['cvm_results'] = $this->cvmResults;
        if (!is_null($this->formFactorIndicator))
            $array['form_factor_indicator'] = $this->formFactorIndicator;
        if (!is_null($this->issuerApplicationData))
            $array['issuer_application_data'] = $this->issuerApplicationData;

        return $array;
    }
}