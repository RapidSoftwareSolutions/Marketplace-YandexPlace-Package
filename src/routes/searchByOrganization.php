<?php
    $app->post('/api/YandexPlaces/serchByOrganization', function ($request, $response) {

      // all param
      // alias => vendor name
      $option = array(
       'text' => 'text',
       'mapCenter' => 'll',
       'mapExtent' => 'spn',
       'skip' => 'skip',
       'resultsLimit' => 'results',
       'lang' => 'lang',
       'apiKey' => 'apikey',
       'alterCord' => 'bbox',
       'hardLimitation' => 'rspn'
       );

       $arrayType = array();


          $settings = $this->settings;
          $checkRequest = $this->validation;
          $validateRes = $checkRequest->validate($request, ['apiKey','text','lang']);


          if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
              return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
          } else {
              $postData = $validateRes;
          }

          $url = 'https://search-maps.yandex.ru/v1/';


          $client = $this->httpClient;
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

          $resp =  $client->request('GET', $url ,['query' => $queryParam ] );

         try {

           $responseBody = $resp->getBody()->getContents();
             if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
                 $result['callback'] = 'success';


                 $result['contextWrites']['to'] =   $responseBody;


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
