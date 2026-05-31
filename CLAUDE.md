# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CurlX is a PHP library/framework for automating HTTP workflows with integrated CAPTCHA solving. It wraps libcurl into a static utility class (`CurlX`) and bundles helper functions (`Main.php`) and CAPTCHA solver integrations (AntiCaptcha and CapSolver APIs). Scripts like `index.php` and `debug.php` are example implementations that demonstrate full checkout automation flows.

## Running Scripts

There is no build step — this is plain PHP. Run scripts directly:

```bash
php index.php
php debug.php
php test.php
php Require/examples/exampleRecaptchaV2.php
```

Card data is passed via CLI argument or `$_GET['card']`:

```bash
php index.php "4111111111111111|12|2026|123"
```

## Dependencies

Composer is used for the CapSolver package. The autoloader is at `Require/autoload.php` and is included by glob-loading everything in `Require/`. If dependencies are missing:

```bash
cd Require && composer install
```

## Architecture

### File Loading Convention

Scripts load all library files at once via glob, not individual requires:

```php
foreach (glob("Require/*.php") as $filename) { require_once $filename; }
```

This means all functions in `Main.php` and all classes (`CurlX`, `Logger`, CAPTCHA handlers) are globally available without explicit imports.

### CurlX — Static HTTP Client (`Require/CurlX.php`)

Entirely static class; every method call goes through `Prepare()` → optional `CheckParam()` → `Run()`. `Run()` returns a plain `object` with this shape:

```php
{
  success: bool,
  code: int,           // HTTP status code
  headers: {
    request_headers: array,
    response_headers: array
  },
  body: string,        // response body, or error message on failure
  error?: string,      // only on failure
  errno?: int          // only on failure
}
```

Cookies are stored as files in `Require/Cache/curlX_{name}.txt`. Call `CurlX::DeleteCookie()` or `DeleteCookies()` (from Main.php) to clean up. The UA is chosen randomly per-request from a hardcoded list in `UserAgent()`.

Proxy configuration is passed as an array: `['METHOD' => 'TUNNEL'|'CUSTOM', 'SERVER' => 'ip:port', 'AUTH' => 'user:pass']`.

### Main.php — Global Utility Functions

Procedural helpers, not a class. Key functions:

- **`g($str, $start, $end)`** / **`Revercap()`** — string extraction between two delimiters (forward and reverse). These are the primary HTML/JSON parsing tools used throughout scripts.
- **`Random_Dates()`** — calls `randomuser.me/api` to get a fake US identity; returns `[name, last, city, state, stateAB, zip, address, phone, email]`.
- **`SolveRecaptcha($site_key, $site, $fields, $mode, $args)`** — submits a CAPTCHA to CapSolver. The API key (`CAP-zzzzzzzz`) must be replaced with a real key. Supported modes: `recaptchav2`, `hcaptcha`, `recaptchav3`, `anticloudflare`, `aws_waf`, etc.
- **`GetResponse($status, $response)`** — terminal function; outputs result, calls `DeleteCookies()`, and `die()`s. All success/failure paths end here.
- **`MissingTokens($Needed)`** — validates that an array of extracted tokens are all non-empty; calls `GetResponse('DEAD', ...)` and halts if any are missing.
- **`GetCC()`** — reads the card string from `$_SERVER['argv'][1]` (CLI) or `$_GET['card']` (web).

### CAPTCHA Handling (`Require/anticaptcha.php` + individual handlers)

Two integrations coexist:

1. **AntiCaptcha.com** (`anticaptcha.php` base class + per-type files like `recaptchaV2.php`, `hcaptcha.php`, etc.) — uses the `AntiCaptchaTaskProtocol` interface. Each handler sets its parameters via setters then calls `createTask()` / `waitForResult()`.

2. **CapSolver** (`Require/0qwertyy/capsolver-php/`) — used by `SolveRecaptcha()` in Main.php. The `\CapSolver\CapSolver` class exposes named methods per CAPTCHA type (`->recaptchav2($fields)`, `->hcaptcha($fields)`, etc.).

`SolveRecaptcha()` retries up to 8 times and calls `ReturnMessage()` (→ `DEAD`) if it never succeeds.

### Global State in Scripts

Scripts use `$GLOBALS['lista']` (the card being tested) and `$GLOBALS['tries']` (retry counter). `GetResponse()` reads these globals when formatting output. Retry loops typically run up to 8 attempts with `goto` or `do/while`.

### Data Files

- `SafariUA.txt` — one UA string per line; consumed via `CurlX::GetRandVal('SafariUA.txt')`.
- `AddressUSA.txt` — one US address per line; read the same way.
