<?php

$response = new \stdClass();
$response->return = new \stdClass();
$response->return->failures = new \stdClass();
$response->return->failures->failures = new \stdClass();
$response->return->failures->failures->failure = 'Validation failed.  ErrorCode: ValueOutOfLegalRange';
$response->return->failures->failures->fieldName = 'TotalOrderAmount';
$response->return->failures->failures->suggestedValue = null;
$response->return->failures->resultId = 2;
$response->return->failures->statusCode = '';

return $response;
