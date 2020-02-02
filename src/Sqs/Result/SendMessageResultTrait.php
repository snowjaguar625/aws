<?php

namespace AsyncAws\Sqs\Result;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait SendMessageResultTrait
{
    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->SendMessageResult;

        $this->MD5OfMessageBody = $this->xmlValueOrNull($data->MD5OfMessageBody, 'string');
        $this->MD5OfMessageAttributes = $this->xmlValueOrNull($data->MD5OfMessageAttributes, 'string');
        $this->MD5OfMessageSystemAttributes = $this->xmlValueOrNull($data->MD5OfMessageSystemAttributes, 'string');
        $this->MessageId = $this->xmlValueOrNull($data->MessageId, 'string');
        $this->SequenceNumber = $this->xmlValueOrNull($data->SequenceNumber, 'string');
    }
}
