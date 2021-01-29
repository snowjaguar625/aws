<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetBucketCorsRequest;

class GetBucketCorsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBucketCorsRequest([
            'Bucket' => 'bucket-name',
            'ExpectedBucketOwner' => 'expected-owner',
        ]);

        // see example-1.json from SDK
        $expected = '
GET /bucket-name?cors HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: expected-owner
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
