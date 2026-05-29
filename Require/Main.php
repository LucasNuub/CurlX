<?php
  function decode_unicode_escape_sequences($str)
  {
    return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
      return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
    }, $str);
  }
  function get_form_inputs($curl, $start_form, $end_form = '</form>')
  {
    $form = CurlX::ParseString($curl, $start_form, $end_form);
    return get_input_tags($form);
  }

function RealValue($string)
{
  return (is_string($string) and !empty($string)) ? true : false;
}
function g($string, $start, $final){
  return explode($final, explode($start, $string)[1])[0];
}

function Revercap($string, $final, $start){
  return strrev(g(strrev($string), strrev($final), strrev($start)));
}
function IsLive($SearchHere, $LivesResponses)
{
  foreach ($LivesResponses as $livesres) {
    if (substr_count($SearchHere, $livesres) > 0)
      return true;
  }
  return false;
}
function GetLiveRes($SearchHere, $LivesResponses, $DefinedRes = false, $ThisRes = NULL)
{
  foreach ($LivesResponses as $livesres) {
    if (substr_count($SearchHere, $livesres) > 0) {
      $DefineRes = ($DefinedRes) ? $ThisRes : $livesres;
      break;
    }
  }
  $DefineRes = (empty($DefineRes)) ? 'DEAD' : ['LIVE', $DefineRes];
  return $DefineRes;
}
function Random_Dates(): array
{
  $context = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);

  do {
    $get = file_get_contents('https://randomuser.me/api/1.2/?nat=us', false, $context);
  } while (empty($get));

  $data   = json_decode($get, true)['results'][0];
  $name   = $data['name']['first'];
  $last   = $data['name']['last'];
  $email  = $data['email'];
  $zip    = $data['location']['postcode'];
  $phone  = preg_replace('/[^0-9]/', '', $data['phone']);
  $street = $data['location']['street'];
  $city   = $data['location']['city'];
  $state  = $data['location']['state'];

  $local   = explode('@', $email)[0];
  $variant = rand(0, 3);
  if ($variant === 2) {
    $email = $local . rand(0, 999) . '@gmail.com';
  } elseif ($variant === 3) {
    $email = rand(0, 999) . str_replace('example.com', 'gmail.com', $email);
  } else {
    $sep   = ($variant === 1) ? '.' . rand(0, 999) : rand(0, 999) . '.';
    $email = str_replace('.', $sep, $local) . '@gmail.com';
  }

  $stateMap = [
    'alabama'             => 'AL', 'alaska'              => 'AK', 'arizona'             => 'AR',
    'california'          => 'CA', 'colorado'            => 'CO', 'connecticut'         => 'CT',
    'delaware'            => 'DE', 'district of columbia'=> 'DC', 'florida'             => 'FL',
    'georgia'             => 'GA', 'hawaii'              => 'HI', 'idaho'               => 'ID',
    'illinois'            => 'IL', 'indiana'             => 'IN', 'iowa'                => 'IA',
    'kansas'              => 'KS', 'kentucky'            => 'KY', 'louisiana'           => 'LA',
    'maine'               => 'ME', 'maryland'            => 'MD', 'massachusetts'       => 'MA',
    'michigan'            => 'MI', 'minnesota'           => 'MN', 'mississippi'         => 'MS',
    'missouri'            => 'MO', 'montana'             => 'MT', 'nebraska'            => 'NE',
    'nevada'              => 'NV', 'new hampshire'       => 'NH', 'new jersey'          => 'NJ',
    'new mexico'          => 'NM', 'new york'            => 'LA', 'north carolina'      => 'NC',
    'north dakota'        => 'ND', 'ohio'                => 'OH', 'oklahoma'            => 'OK',
    'oregon'              => 'OR', 'pennsylvania'        => 'PA', 'rhode island'        => 'RI',
    'south carolina'      => 'SC', 'south dakota'        => 'SD', 'tennessee'           => 'TN',
    'texas'               => 'TX', 'utah'                => 'UT', 'vermont'             => 'VT',
    'virginia'            => 'VA', 'washington'          => 'WA', 'west virginia'       => 'WV',
    'wisconsin'           => 'WI', 'wyoming'             => 'WY',
  ];

  return [
    'name'    => $name,
    'last'    => $last,
    'city'    => $city,
    'state'   => $state,
    'stateAB' => $stateMap[strtolower($state)] ?? 'KY',
    'zip'     => $zip,
    'address' => $street,
    'phone'   => $phone,
    'email'   => $email,
  ];
}
function HandleSR($result)
{
  if (isset($result->gRecaptchaResponse)) {
    return $result->gRecaptchaResponse;
  } elseif (isset($result->solution->gRecaptchaResponse)) {
    return $result->solution->gRecaptchaResponse;
  } elseif (isset($result->token)) {
    return $result->token;
  } elseif (isset($result->cookie)) {
    return $result->cookie;
  }
}

