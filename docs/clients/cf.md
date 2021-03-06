---
layout: client
category: clients
name: CloudFormation
package: async-aws/cloud-formation
---

## Usage

### List stacks

```php
use AsyncAws\CloudFormation\CloudFormationClient;

$cloudFormation = new CloudFormationClient();

$result = $cloudFormation->describeStacks();

foreach ($result->getStacks() as $stack) {
    echo $stack->getStackName().'-'.$stack->getStackStatus().PHP_EOL;
}
```

### List stack's events

```php
use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;

$cloudFormation = new CloudFormationClient();

$result = $cloudFormation->describeStackEvents(new DescribeStackEventsInput([
   'StackName' => 'cluster-prod',
]));

foreach ($result->getStackEvents() as $event) {
    echo $event->getResourceType().'-'.$event->getResourceStatus().PHP_EOL;
}
```

### Detect Stack Drift Status

```php
use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;

$cloudFormation = new CloudFormationClient();

$driftStatus = $cloudFormation->describeStackDriftDetectionStatus([
    'StackDriftDetectionId' => 'b78ac9b0-dec1-11e7-a451-503a3example',
]);
```
