<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\ItemCollectionMetrics;

/**
 * Represents the output of an `UpdateItem` operation.
 */
class UpdateItemOutput extends Result
{
    /**
     * A map of attribute values as they appear before or after the `UpdateItem` operation, as determined by the
     * `ReturnValues` parameter.
     */
    private $attributes;

    /**
     * The capacity units consumed by the `UpdateItem` operation. The data returned includes the total provisioned
     * throughput consumed, along with statistics for the table and any indexes involved in the operation.
     * `ConsumedCapacity` is only returned if the `ReturnConsumedCapacity` parameter was specified. For more information,
     * see Provisioned Throughput in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
     */
    private $consumedCapacity;

    /**
     * Information about item collections, if any, that were affected by the `UpdateItem` operation. `ItemCollectionMetrics`
     * is only returned if the `ReturnItemCollectionMetrics` parameter was specified. If the table does not have any local
     * secondary indexes, this information is not returned in the response.
     */
    private $itemCollectionMetrics;

    /**
     * @return array<string, AttributeValue>
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->attributes;
    }

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->consumedCapacity;
    }

    public function getItemCollectionMetrics(): ?ItemCollectionMetrics
    {
        $this->initialize();

        return $this->itemCollectionMetrics;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->attributes = empty($data['Attributes']) ? [] : $this->populateResultAttributeMap($data['Attributes']);
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? null : new ConsumedCapacity([
            'TableName' => isset($data['ConsumedCapacity']['TableName']) ? (string) $data['ConsumedCapacity']['TableName'] : null,
            'CapacityUnits' => isset($data['ConsumedCapacity']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['CapacityUnits'] : null,
            'ReadCapacityUnits' => isset($data['ConsumedCapacity']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($data['ConsumedCapacity']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['WriteCapacityUnits'] : null,
            'Table' => empty($data['ConsumedCapacity']['Table']) ? null : new Capacity([
                'ReadCapacityUnits' => isset($data['ConsumedCapacity']['Table']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['ReadCapacityUnits'] : null,
                'WriteCapacityUnits' => isset($data['ConsumedCapacity']['Table']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['WriteCapacityUnits'] : null,
                'CapacityUnits' => isset($data['ConsumedCapacity']['Table']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['CapacityUnits'] : null,
            ]),
            'LocalSecondaryIndexes' => !isset($data['ConsumedCapacity']['LocalSecondaryIndexes']) ? null : $this->populateResultSecondaryIndexesCapacityMap($data['ConsumedCapacity']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => !isset($data['ConsumedCapacity']['GlobalSecondaryIndexes']) ? null : $this->populateResultSecondaryIndexesCapacityMap($data['ConsumedCapacity']['GlobalSecondaryIndexes']),
        ]);
        $this->itemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? null : new ItemCollectionMetrics([
            'ItemCollectionKey' => !isset($data['ItemCollectionMetrics']['ItemCollectionKey']) ? null : $this->populateResultItemCollectionKeyAttributeMap($data['ItemCollectionMetrics']['ItemCollectionKey']),
            'SizeEstimateRangeGB' => !isset($data['ItemCollectionMetrics']['SizeEstimateRangeGB']) ? null : $this->populateResultItemCollectionSizeEstimateRange($data['ItemCollectionMetrics']['SizeEstimateRangeGB']),
        ]);
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultItemCollectionKeyAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    /**
     * @return float[]
     */
    private function populateResultItemCollectionSizeEstimateRange(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<string, Capacity>
     */
    private function populateResultSecondaryIndexesCapacityMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = Capacity::create($value);
        }

        return $items;
    }
}
