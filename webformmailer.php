<?php

declare(strict_types=1);

/**
 * webformmailer.php - GoDaddy legacy form-mail handler, updated for PHP 8.5.
 *
 * Saves the submitted form fields to a queue file in the hosting account's
 * data/ folder; the host's mail service picks that file up and e-mails it.
 *
 * Optional control fields a form can send:
 *   form_order     "alpha", or a comma separated list of field names to put first
 *   form_format    text (default) | csv | html | xml
 *   form_delivery  default (15 min) | digest | hourly | hourly_digest | daily | daily_digest
 *   redirect       page to send the visitor to afterwards (relative to the site root)
 *
 * Changes from the original script:
 *   - split()  (removed in PHP 7.0)                 -> explode()
 *   - while (list() = each())  (removed in PHP 8.0) -> foreach
 *   - a failed file write is handled instead of crashing with a TypeError
 *   - every variable is initialised; no "Undefined variable/array key" warnings
 *   - array values (fields named like "x[]") are joined instead of printing "Array"
 *   - field names/values are HTML-escaped in the .html format
 *   - only GET and POST requests are processed
 *
 * Requires PHP 8.1 or newer (tested on PHP 8.5).
 */

if (!isset($_SERVER['SPI'])) {
    exit;
}

/** Fields that control this script and are not part of the submitted data. */
const CONTROL_FIELDS = ['form_order', 'form_format', 'form_delivery', 'redirect'];

/**
 * Absolute URL on this site for the given path.
 */
function site_url(string $path = ''): string
{
    $https  = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
    $scheme = ($https !== '' && $https !== 'off') ? 'https' : 'http';
    $host   = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');

    return $scheme . '://' . $host . '/' . ltrim($path, '/');
}

/**
 * Put the fields named in $b (comma separated, case sensitive) first,
 * followed by all remaining fields in their submitted order.
 */
function customsort(array $a, string $b): array
{
    $data = [];
    foreach (explode(',', $b) as $orderitem) {
        if ($orderitem !== '' && isset($a[$orderitem])) {
            $data[$orderitem] = $a[$orderitem];
        }
    }
    foreach ($a as $key => $val) {
        $data[$key] = $val;
    }

    return $data;
}

function xmlentities(string $string): string
{
    return str_replace(
        ['&', '"', "'", '<', '>'],
        ['&amp;', '&quot;', '&apos;', '&lt;', '&gt;'],
        $string
    );
}

/**
 * Form values are strings, or arrays for fields named like "x[]".
 */
function field_value(mixed $value): string
{
    if (is_array($value)) {
        return implode(', ', array_map(field_value(...), $value));
    }

    return (string) $value;
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    header('Location: ' . site_url());
    exit("CRITICAL: we seem to be running outside of the norm.\nCRITICAL: Document root unavailable.\n");
}

$query_vars = match ($_SERVER['REQUEST_METHOD'] ?? '') {
    'GET'   => $_GET,
    'POST'  => $_POST,
    default => null,
};

if ($query_vars === null) {
    http_response_code(405);
    header('Allow: GET, POST');
    exit;
}

$t = time();

$formhomedir = explode('/', (string) preg_replace('/.*\/home\/content/', '', (string) $_SERVER['DOCUMENT_ROOT']));
if (count($formhomedir) <= 4) {
    $formhome = '/home/content/' . ($formhomedir[1] ?? '') . '/' . ($formhomedir[2] ?? '') . '/data/';
} else {
    $formhome = '/home/content/' . $formhomedir[1] . '/' . $formhomedir[2] . '/' . $formhomedir[3] . '/' . $formhomedir[4] . '/data/';
}

// Field order
$file_order = '.default';
$form_order = $query_vars['form_order'] ?? null;
if (is_string($form_order)) {
    if ($form_order === 'alpha') {
        uksort($query_vars, static fn (int|string $a, int|string $b): int => strnatcasecmp((string) $a, (string) $b));
        $file_order = '.alpha';
    } else {
        $query_vars = customsort($query_vars, $form_order);
        $file_order = '.custom';
    }
}

$file_format = match ($query_vars['form_format'] ?? null) {
    'csv'   => '.csv',
    'html'  => '.html',
    'xml'   => '.xml',
    default => '.text',
};

$file_interval = match ($query_vars['form_delivery'] ?? null) {
    'hourly'        => '.60m',
    'hourly_digest' => '.60mc',
    'daily'         => '.24h',
    'daily_digest'  => '.24hc',
    'digest'        => '.15mc',
    default         => '.15m',
};

$landing_page = $query_vars['redirect'] ?? '';
$landing_page = is_string($landing_page) ? (string) preg_replace('/[\x00-\x1F\x7F]/', '', $landing_page) : '';

// Submitted data without the control fields, values flattened to strings.
$fields = [];
foreach ($query_vars as $key => $val) {
    if (!in_array($key, CONTROL_FIELDS, true)) {
        $fields[$key] = field_value($val);
    }
}

$content = '';
switch ($file_format) {
    case '.csv':
        $csvkeys = [];
        $csvvals = [];
        foreach ($fields as $key => $val) {
            $csvkeys[] = "'" . escapeshellcmd((string) $key) . "'";
            $csvvals[] = "'" . escapeshellcmd($val) . "'";
        }
        $content = implode(',', $csvkeys) . "\n" . implode(',', $csvvals) . "\n";
        break;

    case '.html':
        $content = "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">\n";
        foreach ($fields as $key => $val) {
            $content .= "\t<tr>\n"
                . "\t\t<td><b>" . htmlspecialchars((string) $key) . "</b></td>\n"
                . "\t\t<td>" . htmlspecialchars($val) . "</td>\n"
                . "\t</tr>\n";
        }
        $content .= "</table>\n";
        break;

    case '.xml':
        $content = "<form>\n";
        foreach ($fields as $key => $val) {
            $content .= "\t<field>\n"
                . "\t\t<fieldname>" . xmlentities((string) $key) . "</fieldname>\n"
                . "\t\t<fieldvalue>" . xmlentities($val) . "</fieldvalue>\n"
                . "\t</field>\n";
        }
        $content .= "</form>\n";
        break;

    default: // .text
        foreach ($fields as $key => $val) {
            $content .= $key . ': ' . $val . "\n";
        }
        break;
}

$file = $formhome . 'form_' . $t . $file_order . $file_format . $file_interval;

// The failure is handled and logged here, so PHP's own warning must not be printed
// before the 500 status header is sent.
if (@file_put_contents($file, $content, LOCK_EX) === false) {
    error_log('webformmailer: could not write form data to ' . $file . ' - ' . (error_get_last()['message'] ?? 'unknown error'));
    http_response_code(500);
    exit("Sorry, your form could not be sent. Please try again later.\n");
}

header('Location: ' . site_url($landing_page));
exit;