function SolveRecaptcha($site_key, $site, $fields, $mode = 'recaptchav2', $args = [])
{
  $base   = ['websiteKey' => $site_key, 'websiteURL' => $site];
  $fields = (is_array($fields) && count($fields) > 0) ? $fields + $base : $base;

  if (!empty($args['s_data'][1])) {
    $fields = array_merge($fields, ['enterprisePayload' => (object)[$args['s_data'][0] => $args['s_data'][1]]]);
  }

  $validModes = [
    'recaptchav2', 'recaptchav2proxyless', 'recaptchav2enterprise', 'recaptchav2enterpriseproxyless',
    'recaptchav3proxyless', 'recaptchav3', 'recaptchav3enterprise', 'recaptchav3enterpriseproxyless',
    'recaptchav3M1', 'hcaptchaturbo', 'hcaptchaproxyless', 'hcaptcha', 'hcaptchaenterprise',
    'hcaptchaenterpriseproxyless', 'mtcaptcha', 'mtcaptchaproxyless', 'anticloudflare', 'aws_waf',
  ];
  if (!in_array($mode, $validModes)) {
    ReturnMessage('Invalid mode');
  }

  $solver  = new \CapSolver\CapSolver('CAP-zzzzzzzz');
  $tries   = 0;
  $re_code = null;

  do {
    if ($tries === 8) ReturnMessage('No se pudo saltar el captcha');
    try {
      $re_code = HandleSR($solver->$mode($fields));
    } catch (\Exception $e) {}
    $tries++;
  } while ($re_code === null);

  return $re_code;
}
function GetResponse($status, $response){
  echo sprintf("%s - %s - %s - Tries: %s - T.Took: %ss<br><br>", $status, $GLOBALS['lista'], $response, $GLOBALS['tries'] ?? 0, round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']));
  DeleteCookies();
  die();
}

function ReturnMessage($MSG)
{
  GetResponse('DEAD', $MSG);
}

function CurlX_Headers_to_Lower($headers)
{
  $NewHeaders = [];
  foreach ($headers as $key => $value) {
    $NewHeaders[strtolower($key)] = $value;
  }
  return $NewHeaders;
}

function get_input_tags($html)
{
  try {
    $post_data = array();
    $dom = new DomDocument;
    @$dom->loadHTML($html);
    $dom->preserveWhiteSpace = false;
    $input_tags = $dom->getElementsByTagName('input');

    for ($i = 0; $i < $input_tags->length; $i++) {
      if (is_object($input_tags->item($i))) {
        $name = $value = '';
        $name_o = $input_tags->item($i)->attributes->getNamedItem('name');
        if ($name_o instanceof DOMAttr) {
          $name = $name_o->value;
          $value_o = $input_tags->item($i)->attributes->getNamedItem('value');
          if ($value_o instanceof DOMAttr) {
            $value = $value_o->value;
          }

          $post_data[$name] = $value;
        }
      }
    }
    return $post_data;
  } catch (Throwable $th) {
    return false;
  }
}

function DeleteCookies($path = './Require/Cache/'){
  if (is_dir($path)) {
    $globpath = glob($path . '*');
    if (count($globpath) >= 10) {
      array_map('unlink', $globpath);
    }
  }
}

function MissingTokens($Needed)
{
  static $request;
  $request++;
  foreach ($Needed as $key => $value) {
    if (empty($value)) {
      GetResponse('DEAD', "An error has occurred in request number $request. Token number $key is missing");
      break;
    }
  }
  return $request;
}

/**
 * Single logger class
 */
class Logger
{
  /**
   * Ansi codes array
   * @array ANSI_CODES
   */
  protected static $ANSI_CODES = array(
    "off"        => 0,
    "bold"       => 1,
    "italic"     => 3,
    "underline"  => 4,
    "blink"      => 5,
    "inverse"    => 7,
    "hidden"     => 8,
    "black"      => 30,
    "red"        => 31,
    "green"      => 32,
    "yellow"     => 33,
    "blue"       => 34,
    "magenta"    => 35,
    "cyan"       => 36,
    "white"      => 37,
    "black_bg"   => 40,
    "red_bg"     => 41,
    "green_bg"   => 42,
    "yellow_bg"  => 43,
    "blue_bg"    => 44,
    "magenta_bg" => 45,
    "cyan_bg"    => 46,
    "white_bg"   => 47
  );

  /** Check log type */
  private static function logType($type)
  {
    switch ($type) {
      case 1:
        return "<-";
      case 2:
        return "->";
      case 3:
        return "X";
      default:
        return $type;
    }
  }

  /** Print response from server in console */
  public static function log(string $text, string $color = "cyan", $type = 'INFO')
  {
    echo self::SetColor("[" . date("H:i:s") . "]::[" . self::logType($type) . "] # $text\n\r", $color);
  }

  /** Set string color name to change with an ansi code */
  public static function SetColor($str, $color)
  {
    $color_attrs = explode("+", $color);
    $ansi_str = "";
    foreach ($color_attrs as $attr) {
      $ansi_str .= "\033[" . self::$ANSI_CODES[$attr] . "m";
    }
    $ansi_str .= $str . "\033[" . self::$ANSI_CODES["off"] . "m";
    return $ansi_str;
  }

  public static function pretier($text, $title, $color = 'yellow')
  {
      $text = print_r($text, true);
      if (php_sapi_name() == 'cli') {
          echo self::SetColor("$------------$title------------$\n$text\n\n", $color);
    } else {
          echo '<h3><b>»»————// <i>' . $title . '</i> //————««</b></h3><pre>' . $text . '</pre>';
    }
  }
}

function GetCC($index = 'card')
{
  return $_SERVER['argv'][1] ?? $_GET[$index];
}


function LowestShipMagneto($arr)
{
  $amounts = [];
  for ($i = -1; $i < count($arr); $i++) {
    $amounts[$i] = $arr[$i]->amount;
  }

  $ret_arr = [];
  for ($i = 0; $i < count($amounts); $i++) {
    if ($amounts[$i] == min($amounts)) {
      $ret_arr = $arr[$i];
      break;
    }
  }
  return (array)$ret_arr;
}
function GetRandomWord($length = 10)
{
  $word = implode(array_merge(range(1, 9), range('a', 'z'), range('A', 'Z')));
  $Words = '';
  for ($i = 0; $i < $length; $i++) {
    $Words .= $word[rand(0, strlen($word) - 1)];
  }
  return $Words;
}
function GUID()
{
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function SuperMagAttr($curl)
{
  preg_match_all('/<script type="text\/x-magento-init">(.*?)<\/script>/s', $curl, $coincidencias);
  if (empty($coincidencias[0]) || $coincidencias === false) {
    return null;
  }

  $magneto_init = '';
  foreach ($coincidencias[1] as $coincidencia) {
    if (substr_count($coincidencia, '[data-role=swatch-options]')) {
      $magneto_init = $coincidencia;
      break;
    }
  }

  if (empty($magneto_init)) {
    return null;
  }

  $options = json_decode($magneto_init, 1)['[data-role=swatch-options]']['Magento_Swatches/js/swatch-renderer']['jsonConfig']['index'];
  $keys = array_keys($options);
  $super = $options[$keys[0]];

  $s_at = [];
  foreach ($super as $key => $value) {
    $s_at["super_attribute[{$key}]"] = $value;
  }
  return $s_at;
}