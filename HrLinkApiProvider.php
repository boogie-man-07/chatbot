<?Php

include ('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class HrLinkApiProvider {

    function registerDocumentApplication($user, $formData, $bossPhysicalId, $idOfType) {
        $userPhysicalId = $user['physical_id'];
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $applicationEmployeeIdResponse = $this->getCurrentUser($masterToken, $userPhysicalId);
                $applicationEmployeeApproverIdResponse = $this->getCurrentUser($masterToken, $bossPhysicalId); // allways Ryzhkina Marina
                if ($applicationEmployeeIdResponse['result'] && $applicationEmployeeApproverIdResponse['result']) {
                    $applicationEmployeeId = $applicationEmployeeIdResponse['id'];
                    $applicationEmployeeApproverId = $applicationEmployeeApproverIdResponse['id']; // allways Ryzhkina Marina
                    $userFIO = $this->separateFIO($user['form_fullname']);
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

                    $templateSystemFields = $this->generateSystemFields($userFIO);
                    $templateFields = $this->generateDocumentTemplateFields($formData);
                    $participantsFields = $this->generateDocumentsParticipantsFields($applicationEmployeeId);

                    $applications = array(
                        array(
                            'externalId' => $applicationExternalId,
                            'legalEntityId' => $applicationLegalEntityId,
                            'legalEntityExternalId' => $applicationLegalEntityExternalId,
                            'employeeId' => $applicationEmployeeId,
                            'employeeExternalId' => $applicationEmployeeExternalId,
                            'employeeApproverId' => '2d145f14-bd25-4c4f-b98b-0d7616ee2ed5',
                            'employeeApproverExternalId' => $applicationEmployeeApproverExternalId,
                            'participants' => $participantsFields
                        )
                    );

                    $body = array(
                        'externalId' => $externalId,
                        'date' => $currentDate,
                        'number' => $number,
                        'typeId' => $typeId,
                        'applications' => $applications,
                        'templateSystemFields' => $templateSystemFields,
                        'templateFields' => $templateFields
                    );
                    $encodedBody = json_encode($body);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://hrlink.diall.ru/api/v2/clients/".$clientId."/applicationGroups",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 5000,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $encodedBody,
                        CURLOPT_HTTPHEADER => array(
                            "Master-Api-Token: $masterToken",
                            "Impersonated-User-Id: $userPhysicalId",
                            'Impersonated-User-Id-Type: EXTERNAL_ID',
                            'Content-Type: application/json'
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
                        $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
                        if ($result['result']) {
                            return array(
                                'result' => $result['result'],
                                'applicationGroupId' => $result['applicationGroup']['id']
                            );
                        } else {
                            return array(
                                'result' => $result['result'],
                                'message' => $result['errorMessage']
                            );
                        }
                    }
                } else {
                    return "Не нормально 2";
                }
            } else {
                return "Не нормально 3";
            }
        } else {
            return "Не нормально 4";
        }
    }

    function registerPostponedApplication($user, $sendData, $userRouteInfo, $bossRouteInfo, $idOfType) {
        $userPhysicalId = $user['physical_id'];
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $applicationEmployeeIdResponse = $this->getCurrentUser($masterToken, $userPhysicalId);
                $applicationEmployeeApproverIdResponse = $this->getCurrentUser($masterToken, $userRouteInfo['userBossPhysicalId']);
                if ($applicationEmployeeIdResponse['result'] && $applicationEmployeeApproverIdResponse['result']) {
                    $applicationEmployeeId = $applicationEmployeeIdResponse['id'];
                    $applicationEmployeeApproverId = $applicationEmployeeApproverIdResponse['id'];
                    $userFIO = $this->separateFIO($user['form_fullname']);
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
                    $participantsFields = $this->generateApplicationsParticipantsFields($masterToken, $applicationEmployeeId, $applicationEmployeeApproverId, $bossRouteInfo['userBossPhysicalId']);

                    $applications = array(
                        array(
                            'externalId' => $applicationExternalId,
                            'legalEntityId' => $applicationLegalEntityId,
                            'legalEntityExternalId' => $applicationLegalEntityExternalId,
                            'employeeId' => $applicationEmployeeId,
                            'employeeExternalId' => $applicationEmployeeExternalId,
                            'employeeApproverId' => $applicationEmployeeApproverId,
                            'employeeApproverExternalId' => $applicationEmployeeApproverExternalId,
                            'participants' => $participantsFields
                        )
                    );

                    $templateSystemFields = $this->generateSystemFields($userFIO);
                    $templateFields = array(
                        array('id' => 'f7b44a6d-1c61-41d4-bbd8-3514087ac2de', 'value' => $this->convertToHrLinkDateFormat($sendData['startDate'])),
                        array('id' => 'aabe529e-c65c-457b-9253-5b3c533a9c51', 'value' => $this->convertToHrLinkDateFormat($sendData['endDate'])),
                        array('id' => 'b70cadc4-406d-4c11-afbe-a3f7910a93e5', 'value' => $this->convertToHrLinkDateFormat($sendData['vacations'][0]['startDate'])),
                        array('id' => '6f573058-06d6-48fb-aa73-e2d8fd369c88', 'value' => $this->convertToHrLinkDateFormat($sendData['vacations'][0]['endDate'])),
                        array('id' => '0ec8480d-4af2-4457-80bb-ecc5b647b153', 'value' => $sendData['vacations'][0]['reason'])
                    );

                    $body = array(
                        'externalId' => $externalId,
                        'date' => $currentDate,
                        'number' => $number,
                        'typeId' => $typeId,
                        'applications' => $applications,
                        'templateSystemFields' => $templateSystemFields,
                        'templateFields' => $templateFields
                    );
                    $encodedBody = json_encode($body);
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://hrlink.diall.ru/api/v2/clients/".$clientId."/applicationGroups",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 5000,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $encodedBody,
                        CURLOPT_HTTPHEADER => array(
                            "Master-Api-Token: $masterToken",
                            "Impersonated-User-Id: $userPhysicalId",
                            'Impersonated-User-Id-Type: EXTERNAL_ID',
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
//                     return $response;

                    if ($err) {
                        return array(
                            'result' => false,
                            'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
                        );
                    } else {
                        $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
                        if ($result['result']) {
                            return array(
                                'result' => $result['result'],
                                'applicationGroupId' => $result['applicationGroup']['id']
                            );
                        } else {
                            return array(
                                'result' => $result['result'],
                                'message' => $result['errorMessage']
                            );
                        }
                    }
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

    function registerApplication($user, $vacationFormData, $userRouteInfo, $bossRouteInfo, $idOfType) {
        $userPhysicalId = $user['physical_id'];
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $applicationEmployeeIdResponse = $this->getCurrentUser($masterToken, $userPhysicalId);
                $applicationEmployeeApproverIdResponse = $this->getCurrentUser($masterToken, $userRouteInfo['userBossPhysicalId']);
                if ($applicationEmployeeIdResponse['result'] && $applicationEmployeeApproverIdResponse['result']) {
                    $applicationEmployeeId = $applicationEmployeeIdResponse['id'];
                    $applicationEmployeeApproverId = $applicationEmployeeApproverIdResponse['id'];
                    $userFIO = $this->separateFIO($user['form_fullname']);
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
                    $participantsFields = $this->generateApplicationsParticipantsFields($masterToken, $applicationEmployeeId, $applicationEmployeeApproverId, $bossRouteInfo['userBossPhysicalId']);

                    $applications = array(
                        array(
                            'externalId' => $applicationExternalId,
                            'legalEntityId' => $applicationLegalEntityId,
                            'legalEntityExternalId' => $applicationLegalEntityExternalId,
                            'employeeId' => $applicationEmployeeId,
                            'employeeExternalId' => $applicationEmployeeExternalId,
                            'employeeApproverId' => $applicationEmployeeApproverId,
                            'employeeApproverExternalId' => $applicationEmployeeApproverExternalId,
                            'participants' => $participantsFields
                        )
                    );

                    $templateSystemFields = $this->generateSystemFields($userFIO);
                    $templateFields = $this->generateTemplateFields($vacationFormData);

                    $body = array(
                        'externalId' => $externalId,
                        'date' => $currentDate,
                        'number' => $number,
                        'typeId' => $typeId,
                        'applications' => $applications,
                        'templateSystemFields' => $templateSystemFields,
                        'templateFields' => $templateFields
                    );
                    $encodedBody = json_encode($body);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://hrlink.diall.ru/api/v2/clients/".$clientId."/applicationGroups",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 5000,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $encodedBody,
                        CURLOPT_HTTPHEADER => array(
                            "Master-Api-Token: $masterToken",
                            "Impersonated-User-Id: $userPhysicalId",
                            'Impersonated-User-Id-Type: EXTERNAL_ID',
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
//                     return $response;

                    if ($err) {
                        return array(
                            'result' => false,
                            'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
                        );
                    } else {
                        $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
                        if ($result['result']) {
                            return array(
                                'result' => $result['result'],
                                'applicationGroupId' => $result['applicationGroup']['id']
                            );
                        } else {
                            return array(
                                'result' => $result['result'],
                                'message' => $result['errorMessage']
                            );
                        }
                    }
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

    function generateDocumentsParticipantsFields($applicationEmployeeId) {
        $participantsFields = array();
        array_push(
            $participantsFields,
            array('id' => '59811b03-8aba-49f2-a0b6-23915f2529b1', 'employeeId' => $applicationEmployeeId)
        );
        return $participantsFields;
    }

    function generateApplicationsParticipantsFields($masterToken, $applicationEmployeeId, $applicationEmployeeApproverId, $userBossPhysicalId) {
        $participantsFields = array();
        $topApprover = '';
        if ($userBossPhysicalId === '1d914401-b3e8-11ec-a1bf-d4f5ef044d5f' || $userBossPhysicalId === '00000000-0000-0000-0000-000000000000') {
            $topApprover = $applicationEmployeeApproverId;
        } else {
            $result = $this->getCurrentUser($masterToken, $userBossPhysicalId);
            $topApprover = $result['id'];
        }


        array_push(
            $participantsFields,
            array('id' => '40fdd4a0-4ef4-47e2-b515-4cc35f0ae574', 'employeeId' => $applicationEmployeeId),
            array('id' => 'a7d25a0e-6d7b-403c-8159-ee286ae1d83c', 'employeeId' => $applicationEmployeeApproverId),
            array('id' => 'f2616540-95ff-480e-8506-1533b001c9df', 'employeeId' => $topApprover)
        );
        return $participantsFields;
    }

    function generateSystemFields($userFIO) {
        $templateSystemFields = array();
        array_push(
            $templateSystemFields,
            array('id' => 'af1460a0-5081-4101-9342-6960f8ef99c0', 'value' => $userFIO[0]),
            array('id' => 'ebd6a325-e206-4870-b89e-b86043f97c64', 'value' => $userFIO[1]),
            array('id' => 'df6cc959-a85b-4b62-a51e-0bbcf44ce203', 'value' => $userFIO[2])
        );
        return $templateSystemFields;
    }

    function generateTemplateFields($vacationFormData) {
        $templateFields = array();
        $type = $vacationFormData['vacation_type'] + 1;
        switch ($type) {
            case 1:
                array_push(
                    $templateFields,
                    array('id' => '462b0c7c-fc3d-4045-b9c8-2d7ca9ad2fe6', 'value' => $this->convertToHrLinkDateFormat($vacationFormData['vacation_startdate'])),
                    array('id' => 'c6f8f4cc-9b2f-425a-ae14-f77d0f989f12', 'value' => $vacationFormData['vacation_duration'])
                );
                break;
            case 2:
                array_push(
                    $templateFields,
                    array('id' => 'dd35d894-cdf5-42a6-9cf5-b81ef20bc5e1', 'value' => $this->convertToHrLinkDateFormat($vacationFormData['vacation_startdate'])),
                    array('id' => '1f12036c-192e-4e5c-ba35-e806833222e4', 'value' => $vacationFormData['vacation_duration'])
                );
                break;
            case 3:
                array_push(
                    $templateFields,
                    array('id' => 'c34b6407-49b2-48b2-9a8a-bbbd74179668', 'value' => $this->convertToHrLinkDateFormat($vacationFormData['vacation_startdate'])),
                    array('id' => '748d9dd7-75f2-443d-89e0-9199d2fedc0a', 'value' => $vacationFormData['vacation_duration'])
                );
                break;
            case 4:
                array_push(
                    $templateFields,
                    array('id' => 'a25cf9a8-277e-44ee-96f8-d615d5755c08', 'value' => $this->convertToHrLinkDateFormat($vacationFormData['vacation_startdate'])),
                    array('id' => 'b6e69695-9774-40e7-b922-20784f284c25', 'value' => $vacationFormData['vacation_duration']),
                    array('id' => 'dba6d5aa-b78c-45c3-969e-105d5f3f50e9', 'value' => $vacationFormData['reason']),
                    array('id' => '358fa9aa-9fa3-40a2-889f-60e8d95e0bd9', 'value' => $vacationFormData['cause'])
                );
                break;
        }
        return $templateFields;
    }

    function checkSmsCode($userPhysicalId, $applicationGroupId, $signingRequestId, $code) {
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $clientId = 'a0731d7f-4799-4fe0-944a-247f256fd509';

                $body = array(
                    'signingRequestId' => $signingRequestId,
                    'code' => $code
                );
                $encodedBody = json_encode($body);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://hrlink.diall.ru/api/v1/clients/$clientId/applicationGroups/$applicationGroupId/sign/nqes",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS => $encodedBody,
                    CURLOPT_HTTPHEADER => array(
                        "Master-Api-Token: $masterToken",
                        "Impersonated-User-Id: $userPhysicalId",
                        'Impersonated-User-Id-Type: EXTERNAL_ID',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                return $response;

//                 if ($err) {
//                     return array(
//                         'result' => false,
//                         'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
//                     );
//                 } else {
//                     $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
//                     return $result;
//                 }
            } else {
                return array(
                    'result' => false,
                    'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
                );
            }
        } else {
            return array(
                'result' => false,
                'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
            );
        }
    }

    function sendSmsCode($userPhysicalId, $applicationGroupId) {
        $bearerTokenResponse = $this->generateBearerToken();
        if ($bearerTokenResponse['result']) {
            $bearerToken = $bearerTokenResponse['bearerToken'];
            $masterTokenResponse = $this->generateMasterKey($bearerToken);
            if ($masterTokenResponse['result']) {
                $masterToken = $masterTokenResponse['masterToken'];
                $clientId = 'a0731d7f-4799-4fe0-944a-247f256fd509';

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://hrlink.diall.ru/api/v1/clients/$clientId/applicationGroups/$applicationGroupId/sign/nqes",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{}',
                    CURLOPT_HTTPHEADER => array(
                        "Master-Api-Token: $masterToken",
                        "Impersonated-User-Id: $userPhysicalId",
                        'Impersonated-User-Id-Type: EXTERNAL_ID',
                        'Content-Type: application/json'
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
                    $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
                    if ($result['result']) {
                        return array(
                            'result' => $result['result'],
                            'signingRequestId' => $result['signingRequestId']
                        );
                    } else {
                        return array(
                            'result' => $result['result'],
                            'message' => $result['errorMessage']
                        );
                    }
                }
            } else {
                return array(
                    'result' => false,
                    'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
                );
            }
        } else {
            return array(
                'result' => false,
                'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
            );
        }
    }


    function getCurrentUser($masterToken, $userPhysicalId) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/currentUser',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 5000,
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

    function convertToHrLinkDateFormat($date) {
        return date('Y-m-d', strtotime($date));
    }

    function uploadFile($chatId, $masterToken, $userPhysicalId) {
        $curl = curl_init();
        $currentDate = date('Y-m-d');
        $body = array('file'=> new CURLFILE("/var/www/sigmabot.ddns.net/files/requestDocumentsCopyForm_$chatId"."_"."$currentDate.xlsx"));
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/files',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Master-Api-Token: $masterToken",
                "Impersonated-User-Id: $userPhysicalId",
                'Impersonated-User-Id-Type: EXTERNAL_ID',
                'Content-Type: multipart/form-data'
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
            $result = json_decode($response, TRUE, 512, JSON_UNESCAPED_UNICODE);
            if ($result['result']) {
                return array(
                    'result' => $result['result'],
                    'fileId' => $result['files'][0]['id']
                );
            } else {
                return array(
                    'result' => $result['result'],
                    'message' => $result['errorMessage']
                );
            }
        }
    }

    function generateDocumentTemplateFields($formData) {
        $templateFields = array();
        $issueType = $formData['issue_type'] + 1;
        $referenceType = $formData['reference_type'];
        $deliveryType = $formData['delivery_type'];

        switch ($issueType) {
            case 6:
                switch ($deliveryType) {
                    case 1:
                        array_push(
                            $templateFields,
                            array('id' => 'bf3337b1-59de-4f5c-a57a-6be425e6c434', 'value' => 'Отправить непосредственному руководителю'));
                        break;
                    case 2:
                        array_push(
                            $templateFields,
                            array('id' => 'bf3337b1-59de-4f5c-a57a-6be425e6c434', 'value' => 'Заберу сам'));
                        break;
                    case 3:
                        array_push(
                            $templateFields,
                            array('id' => '22a3e3a6-0be0-4a67-858b-652d3568c09c', 'value' => $formData['delivery_type_text']));
                        break;
                }

                switch ($referenceType) {
                    case 1:
                        array_push(
                            $templateFields,
                            array('id' => '62b03c2d-3dca-4796-84c9-2a1bed4a168f', 'value' => 'V'));
                        break;
                    case 2:
                        array_push(
                            $templateFields,
                            array('id' => '36da719f-0fe0-4534-a662-ab2a608649a7', 'value' => 'V'));
                        break;
                    case 3:
                        array_push(
                            $templateFields,
                            array('id' => '20cd7a2f-797a-485c-bdaa-e9eb0233b09b', 'value' => 'V'));
                        break;
                    case 4:
                        array_push(
                            $templateFields,
                            array('id' => '8ec7aff3-b3f9-4756-a9a3-ab538b8260d9', 'value' => 'V'),
                            array('id' => '5627f388-6c84-40fc-9373-71054fb00140', 'value' => $this->convertToHrLinkDateFormat($formData['start_date'])),
                            array('id' => '7ad100ab-3498-46be-a666-6041d97b2a16', 'value' => $this->convertToHrLinkDateFormat($formData['end_date'])));
                        break;
                    case 5:
                        array_push(
                            $templateFields,
                            array('id' => '6a4353b8-ac33-418f-99fe-5118ae61d6cf', 'value' => 'V'),
                            array('id' => '4c2b530b-eca8-4d41-b5d5-91d88c363cee', 'value' => $this->convertToHrLinkDateFormat($formData['start_date'])),
                            array('id' => '3da4e5e2-83a0-45d0-b1ed-47c2069b0042', 'value' => $this->convertToHrLinkDateFormat($formData['end_date'])));
                        break;
                    case 6:
                        array_push(
                            $templateFields,
                            array('id' => '0759cf8a-3dab-43e0-8a67-c7b5cdbfbc96', 'value' => 'V'),
                            array('id' => '237624ee-400f-4fa0-bd32-277418ecf76a', 'value' => $this->convertToHrLinkDateFormat($formData['start_date'])),
                            array('id' => 'fb3e1e73-7204-4ef0-9177-8baa0ddfacee', 'value' => $this->convertToHrLinkDateFormat($formData['end_date'])));
                        break;
                    case 7:
                        array_push(
                            $templateFields,
                            array('id' => '9454fca8-f6c2-41d5-8c65-f11910972d58', 'value' => 'V'),
                            array('id' => '16790ca4-4927-41b3-99c9-f3b49da2c412', 'value' => $formData['type_text']));
                        break;
                }
                break;
            case 7:
                switch ($deliveryType) {
                    case 1:
                        array_push(
                            $templateFields,
                            array('id' => 'bf2cdad9-546f-448b-b2c1-f1010213e1f5', 'value' => 'Отправить непосредственному руководителю'));
                        break;
                    case 2:
                        array_push(
                            $templateFields,
                            array('id' => 'bf2cdad9-546f-448b-b2c1-f1010213e1f5', 'value' => 'Заберу сам'));
                        break;
                    case 3:
                        array_push(
                            $templateFields,
                            array('id' => '41a041c6-9e32-42c3-8738-0e978ef4a930', 'value' => $formData['delivery_type_text']));
                        break;
                }

                array_push(
                    $templateFields,
                    array('id' => '8761188d-c6a2-494e-9ff0-0f0881959596', 'value' => $formData['type_text'])
                );
                break;
        }
        return $templateFields;
    }
}

?>