<?php
    $app->post('/api/YandexPlace/serchByOrganization', function ($request, $response) {


          //optional params

          $option = array('ll','spn','bbox','rspn','type','results','skip');


          $settings = $this->settings;
          $checkRequest = $this->validation;
          $validateRes = $checkRequest->validate($request, ['apikey','text','lang']);


          if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
              return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
          } else {
              $post_data = $validateRes;
          }

          $url = 'https://search-maps.yandex.ru/v1/';



          $cords = $post_data['args']['ll'];
          $client = $this->httpClient;
          $queryParam = array('apikey' => $post_data['args']['apikey'],'text' => $post_data['args']['text'],'lang' => $post_data['args']['lang'],'type' => 'biz');

          foreach($option as $key => $value)
          {
            if(!empty($post_data['args'][$value]))
            {
              $queryParam[$value] = $post_data['args'][$value];
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
