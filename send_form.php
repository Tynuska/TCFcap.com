<?php
// v.1.34-L
/* =============================================================
 * ===  USER SETTINGS  =========================================
 * ============================================================= */
// mail configurations / komu se to posílá 1. krok uprav
$recipientTo = ['contact@tcfcap.com'];
// $recipientCc = ['jan.korecky@rvlt.digital', 'office@tcfcap.com', 'tereza.stachova@rvlt.digital']; // kopie 2. krok uprav
$recipientBcc = ['petr.bryx@rvlt.digital','gabriela.stebelova@rvlt.digital']; // skrytá kopie 3. krok uprav

// sender email + name
$senderEmail = 'noreply@tcfcap.com';
$senderName = '';

// form fields
$fieldsDef = [
    'name' => 'Contact-5-Name',
    'email' => 'Contact-5-Email',
    'phone' => 'Contact-5-Phone',
    'message' => 'Contact-5-Message',
    'files' => 'files',                     // attachments (must by array in form name="files[]")
    'lang' => 'lang',                       // required language (form hidden input - en/cs/..)
    // 'stdmail'                            // honeypot
];

// fields translations
$fieldsTrans = [
    'en' => [
        'subject' => 'Contact web form',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'message' => 'Message',
    ],
    'cs' => [
        'subject' => 'Kontakt web formulář',
        'name' => 'Jméno',
        'email' => 'Email',
        'phone' => 'Telefon',
        'message' => 'Zpráva',
    ],
];

// default language
$defaultLang = 'en';
// mail type: true => HTML version
$htmlContent = false;

/* =============================================================
 * ===  CODE  ==================================================
 * ============================================================= */
