<?php

/** 
 * Example SAML authentication for PHP federated applications 
 * 
 * IMPORTANT: This is just the SAML gateway! Make sure you look at the actual 
 * 	IPP/IDS communication example this works together with, located in: 
 * 	docs/example_ipp_federated.php
 * 
 * @author Keith Palmer <keith@ConsoliBYTE.com>
 * 
 * @package QuickBooks
 * @subpackage docs
 */

//header('Content-Type: text/plain');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once dirname(__FILE__) . '/../QuickBooks.php';

// This is just for testing! Do not uncomment this unless you know what you're doing!
//$SAML = 'PHNhbWxwOlJlc3BvbnNlIElzc3VlSW5zdGFudD0iMjAxMC0wNC0yMlQxNDozMDo0Ni44MDJaIiBJRD0iWmJpeWVyTGExWGxPajdZejdxempwZlhYdGZrIiBWZXJzaW9uPSIyLjAiIHhtbG5zOnNhbWxwPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6cHJvdG9jb2wiPjxzYW1sOklzc3VlciB4bWxuczpzYW1sPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXNzZXJ0aW9uIj5JREZFRF9QUk9EX0lEUF9TUF9BUFA8L3NhbWw6SXNzdWVyPjxzYW1scDpTdGF0dXM+PHNhbWxwOlN0YXR1c0NvZGUgVmFsdWU9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDpzdGF0dXM6U3VjY2VzcyIvPjwvc2FtbHA6U3RhdHVzPjxzYW1sOkFzc2VydGlvbiBWZXJzaW9uPSIyLjAiIElzc3VlSW5zdGFudD0iMjAxMC0wNC0yMlQxNDozMDo0Ni44MzhaIiBJRD0iTmpmMnouQWZ3bFhXMUZxVU9PZmFFX2lkaWNQIiB4bWxuczpzYW1sPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXNzZXJ0aW9uIj48c2FtbDpJc3N1ZXI+SURGRURfUFJPRF9JRFBfU1BfQVBQPC9zYW1sOklzc3Vlcj48ZHM6U2lnbmF0dXJlIHhtbG5zOmRzPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjIj4KPGRzOlNpZ25lZEluZm8+CjxkczpDYW5vbmljYWxpemF0aW9uTWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8xMC94bWwtZXhjLWMxNG4jIi8+CjxkczpTaWduYXR1cmVNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjcnNhLXNoYTEiLz4KPGRzOlJlZmVyZW5jZSBVUkk9IiNOamYyei5BZndsWFcxRnFVT09mYUVfaWRpY1AiPgo8ZHM6VHJhbnNmb3Jtcz4KPGRzOlRyYW5zZm9ybSBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNlbnZlbG9wZWQtc2lnbmF0dXJlIi8+CjxkczpUcmFuc2Zvcm0gQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzEwL3htbC1leGMtYzE0biMiLz4KPC9kczpUcmFuc2Zvcm1zPgo8ZHM6RGlnZXN0TWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI3NoYTEiLz4KPGRzOkRpZ2VzdFZhbHVlPnp1ampTSUFHdnZQd01pUTdIV2ZOM1ZZTHI3WT08L2RzOkRpZ2VzdFZhbHVlPgo8L2RzOlJlZmVyZW5jZT4KPC9kczpTaWduZWRJbmZvPgo8ZHM6U2lnbmF0dXJlVmFsdWU+CmpzY3hPRnk2T3dwc0ZoNVJ3Wk5RQXBBRFBFWVcrZytKWDlwUEtrTnJ2c0ExYnVkRDJFb3Y4bjNSaDFHVTVwTU84RzM0YWNMZnJhTysKWWVMcWNpTnYvMkVQYzRKUUFqdnhuOWtiekN5eXMvaGlvZGk2QmN6eHJHRFVlZ1JWRUtLUnhieFNHOHVoUmdZd1FIdHQ4VW1FSUt3NApzbGlXUGM4SE9XUUxIZHk5bG9TUjVoVVpSRGMxVGpBSEJVQlFYdnNydXNKdDBGa1g3aHk3MVE0R2lrOE9NOUFFKzF6OEVzR2J6b1RwCnIweXlYUFBFZVlDMCtaRGZLUUZDZ081b2hJZEdZRWtJY1hRRjNxajQ1VVVlQUVnZVM3SmdGSzB3a2NWSVRIU1MxOFIxanlCQ3RFeWUKOEtnQWdKUEwzVjF2V3kzeTM2aWFxOUNNOUE0M2RLeDFaeEJ1aUE9PQo8L2RzOlNpZ25hdHVyZVZhbHVlPgo8L2RzOlNpZ25hdHVyZT48c2FtbDpTdWJqZWN0PjxzYW1sOk5hbWVJRCBGb3JtYXQ9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjEuMTpuYW1laWQtZm9ybWF0OnVuc3BlY2lmaWVkIj5iMjUwM2Y3OS0wOTFmLTRjNmQtYWJkZS0wZmJmNTQxYzM4ZjQ8L3NhbWw6TmFtZUlEPjxzYW1sOlN1YmplY3RDb25maXJtYXRpb24gTWV0aG9kPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6Y206YmVhcmVyIj48c2FtbDpTdWJqZWN0Q29uZmlybWF0aW9uRGF0YSBOb3RPbk9yQWZ0ZXI9IjIwMTAtMDQtMjJUMTQ6MzU6NDYuODM4WiIgUmVjaXBpZW50PSJodHRwczovL3NlY3VyZS5jb25zb2xpYnl0ZS5jb20vZGV2ZWwvc2FtbC90ZXN0LnBocCIvPjwvc2FtbDpTdWJqZWN0Q29uZmlybWF0aW9uPjwvc2FtbDpTdWJqZWN0PjxzYW1sOkNvbmRpdGlvbnMgTm90T25PckFmdGVyPSIyMDEwLTA0LTIyVDE0OjM1OjQ2LjgzOFoiIE5vdEJlZm9yZT0iMjAxMC0wNC0yMlQxNDoyODo0Ni44MzhaIj48c2FtbDpBdWRpZW5jZVJlc3RyaWN0aW9uPjxzYW1sOkF1ZGllbmNlPnBocC5jb25zb2xpYnl0ZS5wcm9kLWludHVpdC5pcHAucHJvZDwvc2FtbDpBdWRpZW5jZT48L3NhbWw6QXVkaWVuY2VSZXN0cmljdGlvbj48L3NhbWw6Q29uZGl0aW9ucz48c2FtbDpBdXRoblN0YXRlbWVudCBBdXRobkluc3RhbnQ9IjIwMTAtMDQtMjJUMTQ6MzA6NDYuODM4WiIgU2Vzc2lvbkluZGV4PSJOamYyei5BZndsWFcxRnFVT09mYUVfaWRpY1AiPjxzYW1sOkF1dGhuQ29udGV4dD48c2FtbDpBdXRobkNvbnRleHRDbGFzc1JlZj51cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YWM6Y2xhc3Nlczp1bnNwZWNpZmllZDwvc2FtbDpBdXRobkNvbnRleHRDbGFzc1JlZj48L3NhbWw6QXV0aG5Db250ZXh0Pjwvc2FtbDpBdXRoblN0YXRlbWVudD48c2FtbDpBdHRyaWJ1dGVTdGF0ZW1lbnQgeG1sbnM6eHM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hIj48c2FtbDpBdHRyaWJ1dGUgTmFtZUZvcm1hdD0idXJuOm9hc2lzOm5hbWVzOnRjOlNBTUw6Mi4wOmF0dHJuYW1lLWZvcm1hdDpiYXNpYyIgTmFtZT0iSW50dWl0LkZlZGVyYXRpb24ucmVhbG1JRFBzZXVkb255bSI+PHNhbWw6QXR0cmlidXRlVmFsdWUgeHNpOnR5cGU9InhzOnN0cmluZyIgeG1sbnM6eHNpPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxL1hNTFNjaGVtYS1pbnN0YW5jZSI+Yjc2NWZkM2QtYzJhNC00ZDEyLTlmNTUtMTAzMzdiMGQ3NWMzPC9zYW1sOkF0dHJpYnV0ZVZhbHVlPjwvc2FtbDpBdHRyaWJ1dGU+PHNhbWw6RW5jcnlwdGVkQXR0cmlidXRlPjx4ZW5jOkVuY3J5cHRlZERhdGEgVHlwZT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjRWxlbWVudCIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48eGVuYzpFbmNyeXB0aW9uTWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjYWVzMTI4LWNiYyIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIi8+PGRzOktleUluZm8geG1sbnM6ZHM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyMiPgo8eGVuYzpFbmNyeXB0ZWRLZXkgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48eGVuYzpFbmNyeXB0aW9uTWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjcnNhLTFfNSIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIi8+PHhlbmM6Q2lwaGVyRGF0YSB4bWxuczp4ZW5jPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGVuYyMiPjx4ZW5jOkNpcGhlclZhbHVlIHhtbG5zOnhlbmM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvMDQveG1sZW5jIyI+V1dLY3RxaVlOUUYrdTc3MklvUm5ONnAvZHlOUDYrU2FRZ0R2TE5wNEd4SEphR3l2a2kvYjEzNGs4OG9meVduM2sxT05qdHJXbm9MUApiRCttRFY5Q2lOeS9Qbk1NZEdWS0xGUjdFV1JFV1prNDVKck1jN2FnZkF3SE1TTGVCOGxXaFJ3R3NkQmdnNlJGU0c1aHdveThKZXUzClN0blIrS21VclNHMURtRVJweDg9PC94ZW5jOkNpcGhlclZhbHVlPjwveGVuYzpDaXBoZXJEYXRhPjwveGVuYzpFbmNyeXB0ZWRLZXk+PC9kczpLZXlJbmZvPjx4ZW5jOkNpcGhlckRhdGEgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48eGVuYzpDaXBoZXJWYWx1ZSB4bWxuczp4ZW5jPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGVuYyMiPjhUT3pyRjlUNUdqM3ZXdkh2NmM1UCttZE9TR3Qyd0N0ek11LzgxVmxUNC9NVEZmM1J1dXJ1U01JSTcvUUF4RUNvd3lIbHpUeWxyRzgKS2k0dEE1T3dzVkhvd3Fneitmbng3UTU4MTdQUyszKzBTR2JERkFwblQ5a3ZRVEZkZWZGS3d4UXhnL25kTUNHZCtzRDFEZWk0VEIyMwpJTXVpRkVURExNWDhqOCtjQWZKTDdUdTA4WkZIQVZTaWF1enM5bkNtUHVvZzcxR0tIcHpmSkdocDNvZXdGbnN6M010ZWVYUm8yWHVUCmNWTjhsOElnWUV5OHl2b2RPQTlNc3RpSnFWZWNYWVFLODR4NTJtUjdsOFpPb1JENHFaZzZsRzBaNHNnZDRjcVFUVlY1QUtvNldlS0oKbi9wL3NvUmhBOTB1TWMyMGl5c1JHd0doS0JoOENHZHlKOXV2c0h4c3cxVHVXUGRSM0N0VXB2NVlZejhxK0UzeWppV0VsVCtPaEhXOQpoYWN4b2x5cTA5M2Npbm9sdUQycTNLWjVjSkxWQndpcXV3TklJWkNvbzIvWHVaYjIrd0x3QmpyN1pkdDlUSUI5V1lRelcxcnJWTUpBCk9VMmFBOVNWdEhxcytqTitQMnVGODYwVWxsdEd5YmlxWjFiQ2MyOWk1VHR5Y000STVuWVhVcC9ET2FlZGZWTllQSlBpYmgzRVBoSDEKZVk5SDdDeENSWURYL1V5SkJiTUtldXozNEtWQ1ByeWN0TzFzQ1hQUmtlN0lIS3A4Z2NodjRLYjZ6UERhcEVJUFdEeUxtRzRXOCtnNgpWdnFoMVNsY1Q4TkRhdTF0UEZzOURtT3NNTkdGYTBrVDwveGVuYzpDaXBoZXJWYWx1ZT48L3hlbmM6Q2lwaGVyRGF0YT48L3hlbmM6RW5jcnlwdGVkRGF0YT48L3NhbWw6RW5jcnlwdGVkQXR0cmlidXRlPjxzYW1sOkF0dHJpYnV0ZSBOYW1lRm9ybWF0PSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXR0cm5hbWUtZm9ybWF0OmJhc2ljIiBOYW1lPSJyZWFsbUlEIj48c2FtbDpBdHRyaWJ1dGVWYWx1ZSB4c2k6dHlwZT0ieHM6c3RyaW5nIiB4bWxuczp4c2k9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hLWluc3RhbmNlIj4xNzM2NDI0Mzg8L3NhbWw6QXR0cmlidXRlVmFsdWU+PC9zYW1sOkF0dHJpYnV0ZT48c2FtbDpBdHRyaWJ1dGUgTmFtZUZvcm1hdD0idXJuOm9hc2lzOm5hbWVzOnRjOlNBTUw6Mi4wOmF0dHJuYW1lLWZvcm1hdDpiYXNpYyIgTmFtZT0idGFyZ2V0VXJsIj48c2FtbDpBdHRyaWJ1dGVWYWx1ZSB4c2k6dHlwZT0ieHM6c3RyaW5nIiB4bWxuczp4c2k9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hLWluc3RhbmNlIj5odHRwczovL3NlY3VyZS5jb25zb2xpYnl0ZS5jb20vZGV2ZWwvc2FtbC90ZXN0Mi5waHA8L3NhbWw6QXR0cmlidXRlVmFsdWU+PC9zYW1sOkF0dHJpYnV0ZT48L3NhbWw6QXR0cmlidXRlU3RhdGVtZW50Pjwvc2FtbDpBc3NlcnRpb24+PC9zYW1scDpSZXNwb25zZT4=';
$SAML = null;

// The type of gateway to create
$type = QuickBooks_IPP_Federator::TYPE_SAML;
//$type = QuickBooks_IPP_Federator::TYPE_OAUTH;		// This totally isn't supported yet

// The path to your private key file
$private_key = dirname(__FILE__) . '/data/example.ipp.key';

// A database connection string for logging requests/responses/debug information
$dsn = null;

// 
$callback = null;

// Create the new federator instance
$Federator = new QuickBooks_IPP_Federator($type, $private_key, $dsn, $callback);

// This provides helpful troubleshooting information 
//$Federator->useDebugMode(true);

// Let the SAML gateway handle the SAML authentication response
if ($Federator->handle($SAML))
{
	; // Success! The end-user will be forwarded automatically to the application URL.
}
else
{
	die('Oh no, something bad happened: ' . $Federator->errorNumber() . ': ' . $Federator->errorMessage());
}
