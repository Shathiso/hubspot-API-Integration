<?php

class HubSpotController
{  

    public $result=[];
    public $pageNo=0;

    public function getHubspotContacts($offset){
        $paramOffset = $offset;

        $curl = curl_init();
        $password = ''; //test

        $username = '';
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.hubapi.com/contacts/v1/lists/all/contacts/all?hapikey=$password&count=100&vidOffset=$paramOffset&property=firstname&property=lastname&property=email&property=company",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          //CURLOPT_USERPWD=> $username . ':' . $password,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 50,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
          ),
        ));
        
        $response = curl_exec($curl);
        
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
          return "cURL Error #:" . $err;
        } 
        else {        
          $data = json_decode($response, true);
          $latestOffset = $data['vid-offset'];
          
          foreach($data as $contact){
            if( $contact[0]['properties']['company']['value'] == "test"){
              array_push($this->result, $contact);
              echo "contact Added";
             }
           }
          if($data['has-more'] == 1){
            return self::getHubspotContacts($latestOffset);
          }
          else{
            return $this->result;     
            }
          }
        } 
}
?>
