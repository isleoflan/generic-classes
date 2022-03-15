<?php

declare(strict_types=1);

namespace IOL\Generic\v1\Request;

use IOL\Generic\v1\DataSource\File;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;

class Error
{
    /**
     * @throws JsonException
     */
    public function __construct(
        private int     $errorCode,
        private ?string $message = null,
        private ?int    $httpCode = null
    )
    {
        if (is_null($message) || is_null($httpCode)) {
            $this->lookup();
        }
    }

    #[ArrayShape(['errorCode' => 'int', 'message' => 'string'])]
    public function render(): array
    {
        return [
            'errorCode' => $this->errorCode,
            'message' => $this->message,
        ];
    }

    /**
     * @return int|null
     */
    public function getHttpCode(): ?int
    {
        return $this->httpCode;
    }

    /**
     * @throws JsonException
     */
    private function lookup(): void
    {
        $errorFileBase = File::getBasePath() . '/i18n/errors/';
        $errorLanguage = 'en';
        $errorFile = $errorFileBase . $errorLanguage . '.json';
        $lookupTable = json_decode(file_get_contents($errorFile), true, 512, JSON_THROW_ON_ERROR);

        if (isset($lookupTable[$this->errorCode])) {
            $this->message = $this->message ?? $lookupTable[$this->errorCode]['message'];
            $this->httpCode = $this->httpCode ?? $lookupTable[$this->errorCode]['errorCode'];
        }
    }
}
