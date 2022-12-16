<?Php

include ('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDqFnFI5CLCrSFI
iglE+6ejCTC3vdmWzkmArNL14glnzwXrOTxLlnMbuMALFWVmf0LQZEcZN3tVuONi
u0AJlVyJyyZY+8jGY3UdFPo+HqCTxnRwCcEgUvOnDeiTdGguol97bGQfNxsQV+Xz
UnrmMtwVblMAIPDTdfrdo3u6mDe996f8HiPEVAKJbQCRFSMRr/4VQU2XNwlzCysH
cdKlZbUVvrnIajJQ3rr1Mb03ztMrEUCPg1SrzCsHf+SPUNI+O4IIObpJlW8+abiW
Vys1QLk8/vkvFNhPcX1jeIvPHt3M02GFyhYICQUWiyLR/DB3ErfInH6a/GbjVU7w
fS6jeGFTAgMBAAECggEAHbBq/cuxfO9oYULgmhcw08S+oQ87IZ1YRTGmL/lTKA9h
uMuxkgSq5MEx2jYEflN1reiJ+/DFe8HPVR5aqNyAuBlD69VDSaYjnGSkuGw6AYQS
EIOsMuVSccWZ0dnZvNBrO8Qrjqn2jMQZLhsv6zJYJ3+ulz2WTbuQ7btmXewgZb9d
Pib2is2wq8HI7yywGgXNey0vc773qnHEtznhacsLlUCaH34heZBYoHNv2eDCuUlI
PbyxCOzngLjbyYW2i+wHPDcpGP/GOvEmni4JkBIa9sFeRNv+chHOhhyS30gyaP5W
IeooWSTAjxYZqi59fT6SkYGNyuePyCONmyRrGbQtAQKBgQD1wOVdoRfN+R2dbwUd
GxBkuPtRtkF0VaHIOgwv5pOr9SIfQQFnUC12KbPqYtrAa8KvL85fdYylRB4E2GjM
IGiQVoWPr5FbejKHe4rMi18GXOGh7GuPB5UkA7uVJqLFkIYytYHks5+w7KCaJxCI
iVSyuM46OrabcUlcAnPJgtNuMQKBgQDz2QdZ6u1/6irG5EqvxC2JEYjr1ayLtWP9
wGGsfOWU0mCA5sE3q6Gtu5jq0H/hpwmICozVgDqU3bwpEMrymez23GD4FiuGAhQo
AkMjV3lhyVAwYp9ULnmHNIY8BYajKcyYA8TL9iIRxDK0BhSRJU0ttrtRDRL0mhsM
WSeCQmwSwwKBgF38b7vnKBt18oWLOAFxoEtlE3iko1PCjCTvTknjfQZ+sZYSXl8M
otZWDKDPPanpjINDXUHUyv1Jl9FykmG+4z6QWHQjSQwbJ8f4z5R6mNTuILy0lk4V
MEydwNfB9u5n32r1T8APkjsvxBwwZHpTSzkuxHAwlDXOlafCqFWg2wuBAoGAN+HV
wHKJSRY2BWyN/SfdM5tUII/QQhgouR9cgvYAexGXUhMP1p07qR/j1HRByknIcmfQ
jKEdS75g/5w8lkpWNuCcTF5wTP6u9dhG4JOMWq+S8/O2Bcm8yhJsbNbrWvsaSwAa
Go/mkOHAqOb30aeVv1MwLEvtuyaR6kuINV7Ze5cCgYBsTRW6fd30HY5oocDsMmdQ
m8k5QmDeyxq8pdqkCLUw+GydpC8dLfELP8Sl+PJdAjwBE/I6E9CJny9fpIgtUzKV
A/xbCRsvWWCGFZaALiFW6srOErhd3hzdLFTRzTR8jY2TB1KJmfNpY+qWcqyZOYY0
1Qgec6BpcjHnvClcnOQeKA==
-----END PRIVATE KEY-----
EOD;

$publicKey = <<<EOD
-----BEGIN CERTIFICATE-----
MIIGQjCCBSqgAwIBAgIMTt6JnXzCnCSgsO5kMA0GCSqGSIb3DQEBCwUAMFMxCzAJ
BgNVBAYTAkJFMRkwFwYDVQQKExBHbG9iYWxTaWduIG52LXNhMSkwJwYDVQQDEyBH
bG9iYWxTaWduIEdDQyBSMyBEViBUTFMgQ0EgMjAyMDAeFw0yMjA4MjIwODE3MDFa
Fw0yMzA5MjMwODE3MDBaMBUxEzARBgNVBAMMCiouZGlhbGwucnUwggEiMA0GCSqG
SIb3DQEBAQUAA4IBDwAwggEKAoIBAQDqFnFI5CLCrSFIiglE+6ejCTC3vdmWzkmA
rNL14glnzwXrOTxLlnMbuMALFWVmf0LQZEcZN3tVuONiu0AJlVyJyyZY+8jGY3Ud
FPo+HqCTxnRwCcEgUvOnDeiTdGguol97bGQfNxsQV+XzUnrmMtwVblMAIPDTdfrd
o3u6mDe996f8HiPEVAKJbQCRFSMRr/4VQU2XNwlzCysHcdKlZbUVvrnIajJQ3rr1
Mb03ztMrEUCPg1SrzCsHf+SPUNI+O4IIObpJlW8+abiWVys1QLk8/vkvFNhPcX1j
eIvPHt3M02GFyhYICQUWiyLR/DB3ErfInH6a/GbjVU7wfS6jeGFTAgMBAAGjggNS
MIIDTjAOBgNVHQ8BAf8EBAMCBaAwgZMGCCsGAQUFBwEBBIGGMIGDMEYGCCsGAQUF
BzAChjpodHRwOi8vc2VjdXJlLmdsb2JhbHNpZ24uY29tL2NhY2VydC9nc2djY3Iz
ZHZ0bHNjYTIwMjAuY3J0MDkGCCsGAQUFBzABhi1odHRwOi8vb2NzcC5nbG9iYWxz
aWduLmNvbS9nc2djY3IzZHZ0bHNjYTIwMjAwVgYDVR0gBE8wTTBBBgkrBgEEAaAy
AQowNDAyBggrBgEFBQcCARYmaHR0cHM6Ly93d3cuZ2xvYmFsc2lnbi5jb20vcmVw
b3NpdG9yeS8wCAYGZ4EMAQIBMAkGA1UdEwQCMAAwQQYDVR0fBDowODA2oDSgMoYw
aHR0cDovL2NybC5nbG9iYWxzaWduLmNvbS9nc2djY3IzZHZ0bHNjYTIwMjAuY3Js
MB8GA1UdEQQYMBaCCiouZGlhbGwucnWCCGRpYWxsLnJ1MB0GA1UdJQQWMBQGCCsG
AQUFBwMBBggrBgEFBQcDAjAfBgNVHSMEGDAWgBQNmMBzf6u9vdlHS0mtCkoMrD7H
fDAdBgNVHQ4EFgQUTmQGBgP89QZgw4c2Yo11LoSk/FwwggF+BgorBgEEAdZ5AgQC
BIIBbgSCAWoBaAB2AOg+0No+9QY1MudXKLyJa8kD08vREWvs62nhd31tBr1uAAAB
gsSgaE0AAAQDAEcwRQIhAMoSNwV4GSrMcTjFT1KhKCvKH4xuTPkaqu9gpf4sLJWp
AiAKpGVZ5OXXsyOaf8aKvpUj7dbPue3sHB8jR2KqdbNvHgB2AG9Tdqwx8DEZ2JkA
pFEV/3cVHBHZAsEAKQaNsgiaN9kTAAABgsSgaDUAAAQDAEcwRQIhAK3fjHlwkJKJ
VAPytK6XZTU1QiOglndmaqdjSncprhcRAiAYmoR13NN0iRo5O+fjYBTB7rkdyKMc
ZksJmlG8NGzXGAB2AFWB1MIWkDYBSuoLm1c8U/DA5Dh4cCUIFy+jqh0HE9MMAAAB
gsSgaFoAAAQDAEcwRQIhAPfj1aLv8j/TknZi7Ug/fGwFw05VqYk5fwCFDZPF2Ytl
AiAutcxHThQUQxTiNWJuuJH+a4DDz9kM8c7ilKhdwn0GFTANBgkqhkiG9w0BAQsF
AAOCAQEAlSOpd2K63v5SdNTCZgqS3vZHJwUVmCnOxSzTh1vocGQR+09lWL71u722
q8CwQINeXWwEd/jFGteOodHwqWGpsD+SrsQd44UjWFD1/CV4kxElEOtjlbSVeoYz
wOX0pGMrwyMVont6sGPORDuc5zUZECk4cF7PQCv8sA921RpZR5zqFr50vJr83hjo
WfGAOS5Orv0I2NSA5+A2XihSLhUIPWOzvdedtzcvuNg9BeYaYbGPVFjVe2m/ypkW
W17pyKQbABl+cjbZMgBZpnuOvCsKHF1/IaXuFtbkP7pIdI30QqmnWpW05ozLqRV2
ar9LZzFRPI7f2Sx2qoFNv5vmbrAgvQ==
-----END CERTIFICATE-----
EOD;

$payload = [
    'iss' => 'DiallAlianceT',
    'sub' => '108da251-6c21-4105-80f2-99386f97a313',
    'aud' => 'esa.hr-link.ru',
    'exp' => time() + (60 * 5),
    'nbf' => time(),
    'iat' => time()
];

$jwt = JWT::encode($payload, $privateKey, 'RS256');
echo "Encode:\n" . print_r($jwt, true) . "\n";

$decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));

/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/

$decoded_array = (array) $decoded;
echo "Decode:\n" . print_r($decoded_array, true) . "\n";

?>