<?php

namespace App\Classe;

use Mailjet\Resources;
use Mailjet\Client;

class Mail 
{
    private $api_key = '38b5b42a41967540550d04f798a487a1';
    private $api_key_secret = 'f45742461dac829435bc75bc7905d50e';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "julie-causse@live.fr",
                        'Name' => "La Boutique Française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4031883,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables'=> [
                        'content'=> $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}