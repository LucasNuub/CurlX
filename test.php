<?php
foreach (glob('Require/*.php') as $key) {
  require_once($key);
}

list($cc, $mes, $ano, $cvv) = explode('|', $lista = GetCC());
$name = 's';
$last = 's';

$response = '>{"d":"{"Id":"N857B7NVPJ8CM8X3","ServiceId":null,"Success":false,"Message":"The issuer bank has declined the transaction with reason \"54 : Expired card\". Please reach out to the bank for further clarification.","ErrorMessageId":0,"Retry":false,"tokenCollection":{"ProcessorId":"Adyen","TokenResponseMessage":"The card is already expired.","InvoiceId":"176a5481-905e-4b67-9847-32b223ec8f0f","TransactionType":"Sale","GroupBilling":"0","ReDirectURI":"https://booking.ject.us/webstorepaymenthandler?redirectUrl=https://booking.ject.us/webstoreNew/sales/seriespackage/843550c9-a603-4942-9973-ecbe9472fed7"}}"}';
    $respo = CurlX::ParseString($response, '{"d":"{','}');
    echo $respo;
    echo "<br><hr>";
$toks = CurlX::ParseString($response, '"Message":"','","ErrorMessageId');
    print_r($toks);

