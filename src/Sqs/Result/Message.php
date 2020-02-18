<?php

namespace AsyncAws\Sqs\Result;

class Message
{
    /**
     * A unique identifier for the message. A `MessageId`is considered unique across all AWS accounts for an extended period
     * of time.
     */
    private $MessageId;

    /**
     * An identifier associated with the act of receiving the message. A new receipt handle is returned every time you
     * receive a message. When deleting a message, you provide the last received receipt handle to delete the message.
     */
    private $ReceiptHandle;

    /**
     * An MD5 digest of the non-URL-encoded message body string.
     */
    private $MD5OfBody;

    /**
     * The message's contents (not URL-encoded).
     */
    private $Body;

    /**
     * A map of the attributes requested in `ReceiveMessage` to their respective values. Supported attributes:.
     */
    private $Attributes = [];

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $MD5OfMessageAttributes;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS Message
     * Attributes in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-attributes.html
     */
    private $MessageAttributes = [];

    /**
     * @param array{
     *   MessageId: ?string,
     *   ReceiptHandle: ?string,
     *   MD5OfBody: ?string,
     *   Body: ?string,
     *   Attributes: ?array,
     *   MD5OfMessageAttributes: ?string,
     *   MessageAttributes: ?array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->MessageId = $input['MessageId'];
        $this->ReceiptHandle = $input['ReceiptHandle'];
        $this->MD5OfBody = $input['MD5OfBody'];
        $this->Body = $input['Body'];
        $this->Attributes = $input['Attributes'] ?? [];
        $this->MD5OfMessageAttributes = $input['MD5OfMessageAttributes'];
        $this->MessageAttributes = array_map(function ($item) { return MessageAttributeValue::create($item); }, $input['MessageAttributes'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    public function getBody(): ?string
    {
        return $this->Body;
    }

    public function getMD5OfBody(): ?string
    {
        return $this->MD5OfBody;
    }

    public function getMD5OfMessageAttributes(): ?string
    {
        return $this->MD5OfMessageAttributes;
    }

    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes;
    }

    public function getMessageId(): ?string
    {
        return $this->MessageId;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->ReceiptHandle;
    }
}
