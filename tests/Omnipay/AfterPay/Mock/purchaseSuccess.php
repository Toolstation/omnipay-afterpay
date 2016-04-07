<?php
$response = new \stdClass();
$response->return = new \stdClass();
$response->return->afterPayOrderReference = '3412893e-073f-4e6e-a675-2fadd44df72c';
$response->return->checksum = 'a9f74b3144f7e47a1e83f2a47d087284';
$response->return->extrafields = new \stdClass();
$response->return->extrafields->extraField = array();
$response->return->extrafields->extraField[0] = new \stdClass();
$response->return->extrafields->extraField[0]->nameField = 'RSS_Corr_Address_Street';
$response->return->extrafields->extraField[0]->valueField = 'Richtericher Str.';
$response->return->extrafields->extraField[1] = new \stdClass();
$response->return->extrafields->extraField[1]->nameField = 'RSS_RiskCheck_ResultCode';
$response->return->extrafields->extraField[1]->valueField = 'SCR000';
$response->return->extrafields->extraField[2] = new \stdClass();
$response->return->extrafields->extraField[2]->nameField = 'RSS_StoreOrder_ResultCode';
$response->return->extrafields->extraField[2]->valueField = 'SOR000';
$response->return->resultId = 0;
$response->return->statusCode = 'A';
$response->return->timestampIn = 1460047734;
$response->return->timestampOut = 1460047737;
$response->return->transactionId = 10003022;

return $response;
