# CurlX

A PHP library for automating HTTP workflows with built-in CAPTCHA solving support. Wraps libcurl into a clean static API and bundles helper utilities for identity generation, string parsing, and response classification.

> Credit for the original CurlX script: [devblack](https://github.com/devblack)

---

## Requirements

- PHP 7.4+
- cURL extension enabled
- Composer (for CapSolver dependency)

## Setup

```bash
cd Require && composer install
```

Cookie cache files are written to `Require/Cache/` automatically (created on first use, requires write permission).

---

## Usage

Scripts receive card data via CLI argument or `$_GET['card']`:

```bash
php index.php "4111111111111111|12|2026|123"
```

To run one of the bundled CAPTCHA examples:

```bash
php Require/examples/exampleRecaptchaV2.php
```

---

## Library Structure

### `Require/CurlX.php` — HTTP Client

Static class. Every request follows `Prepare → CheckParam → Run` and returns a plain object:

```php
{
  success: bool,
  code: int,
  headers: { request_headers: array, response_headers: array },
  body: string
}
```

```php
$res = CurlX::Get($url, $headers, $cookieName, $proxy);
$res = CurlX::Post($url, $data, $headers, $cookieName, $proxy);
CurlX::Custom($url, 'PUT', $data, $headers, $cookieName, $proxy);
$res = CurlX::Run(); // after Custom()
```

Proxy format: `['METHOD' => 'TUNNEL', 'SERVER' => 'ip:port']` or `['METHOD' => 'CUSTOM', 'SERVER' => 'ip:port', 'AUTH' => 'user:pass']`.

Utility helpers: `CurlX::ParseString($str, $start, $end)`, `CurlX::CleanString($str)`, `CurlX::GetRandVal($file)`, `CurlX::Debug()`.

### `Require/Main.php` — Utilities

Procedural helpers loaded globally:

| Function | Purpose |
|---|---|
| `g($str, $start, $end)` | Extract substring between two delimiters |
| `Revercap($str, $end, $start)` | Same extraction, reversed (right-to-left) |
| `Random_Dates()` | Fetch a random US identity from randomuser.me |
| `SolveRecaptcha($key, $url, $fields, $mode)` | Submit CAPTCHA to CapSolver; retry up to 8× |
| `MissingTokens($array)` | Halt with DEAD if any value is empty |
| `GetResponse($status, $message)` | Print result, delete cookies, and exit |
| `IsLive($body, $responses)` | Check if response body contains a live indicator |
| `GetCC()` | Read card from CLI arg or `$_GET['card']` |
| `Logger::log($text, $color, $type)` | ANSI-colored console output |

### CAPTCHA Solving

Two integrations are available:

- **CapSolver** — used by `SolveRecaptcha()`. Set your API key in `Main.php` (`CAP-...`). Supported modes: `recaptchav2`, `recaptchav2proxyless`, `recaptchav3`, `hcaptcha`, `hcaptchaproxyless`, `anticloudflare`, `aws_waf`, and more.
- **AntiCaptcha** — individual handler classes in `Require/` (`recaptchaV2.php`, `hcaptcha.php`, `geetest.php`, etc.) extending the base `Anticaptcha` class. See `Require/examples/` for usage.

### Data Files

- `SafariUA.txt` — Safari user-agent strings, one per line
- `AddressUSA.txt` — US addresses, one per line

Read a random line from either with `CurlX::GetRandVal('SafariUA.txt')`.
