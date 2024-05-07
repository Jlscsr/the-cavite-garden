<?php

namespace Builders;

class ResponseBuilder
{
    private array $data = [];
    private string $message = '';
    private string $statusMessage = '';
    private int $statusCode = 200;

    /**
     * Constructor for the class.
     *
     * @param string $message The message to be set.
     * @param string $statusMessage The status message to be set.
     * @param int $statusCode The status code to be set.
     */
    public function __construct(string $message, string $statusMessage, int $statusCode)
    {
        $this->message = $message;
        $this->statusMessage = $statusMessage;
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the data for the ResponseBuilder.
     *
     * @param array $data The data to be set.
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Builds and returns an array with the status message, message, and data.
     *
     * @return array The built array with the status message, message, and data.
     */
    public function build(): array
    {
        return [
            'status' => $this->statusMessage,
            'message' => $this->message,
            'data' => $this->data ?? []
        ];
    }

    /**
     * Retrieves the status code of the ResponseBuilder.
     *
     * @return int The status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