if (
    // required inputs
    isset($_POST[$fieldsDef['name']]) && isset($_POST[$fieldsDef['email']]) && isset($_POST[$fieldsDef['message']])
    // honeypot
    && isset($_POST['stdmail']) && empty($_POST['stdmail'])
) {
    // language
    $lang = $_POST[$fieldsDef['lang']] ?? $defaultLang;
    if (!isset($fieldsTrans[$lang])) {
        $lang = $defaultLang;
    }

    // post data
    $attachments = $_FILES[$fieldsDef['files']] ?? [];

    // body / message
    if ($htmlContent) {
        // html version
        $dataBody = '
<html><body style="display:block;width:100%;border:0 solid rgba(229,66,66,0);background-color:#FFFFFF">
	<div style="position:static;max-width:1140px;margin:0 auto;">
		<div style="background-color: #24343f">
			<div style="height:40px;width:100vh;border-width:0;display:flex;justify-content:center;align-items:center">'
/*
                . '<img style="width: 150px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMjI1cHgiIGhlaWdodD0iNTNweCIgdmlld0JveD0iMCAwIDIyNSA1MyIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj4KICAgIDx0aXRsZT5UQ0ZfdjJfQmVpZ2U8L3RpdGxlPgogICAgPGRlZnM+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtMSIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtMyIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtNSIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtNyIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtOSIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICAgICAgPHJlY3QgaWQ9InBhdGgtMTEiIHg9IjAiIHk9IjAiIHdpZHRoPSIyMjUiIGhlaWdodD0iNTIuMzE0NzU4NSI+PC9yZWN0PgogICAgICAgIDxyZWN0IGlkPSJwYXRoLTEzIiB4PSIwIiB5PSIwIiB3aWR0aD0iMjI1IiBoZWlnaHQ9IjUyLjMxNDc1ODUiPjwvcmVjdD4KICAgICAgICA8cmVjdCBpZD0icGF0aC0xNSIgeD0iMCIgeT0iMCIgd2lkdGg9IjIyNSIgaGVpZ2h0PSI1Mi4zMTQ3NTg1Ij48L3JlY3Q+CiAgICA8L2RlZnM+CiAgICA8ZyBpZD0iV2ViIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0iVENGX0xhbmRpbmctUGFnZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTEzNS4wMDAwMDAsIC03NS4wMDAwMDApIj4KICAgICAgICAgICAgPGcgaWQ9IlRDRl92Ml9CZWlnZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTM1LjAwMDAwMCwgNzUuMDAwMDAwKSI+CiAgICAgICAgICAgICAgICA8ZyBpZD0iQ2xpcHBlZCI+CiAgICAgICAgICAgICAgICAgICAgPG1hc2sgaWQ9Im1hc2stMiIgZmlsbD0id2hpdGUiPgogICAgICAgICAgICAgICAgICAgICAgICA8dXNlIHhsaW5rOmhyZWY9IiNwYXRoLTEiPjwvdXNlPgogICAgICAgICAgICAgICAgICAgIDwvbWFzaz4KICAgICAgICAgICAgICAgICAgICA8ZyBpZD0iU1ZHSURfMV8iPjwvZz4KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTYwLjgwNDIzNCwzMC4yMjI2NTQ4IEMxNTkuNDYyODMsMzAuMjIyNjU0OCAxNTguMzY3ODA2LDI5Ljc1NzI2OTcgMTU3LjQ2NDQxMiwyOC44MjY0OTk2IEMxNTYuNTYxMDE3LDI3Ljg5NTcyOTQgMTU2LjEyMzAwOCwyNi43NzMzMzAxIDE1Ni4xMjMwMDgsMjUuNDA0NTUwNCBMMTU2LjEyMzAwOCwyNS4zNzcxNzQ4IEMxNTYuMTIzMDA4LDI0LjAzNTc3MDggMTU2LjU2MTAxNywyMi44ODU5OTU5IDE1Ny40NjQ0MTIsMjEuOTU1MjI1NyBDMTU4LjM2NzgwNiwyMS4wMjQ0NTU1IDE1OS40OTAyMDYsMjAuNTU5MDcwNCAxNjAuODMxNjEsMjAuNTU5MDcwNCBDMTYxLjYyNTUwMiwyMC41NTkwNzA0IDE2Mi4yODI1MTYsMjAuNjk1OTQ4NCAxNjIuODU3NDA0LDIwLjk0MjMyODggQzE2My40MDQ5MTUsMjEuMTg4NzA5MSAxNjMuOTUyNDI3LDIxLjU3MTk2NzQgMTY0LjQ3MjU2NCwyMi4wMzczNTI1IEwxNjMuNzYwNzk4LDIyLjgwMzg2OTEgQzE2Mi44NTc0MDQsMjEuOTU1MjI1NyAxNjEuODk5MjU4LDIxLjUxNzIxNjIgMTYwLjgzMTYxLDIxLjUxNzIxNjIgQzE1OS43OTEzMzcsMjEuNTE3MjE2MiAxNTguOTQyNjk0LDIxLjg3MzA5ODkgMTU4LjI1ODMwNCwyMi42MTIyMzk5IEMxNTcuNTczOTE0LDIzLjM1MTM4MDkgMTU3LjIxODAzMSwyNC4yNTQ3NzU1IDE1Ny4yMTgwMzEsMjUuMzIyNDIzNyBMMTU3LjIxODAzMSwyNS4zNDk3OTkyIEMxNTcuMjE4MDMxLDI2LjQ0NDgyMyAxNTcuNTczOTE0LDI3LjM0ODIxNzUgMTU4LjI1ODMwNCwyOC4wODczNTg2IEMxNTguOTQyNjk0LDI4LjgyNjQ5OTYgMTU5Ljc5MTMzNywyOS4yMDk3NTc5IDE2MC44MzE2MSwyOS4yMDk3NTc5IEMxNjEuNDMzODczLDI5LjIwOTc1NzkgMTYxLjk1NDAwOSwyOS4xMDAyNTU1IDE2Mi40MTkzOTQsMjguODgxMjUwOCBDMTYyLjg4NDc3OSwyOC42NjIyNDYgMTYzLjM1MDE2NCwyOC4zMzM3Mzg5IDE2My44MTU1NDksMjcuODY4MzUzOCBMMTY0LjQ5OTkzOSwyOC41NTI3NDM2IEMxNjMuOTc5ODAzLDI5LjEwMDI1NTUgMTYzLjQwNDkxNSwyOS41MTA4ODk0IDE2Mi44MzAwMjgsMjkuNzg0NjQ1MyBDMTYyLjI4MjUxNiwzMC4wODU3NzY5IDE2MS41OTgxMjYsMzAuMjIyNjU0OCAxNjAuODA0MjM0LDMwLjIyMjY1NDgiIGlkPSJQYXRoIiBmaWxsPSIjRjdFMENFIiBmaWxsLXJ1bGU9Im5vbnplcm8iIG1hc2s9InVybCgjbWFzay0yKSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTE3MC4xOTQwNjMsMjYuNjM2NDUyMSBMMTc0LjQzNzI3OSwyNi42MzY0NTIxIEwxNzIuMzI5MzU5LDIxLjg3MzA5ODkgTDE3MC4xOTQwNjMsMjYuNjM2NDUyMSBaIE0xNjcuNTkzMzgxLDMwLjA1ODQwMTMgTDE3MS44MzY1OTgsMjAuNjQxMTk3MiBMMTcyLjgyMjExOSwyMC42NDExOTcyIEwxNzcuMDY1MzM2LDMwLjA1ODQwMTMgTDE3NS45NDI5MzcsMzAuMDU4NDAxMyBMMTc0Ljg0NzkxMywyNy41OTQ1OTc5IEwxNjkuNzgzNDI5LDI3LjU5NDU5NzkgTDE2OC42ODg0MDUsMzAuMDU4NDAxMyBMMTY3LjU5MzM4MSwzMC4wNTg0MDEzIEwxNjcuNTkzMzgxLDMwLjA1ODQwMTMgWiIgaWQ9IlNoYXBlIiBmaWxsPSIjRjdFMENFIiBmaWxsLXJ1bGU9Im5vbnplcm8iPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxnIGlkPSJDbGlwcGVkIj4KICAgICAgICAgICAgICAgICAgICA8bWFzayBpZD0ibWFzay00IiBmaWxsPSJ3aGl0ZSI+CiAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgeGxpbms6aHJlZj0iI3BhdGgtMyI+PC91c2U+CiAgICAgICAgICAgICAgICAgICAgPC9tYXNrPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJTVkdJRF8wMDAwMDE0NzIwMzU3NTY2NDM4OTk3NTg4MDAwMDAwMjgzMjk0NzI1MzE4MzUyOTkwMl8iPjwvZz4KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTgxLjg1NjA2NSwyNS44MTUxODQzIEwxODQuMTU1NjE1LDI1LjgxNTE4NDMgQzE4NC45MjIxMzIsMjUuODE1MTg0MyAxODUuNTUxNzcsMjUuNjIzNTU1MiAxODYuMDE3MTU1LDI1LjI0MDI5NjkgQzE4Ni40ODI1NCwyNC44NTcwMzg2IDE4Ni43MDE1NDUsMjQuMzY0Mjc3OSAxODYuNzAxNTQ1LDIzLjc2MjAxNDggTDE4Ni43MDE1NDUsMjMuNzM0NjM5MyBDMTg2LjcwMTU0NSwyMy4wNzc2MjUgMTg2LjQ4MjU0LDIyLjU4NDg2NDMgMTg2LjAxNzE1NSwyMi4yMjg5ODE2IEMxODUuNTc5MTQ2LDIxLjg3MzA5ODkgMTg0Ljk0OTUwNywyMS43MDg4NDU0IDE4NC4xODI5OTEsMjEuNzA4ODQ1NCBMMTgxLjgyODY5LDIxLjcwODg0NTQgTDE4MS44Mjg2OSwyNS44MTUxODQzIEwxODEuODU2MDY1LDI1LjgxNTE4NDMgWiBNMTgwLjc4ODQxNywzMC4wNTg0MDEzIEwxODAuNzg4NDE3LDIwLjcyMzMyNCBMMTg0LjI2NTExNywyMC43MjMzMjQgQzE4NS4zMzI3NjYsMjAuNzIzMzI0IDE4Ni4xNTQwMzMsMjAuOTk3MDc5OSAxODYuNzgzNjcyLDIxLjUxNzIxNjIgQzE4Ny40MTMzMTEsMjIuMDM3MzUyNSAxODcuNzQxODE4LDIyLjc3NjQ5MzUgMTg3Ljc0MTgxOCwyMy42Nzk4ODgxIEwxODcuNzQxODE4LDIzLjcwNzI2MzcgQzE4Ny43NDE4MTgsMjQuNjY1NDA5NCAxODcuMzg1OTM1LDI1LjQzMTkyNiAxODYuNzAxNTQ1LDI1Ljk1MjA2MjMgQzE4Ni4wMTcxNTUsMjYuNDcyMTk4NiAxODUuMTQxMTM2LDI2Ljc0NTk1NDUgMTg0LjEwMDg2NCwyNi43NDU5NTQ1IEwxODEuODI4NjksMjYuNzQ1OTU0NSBMMTgxLjgyODY5LDMwLjAzMTAyNTcgTDE4MC43ODg0MTcsMzAuMDMxMDI1NyBMMTgwLjc4ODQxNywzMC4wNTg0MDEzIFoiIGlkPSJTaGFwZSIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBtYXNrPSJ1cmwoI21hc2stNCkiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDwvZz4KICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUiIGZpbGw9IiNGN0UwQ0UiIGZpbGwtcnVsZT0ibm9uemVybyIgeD0iMTkxLjYyOTE1MiIgeT0iMjAuNzIzMzI0IiB3aWR0aD0iMS4wNDAyNzI1NCIgaGVpZ2h0PSI5LjMzNTA3NzI2Ij48L3JlY3Q+CiAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iUGF0aCIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBwb2ludHM9IjE5OS42MjI4MjUgMzAuMDU4NDAxMyAxOTkuNjIyODI1IDIxLjY4MTQ2OTggMTk2LjQ3NDYzMiAyMS42ODE0Njk4IDE5Ni40NzQ2MzIgMjAuNzIzMzI0IDIwMy44MTEyOTEgMjAuNzIzMzI0IDIwMy44MTEyOTEgMjEuNjgxNDY5OCAyMDAuNjYzMDk4IDIxLjY4MTQ2OTggMjAwLjY2MzA5OCAzMC4wNTg0MDEzIj48L3BvbHlnb24+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMjA4LjEwOTI1OSwyNi42MzY0NTIxIEwyMTIuMzUyNDc2LDI2LjYzNjQ1MjEgTDIxMC4yNDQ1NTUsMjEuODczMDk4OSBMMjA4LjEwOTI1OSwyNi42MzY0NTIxIFogTTIwNS41MDg1NzgsMzAuMDU4NDAxMyBMMjA5Ljc1MTc5NSwyMC42NDExOTcyIEwyMTAuNzM3MzE2LDIwLjY0MTE5NzIgTDIxNC45ODA1MzMsMzAuMDU4NDAxMyBMMjEzLjg1ODEzNCwzMC4wNTg0MDEzIEwyMTIuNzYzMTEsMjcuNTk0NTk3OSBMMjA3LjY5ODYyNSwyNy41OTQ1OTc5IEwyMDYuNjAzNjAxLDMwLjA1ODQwMTMgTDIwNS41MDg1NzgsMzAuMDU4NDAxMyBMMjA1LjUwODU3OCwzMC4wNTg0MDEzIFoiIGlkPSJTaGFwZSIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iUGF0aCIgZmlsbD0iI0Y2RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBwb2ludHM9IjIxOC43MDM2MTQgMjAuNzIzMzI0IDIxOC43MDM2MTQgMzAuMDU4NDAxMyAyMjUgMzAuMDU4NDAxMyAyMjUgMjkuMTAwMjU1NSAyMTkuNzcxMjYyIDI5LjEwMDI1NTUgMjE5Ljc3MTI2MiAyMC43MjMzMjQiPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUiIGZpbGw9IiNGN0UwQ0UiIGZpbGwtcnVsZT0ibm9uemVybyIgeD0iMTcuNjU3MjU3NiIgeT0iMS40MjM1MzA4NCIgd2lkdGg9IjEzLjc5NzI5ODkiIGhlaWdodD0iNDkuNDY3Njk2OCI+PC9yZWN0PgogICAgICAgICAgICAgICAgPGcgaWQ9Ikdyb3VwIj4KICAgICAgICAgICAgICAgICAgICA8ZyBpZD0iQ2xpcHBlZCI+CiAgICAgICAgICAgICAgICAgICAgICAgIDxtYXNrIGlkPSJtYXNrLTYiIGZpbGw9IndoaXRlIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgeGxpbms6aHJlZj0iI3BhdGgtNSI+PC91c2U+CiAgICAgICAgICAgICAgICAgICAgICAgIDwvbWFzaz4KICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IlNWR0lEXzAwMDAwMTc2NzM4MDA0NzI0MDg4OTcxOTgwMDAwMDE0Nzc5MTkxNjg4Nzc5Nzk0MzM5XyI+PC9nPgogICAgICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNy45NjYyOTc2LDEuNDIzNTMwODQgTDAsMS40MjM1MzA4NCBMMCwxNi42OTkxMTE4IEMwLDE2LjY5OTExMTggMC4wODIxMjY3Nzk0LDE2LjM0MzIyOTEgMC4wODIxMjY3Nzk0LDE2LjMxNTg1MzUgQzAuNzY2NTE2NjA4LDEzLjg1MjA1MDEgMy4xNDgxOTMyMSw0LjcwODYwMjAyIDEzLjgyNDY3NDUsMS41MzMwMzMyMiBDMTMuODUyMDUwMSwxLjUzMzAzMzIyIDE0LjA3MTA1NDksMS40NTA5MDY0NCAxNC4wOTg0MzA1LDEuNDUwOTA2NDQgTDcuOTY2Mjk3NiwxLjQ1MDkwNjQ0IEw3Ljk2NjI5NzYsMS40MjM1MzA4NCBaIiBpZD0iUGF0aCIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBtYXNrPSJ1cmwoI21hc2stNikiPjwvcGF0aD4KICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IkNsaXBwZWQiPgogICAgICAgICAgICAgICAgICAgICAgICA8bWFzayBpZD0ibWFzay04IiBmaWxsPSJ3aGl0ZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8dXNlIHhsaW5rOmhyZWY9IiNwYXRoLTciPjwvdXNlPgogICAgICAgICAgICAgICAgICAgICAgICA8L21hc2s+CiAgICAgICAgICAgICAgICAgICAgICAgIDxnIGlkPSJTVkdJRF8wMDAwMDE3NjczODAwNDcyNDA4ODk3MTk4MDAwMDAxNDc3OTE5MTY4ODc3OTc5NDMzOV8iPjwvZz4KICAgICAgICAgICAgICAgICAgICAgICAgPHBhdGggZD0iTTkxLjc2Mjk4ODIsMjUuODQyNTU5OSBMOTcuMjY1NDgyNCwzLjkxNDcwOTgyIEM5Mi4wMzY3NDQxLDEuMzE0MDI4NDcgODYuMTIzNjE2LDAgNzkuNzE3NzI3MiwwIEM3Ni4yNDEwMjY5LDAgNzMuMDY1NDU4MSwwLjUyMDEzNjI3IDcwLjE5MTAyMDgsMS40NzgyODIwMyBDNzAuMzAwNTIzMiwxLjQ3ODI4MjAzIDcwLjM4MjY1LDEuNDc4MjgyMDMgNzAuNDkyMTUyMywxLjQ3ODI4MjAzIEM3NS42MTEzODgyLDEuNDc4MjgyMDMgODEuMjIzMzg0OCw1LjY5NDEyMzM3IDkxLjcwODIzNywyNS44MTUxODQzIEM5MS43MzU2MTI2LDI1LjgxNTE4NDMgOTEuNzM1NjEyNiwyNS44NDI1NTk5IDkxLjc2Mjk4ODIsMjUuODQyNTU5OSIgaWQ9IlBhdGgiIGZpbGw9IiNGN0UwQ0UiIGZpbGwtcnVsZT0ibm9uemVybyIgbWFzaz0idXJsKCNtYXNrLTgpIj48L3BhdGg+CiAgICAgICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJDbGlwcGVkIj4KICAgICAgICAgICAgICAgICAgICAgICAgPG1hc2sgaWQ9Im1hc2stMTAiIGZpbGw9IndoaXRlIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgeGxpbms6aHJlZj0iI3BhdGgtOSI+PC91c2U+CiAgICAgICAgICAgICAgICAgICAgICAgIDwvbWFzaz4KICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IlNWR0lEXzAwMDAwMTc2NzM4MDA0NzI0MDg4OTcxOTgwMDAwMDE0Nzc5MTkxNjg4Nzc5Nzk0MzM5XyI+PC9nPgogICAgICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNzMuNTg1NTk0NCwzNC40Mzg0OTYyIEM2Ny40NTM0NjE1LDI3Ljg0MDk3ODIgNjQuMDg2MjYzNSwxOS4zNTQ1NDQzIDY0LjA4NjI2MzUsMTAuNDg0ODUyMiBDNjQuMDg2MjYzNSw3LjQ0NjE2MTMzIDY0Ljc3MDY1MzQsNS4wNjQ0ODQ3MyA2Ni4wNTczMDYyLDMuNTMxNDUxNTEgQzY2LjE2NjgwODYsMy4zOTQ1NzM1NSA2Ni4wODQ2ODE4LDMuNTA0MDc1OTIgNjYuMzg1ODEzNCwzLjE3NTU2ODggQzU5LjE1ODY1NjgsNy4xNDUwMjk4MSA1NC45MTU0Mzk4LDE0LjY3MzMxNzkgNTQuOTE1NDM5OCwyNC40NzM3ODAzIEM1NC45MTU0Mzk4LDMyLjU0OTU4MDIgNTcuMzc5MjQzMiwzOS41MDI5ODA5IDYyLjA2MDQ2OTYsNDQuNTQwMDkgQzY2Ljc5NjQ0NzMsNDkuNjMxOTUwNCA3My4zMzkyMTQsNTIuMzQyMTM0MSA4MS4wMzE3NTU3LDUyLjM0MjEzNDEgQzg4LjA5NDY1ODcsNTIuMzQyMTM0MSA5NC44NTY0MzAyLDQ5LjE2NjU2NTMgOTguNjM0MjYyMSw0NC4yNjYzMzQxIEM5OC42NjE2Mzc3LDQ0LjIzODk1ODUgOTguNjg5MDEzMyw0NC4xODQyMDczIDk4LjcxNjM4ODksNDQuMTU2ODMxNyBDOTguNjYxNjM3Nyw0NC4xNTY4MzE3IDk4LjYwNjg4NjUsNDQuMTg0MjA3MyA5OC41MjQ3NTk3LDQ0LjE4NDIwNzMgQzk3LjI5Mjg1OCw0NC40MDMyMTIxIDk1Ljk1MTQ1MzksNDQuNDg1MzM4OCA5NC44MjkwNTQ2LDQ0LjQ4NTMzODggQzg3LjI0NjAxNTMsNDQuNDMwNTg3NyA3OS40OTg3MjI1LDQwLjc4OTYzMzggNzMuNTg1NTk0NCwzNC40Mzg0OTYyIiBpZD0iUGF0aCIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBtYXNrPSJ1cmwoI21hc2stMTApIj48L3BhdGg+CiAgICAgICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZSIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiB4PSIxMTAuNDg3ODk0IiB5PSIxLjQyMzUzMDg0IiB3aWR0aD0iMTMuNzk3Mjk4OSIgaGVpZ2h0PSI0OS40Njc2OTY4Ij48L3JlY3Q+CiAgICAgICAgICAgICAgICA8ZyBpZD0iR3JvdXAiPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJDbGlwcGVkIj4KICAgICAgICAgICAgICAgICAgICAgICAgPG1hc2sgaWQ9Im1hc2stMTIiIGZpbGw9IndoaXRlIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgeGxpbms6aHJlZj0iI3BhdGgtMTEiPjwvdXNlPgogICAgICAgICAgICAgICAgICAgICAgICA8L21hc2s+CiAgICAgICAgICAgICAgICAgICAgICAgIDxnIGlkPSJTVkdJRF8wMDAwMDA3MjI3OTI2NDY4MzE5NzQ5MjExMDAwMDAxMDEyNDA3MjEyNDI1NjY2MzE4OV8iPjwvZz4KICAgICAgICAgICAgICAgICAgICAgICAgPHBhdGggZD0iTTQxLjE0NTUxNjUsMS40MjM1MzA4NCBMNDkuMTExODE0MSwxLjQyMzUzMDg0IEw0OS4xMTE4MTQxLDE2LjY5OTExMTggQzQ5LjExMTgxNDEsMTYuNjk5MTExOCA0OS4wMjk2ODczLDE2LjM0MzIyOTEgNDkuMDI5Njg3MywxNi4zMTU4NTM1IEM0OC4zNDUyOTc1LDEzLjg1MjA1MDEgNDUuOTYzNjIwOSw0LjcwODYwMjAyIDM1LjI4NzEzOTYsMS41MzMwMzMyMiBDMzUuMjU5NzY0LDEuNTMzMDMzMjIgMzUuMDQwNzU5MiwxLjQ1MDkwNjQ0IDM1LjAxMzM4MzYsMS40NTA5MDY0NCBMNDEuMTQ1NTE2NSwxLjQ1MDkwNjQ0IEw0MS4xNDU1MTY1LDEuNDIzNTMwODQgWiIgaWQ9IlBhdGgiIGZpbGw9IiNGN0UwQ0UiIGZpbGwtcnVsZT0ibm9uemVybyIgbWFzaz0idXJsKCNtYXNrLTEyKSI+PC9wYXRoPgogICAgICAgICAgICAgICAgICAgIDwvZz4KICAgICAgICAgICAgICAgICAgICA8ZyBpZD0iQ2xpcHBlZCI+CiAgICAgICAgICAgICAgICAgICAgICAgIDxtYXNrIGlkPSJtYXNrLTE0IiBmaWxsPSJ3aGl0ZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8dXNlIHhsaW5rOmhyZWY9IiNwYXRoLTEzIj48L3VzZT4KICAgICAgICAgICAgICAgICAgICAgICAgPC9tYXNrPgogICAgICAgICAgICAgICAgICAgICAgICA8ZyBpZD0iU1ZHSURfMDAwMDAwNzIyNzkyNjQ2ODMxOTc0OTIxMTAwMDAwMTAxMjQwNzIxMjQyNTY2NjMxODlfIj48L2c+CiAgICAgICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMzcuMDQyMjE5LDEuNDIzNTMwODQgTDE0NS41MDEyNzgsMS40MjM1MzA4NCBMMTQ1LjUwMTI3OCwxNC40ODE2ODg4IEMxNDUuNTAxMjc4LDE0LjQ4MTY4ODggMTQ1LjQxOTE1MSwxNC4xODA1NTcyIDE0NS40MTkxNTEsMTQuMTUzMTgxNyBDMTQ0LjY4MDAxLDEyLjA0NTI2MSAxNDIuMTg4ODMxLDQuMjE1ODQxMzQgMTMwLjgyNzk2LDEuNDc4MjgyMDMgQzEzMC44MDA1ODQsMS40NzgyODIwMyAxMzAuNTU0MjA0LDEuNDIzNTMwODQgMTMwLjUyNjgyOCwxLjM5NjE1NTI1IEwxMzcuMDQyMjE5LDEuMzk2MTU1MjUgTDEzNy4wNDIyMTksMS40MjM1MzA4NCBaIiBpZD0iUGF0aCIgZmlsbD0iI0Y3RTBDRSIgZmlsbC1ydWxlPSJub256ZXJvIiBtYXNrPSJ1cmwoI21hc2stMTQpIj48L3BhdGg+CiAgICAgICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJDbGlwcGVkIj4KICAgICAgICAgICAgICAgICAgICAgICAgPG1hc2sgaWQ9Im1hc2stMTYiIGZpbGw9IndoaXRlIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgeGxpbms6aHJlZj0iI3BhdGgtMTUiPjwvdXNlPgogICAgICAgICAgICAgICAgICAgICAgICA8L21hc2s+CiAgICAgICAgICAgICAgICAgICAgICAgIDxnIGlkPSJTVkdJRF8wMDAwMDA3MjI3OTI2NDY4MzE5NzQ5MjExMDAwMDAxMDEyNDA3MjEyNDI1NjY2MzE4OV8iPjwvZz4KICAgICAgICAgICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzNy4xNzkwOTcsMTkuNDM2NjcxMSBDMTM0LjU1MTA0LDIyLjAzNzM1MjUgMTMwLjc3MzIwOCwyNC4yMDAwMjQzIDEyNy40MDYwMSwyNS4xODU1NDU3IEMxMjcuMjQxNzU3LDI1LjI0MDI5NjkgMTI3LjA3NzUwMywyNS4yNjc2NzI1IDEyNi45NDA2MjUsMjUuMzIyNDIzNyBDMTI3LjEwNDg3OSwyNS4zNDk3OTkyIDEyNy4yNjkxMzIsMjUuNDA0NTUwNCAxMjcuNDA2MDEsMjUuNDU5MzAxNiBDMTMwLjc3MzIwOCwyNi40NDQ4MjMgMTM0LjU3ODQxNiwyOC42MDc0OTQ4IDEzNy4xNzkwOTcsMzEuMjA4MTc2MiBDMTM3LjI4ODYsMzEuMzE3Njc4NSAxMzcuMzcwNzI2LDMxLjM5OTgwNTMgMTM3LjQ4MDIyOSwzMS41MDkzMDc3IEwxMzcuNDgwMjI5LDI1LjI5NTA0ODEgTDEzNy40ODAyMjksMTkuMTM1NTM5NiBDMTM3LjM3MDcyNiwxOS4yMTc2NjY0IDEzNy4yNjEyMjQsMTkuMzI3MTY4OCAxMzcuMTc5MDk3LDE5LjQzNjY3MTEiIGlkPSJQYXRoIiBmaWxsPSIjRjdFMENFIiBmaWxsLXJ1bGU9Im5vbnplcm8iIG1hc2s9InVybCgjbWFzay0xNikiPjwvcGF0aD4KICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==" alt="logo of TCF Capital">'
*/
            . '</div>
		</div>
		<div style="height:100px;padding-right:2rem;padding-left:2rem;border-width:0">
        ';
        $dataBody .= '
			<h4 style="margin-top: 20px">' . $fieldsTrans[$lang]['name'] . ':</h4>
			<p class="text" style="font-family:Raleway,sans-serif;font-size:16px;line-height:26px;font-weight:400">'
                . ($_POST[$fieldsDef['name']] ?? '') . '</p>';
        if (!empty($_POST[$fieldsDef['email']])) {
            $dataBody .= '
			<h4>' . $fieldsTrans[$lang]['email'] . ':</h4>
			<p class="text" style="font-family:Raleway,sans-serif;font-size:16px;line-height:26px;font-weight:400">'
                . trim($_POST[$fieldsDef['email']]) . '</p>';
        }

        /*
        if (!empty($_POST[$fieldsDef['phone']])) {
            $dataBody .= '<p style="color:#080;font-size:18px;">' . $fieldsTrans[$lang]['phone'] . ': ' . trim($_POST[$fieldsDef['phone']]) . '</p>';
        }
        */

        $dataBody .= '
			<h4>' . $fieldsTrans[$lang]['message'] . ':</h4>
			<p class="text" style="font-family:Raleway,sans-serif;font-size:16px;line-height:26px;font-weight:400">'
                . ($_POST[$fieldsDef['message']] ?? '') . '</p>';
        $dataBody .= '
		</div>
	</div>
</body></html>
        ';
    } else {
        // plain text version
        $dataBody = '';
        foreach ($fieldsDef as $field => $fieldDef) {
            if (isset($_POST[$fieldDef]) && $fieldDef !== 'lang') {
                $dataBody .= $fieldsTrans[$lang][$field] . ': ' . ($_POST[$fieldDef] ?? '') . "\n";
            }
        }
    }

    if (isset($attachments['name'])) {
        $attachmentsCount = count($attachments['name']);
        for ($i = 0; $i < $attachmentsCount; $i++) {    // unset "empty" files
            if (empty($attachments['name'][$i])) {
                unset($attachments['name'][$i]);
            }
        }

        $attachmentsCount = count($attachments['name']);
    } else {
        $attachmentsCount = 0;
    }

    // headers
    $headers[] = "MIME-Version: 1.0";
    if (empty($senderName)) {
        $headers[] = "From: <" . $senderEmail . ">";
    } else {
        $headers[] = "From: =?utf-8?B?" . base64_encode($senderName) . "?= <" . $senderEmail . ">";
    }

    if (!empty($_POST[$fieldsDef['email']])) {
        $headers[] = "Reply-To: " . trim($_POST[$fieldsDef['email']]);
    }

    if (!empty($recipientCc)) {
        $headers[] = 'Cc: ' . implode(', ', $recipientCc);
    }

    if (!empty($recipientBcc)) {
        $headers[] = 'Bcc: ' . implode(', ', $recipientBcc);
    }

    $headers[] = "X-Mailer: PHP/" . phpversion();

    if ($attachmentsCount > 0) { // if any attachment exists
        //header
        $boundary = md5("rvlt_digital");
        $headers[] = "Content-Type: multipart/mixed; boundary = $boundary\r\n";

        //body
        $body[] = "--$boundary";
        if ($htmlContent) {
            // html version
            $body[] = "Content-Type: text/html; charset=utf-8";
        } else {
            // plain text version
            $body[] = "Content-Type: text/plain;charset=utf-8";
        }

        $body[] = "Content-Transfer-Encoding: base64\r\n";
        $body[] = chunk_split(base64_encode($dataBody));

        //attachments
        for ($i = 0; $i < $attachmentsCount; $i++) {
            if (!empty($attachments['name'][$i])) {
                if ($attachments['error'][$i] > 0) { //exit script and output error if we encounter any
                    $errors = [
                        1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                        2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                        3 => "The uploaded file was only partially uploaded",
                        4 => "No file was uploaded",
                        6 => "Missing a temporary folder",
                    ];

                    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
                    echo $errors[$attachments['error'][$i]];
                    exit(1);
                }

                //get file info
                $fileName = $attachments['name'][$i];
                $fileSize = $attachments['size'][$i];
                $fileType = $attachments['type'][$i];

                //read file & encode
                $handle = fopen($attachments['tmp_name'][$i], "r");
                $content = fread($handle, $fileSize);
                fclose($handle);

                $fileEncoded = chunk_split(base64_encode($content));

                $body[] = "--$boundary";
                $body[] = "Content-Type: $fileType; name=" . $fileName;
                $body[] = "Content-Disposition: attachment; filename=" . $fileName;
                $body[] = "Content-Transfer-Encoding: base64";
                $body[] = "X-Attachment-Id: " . rand(1000, 99999) . "\r\n";
                $body[] = $fileEncoded;
            }
        }
    } else {
        //header
        if ($htmlContent) {
            // html version
            $headers[] = "Content-Type: text/html; charset=utf-8";
        } else {
            // plain text version
            $headers[] = "Content-Type: text/plain;charset=utf-8";
        }

        $body[] = $dataBody;
    }

    $sentResult = @mail(
        implode(', ', $recipientTo),
        $fieldsTrans[$lang]['subject'] ?? $fieldsTrans[$defaultLang]['subject'] ?? '',
        implode("\r\n", $body),
        implode("\r\n", $headers)
    );

    if ($sentResult) {
        echo 'success';
        exit;
    }
}

header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
exit(1);
