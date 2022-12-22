<?Php

include ('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class HrLinkApiProvider {

    function registerApplication($user, $vacationFormData, $bossPhysicalId, $idOfType) {
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $applicationEmployeeIdResponse = $this->getCurrentUser($masterToken, $user['physical_id']);
                $applicationEmployeeApproverIdResponse = $this->getCurrentUser($masterToken, $bossPhysicalId);
                if ($applicationEmployeeIdResponse['result'] && $applicationEmployeeApproverIdResponse['result']) {
                    $applicationEmployeeId = $applicationEmployeeIdResponse['id'];
                    $applicationEmployeeApproverId = $applicationEmployeeApproverIdResponse['id'];
                    $userFIO = separateFIO($user['form_fullname']);
                    $clientId = 'a0731d7f-4799-4fe0-944a-247f256fd509';
                    $externalId = null;
                    $currentDate = date('Y-m-d');
                    $number = 'Персональный ассистент работника_telegram';
                    $typeId = $idOfType;
                    $applicationExternalId = null;
                    $applicationLegalEntityId = '91f2a834-1721-46c1-b917-6dc5cb943ed5';
                    $applicationLegalEntityExternalId = null;
                    $applicationEmployeeExternalId = null;
                    $applicationEmployeeApproverExternalId = null;

                    $applications = array(
                        'externalId' => $applicationExternalId,
                        'legalEntityId' => $applicationLegalEntityId,
                        'legalEntityExternalId' => $applicationLegalEntityExternalId,
                        'employeeId' => $applicationEmployeeId,
                        'employeeExternalId' => $applicationEmployeeExternalId,
                        'employeeApproverId' => $applicationEmployeeApproverId,
                        'employeeApproverExternalId' => $applicationEmployeeApproverExternalId
                    );

                    $templateSystemFields = array(
                        array('id' => 'af1460a0-5081-4101-9342-6960f8ef99c0', 'value' => $userFIO[0]),
                        array('id' => 'ebd6a325-e206-4870-b89e-b86043f97c64', 'value' => $userFIO[1]),
                        array('id' => 'df6cc959-a85b-4b62-a51e-0bbcf44ce203', 'value' => $userFIO[2])
                    );

                    $templateFields = array(
                        array('id' => '462b0c7c-fc3d-4045-b9c8-2d7ca9ad2fe6', 'value' => $vacationFormData['vacation_startdate']),
                        array('id' => 'c6f8f4cc-9b2f-425a-ae14-f77d0f989f12', 'value' => $vacationFormData['vacation_duration'])
                    );

                    $body = array(
                        'externalId' => $externalId,
                        'date' => $currentDate,
                        'number' => $number,
                        'typeId' => $typeId,
                        'applications' => $application,
                        'templateSystemFields' => $templateSystemFields,
                        'templateFields' => $templateFields
                    );

                    return $body;

                } else {
                    return "Не нормально 1";
                }
            } else {
                return "Не нормально 2";
            }
        } else {
            return "Не нормально 3";
        }
    }


    function getCurrentUser($masterToken, $userPhysicalId) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/currentUser',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            "Master-Api-Token: $masterToken",
            "Impersonated-User-Id: $userPhysicalId",
            'Impersonated-User-Id-Type: EXTERNAL_ID'
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return array(
                'result' => false,
                'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
            );
        } else {
            $result = json_decode($response, true);
            return array(
                'result' => $result['result'],
                'id' => $result['currentUser']['employees'][0]['id']
            );
        }
    }

    function generateMasterKey($bearerToken) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://esa.hr-link.ru/api/v1/masterTokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "tenantHost": "hrlink.diall.ru"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $bearerToken",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return array(
                'result' => false,
                'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
            );
        } else {
            $result = json_decode($response, true);
            return array(
                'result' => $result['result'],
                'masterToken' => $result['masterToken']
            );
        }
    }

    function generateBearerToken() {
        $pk = file_get_contents('/var/www/sigmabot.ddns.net/pk.key');
        $pubk = file_get_contents('/var/www/sigmabot.ddns.net/pubk.pub');

        $privateKey = <<<EOD
        $pk
        EOD;

        $publicKey = <<<EOD
        $pubk
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
        return array(
            'result' => true,
            'bearerToken' => $jwt
        );
    }

    function separateFIO($fullName) {
        return explode(' ', $fullName);
    }

//     function uploadFile() {
//         $curl = curl_init();
//         curl_setopt_array($curl, array(
//             CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/files',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'GET',
//             CURLOPT_POSTFIELDS => array('file'=> new CURLFILE('/Users/booogie-man-07/Downloads/star.png')),
//             CURLOPT_HTTPHEADER => array(
//                 'User-Api-Token: 2799ee80-c1a6-40e0-a930-64ba4346c548',
//                 'Content-Type: multipart/form-data'
//             ),
//         ));
//
//         $response = curl_exec($curl);
//         curl_close($curl);
//
//         if ($err) {
//             //echo "cURL Error #: ".$err;
//             return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
//         } else {
//             $result = json_decode($response, true);
//             return $result;
//         }
//     }
}

?>