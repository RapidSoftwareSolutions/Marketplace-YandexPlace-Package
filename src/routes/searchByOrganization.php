<?php
    $app->post('/api/YandexPlaces/searchByOrganization', function ($request, $response) {

      // all param
      // alias => vendor name
      $option = array(
       'text' => 'text',
       'mapCenter' => 'll',
       'searchAreaSize' => 'spn',
       'skip' => 'skip',
       'resultsLimit' => 'results',
       'language' => 'lang',
       'apiKey' => 'apikey',
       'alternativeSearch' => 'bbox',
       'searchAreaRestriction' => 'rspn'
       );

       $arrayType = array();


          $settings = $this->settings;
          $checkRequest = $this->validation;
          $validateRes = $checkRequest->validate($request, ['apiKey','text','language']);


          if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
              return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
          } else {
              $postData = $validateRes;
          }

          $url = 'https://search-maps.yandex.ru/v1/';


          $client = $this->httpClient;
          //include hard limitation
          if(!empty($postData['args']['searchAreaRestriction'][0]) && $postData['args']['searchAreaRestriction'][0] == 'On')
          {
            $postData['args']['searchAreaRestriction'] = 1;
          }


          foreach($option as $alias => $value)
            {
              if(!empty($postData['args'][$alias]))
              {
                  if(in_array($alias,$arrayType))
                  {
                    $postData['args'][$alias] = implode(',',$postData['args'][$alias]);
                  }
                  $queryParam[$value] = $postData['args'][$alias];
              }
            }

        if(!empty($queryParam['ll'])){
            $coordinates[0] = trim(explode(",",$queryParam['ll'])[0]);
            $coordinates[1] = trim(explode(",",$queryParam['ll'])[1]);
            $queryParam['ll'] = $coordinates[1] . "," . $coordinates[0];
        }

        $resp =  $client->request('GET', $url ,['query' => $queryParam ] );

         try {

           $responseBody = $resp->getBody()->getContents();
             if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
                 $result['callback'] = 'success';


                 $result['contextWrites']['to'] =   array('status' => 'success','result' => $responseBody);


             } else {
                 $result['callback'] = 'error';
                 $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                 $result['contextWrites']['to']['status_msg'] = "Wrong param";
             }
         } catch (\GuzzleHttp\Exception\ClientException $exception) {
             $responseBody = $exception->getResponse()->getBody()->getContents();
             if(empty(json_decode($responseBody))) {
                 $out = $responseBody;
             } else {
                 $out = json_decode($responseBody);
             }
             $result['callback'] = 'error';
             $result['contextWrites']['to']['status_code'] = 'API_ERROR';
             $result['contextWrites']['to']['status_msg'] = "Wrong param";
         } catch (GuzzleHttp\Exception\ServerException $exception) {
             $responseBody = $exception->getResponse()->getBody()->getContents();
             if(empty(json_decode($responseBody))) {
                 $out = $responseBody;
             } else {
                 $out = json_decode($responseBody);
             }
             $result['callback'] = 'error';
             $result['contextWrites']['to']['status_code'] = 'API_ERROR';
             $result['contextWrites']['to']['status_msg'] = "Wrong param";
         } catch (GuzzleHttp\Exception\ConnectException $exception) {
             $responseBody = $exception->getResponse()->getBody(true);
             $result['callback'] = 'error';
             $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
             $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';
         }
         return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});

