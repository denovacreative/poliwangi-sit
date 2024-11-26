<?php

namespace App\Services;

use GuzzleHttp\Client;

class FeederService
{
    private $url;
    private $username;
    private $password;
    private $act;
    private $opt;

    function __construct($act, $opt = [])
    {
        $this->url = 'http://' . getSetting('feeder_url') . ':' . getSetting('feeder_port') . getSetting('feeder_path');
        $this->username = getSetting('feeder_username');
        $this->password = getSetting('feeder_password');
        $this->act = $act;
        $this->opt = $opt;
    }

    public function runWS($opt = [])
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password
        ];

        $req = $client->post($this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response, true);

        if ($this->act != 'GetToken') {
            if ($result['error_code'] == 0) {
                $token = $result['data']['token'];

                $params = [
                    "act" => $this->act,
                    "token" => $token,
                ];
                $params = array_merge($params, $this->opt);
                $req = $client->post($this->url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => json_encode($params)
                ]);
                $response = $req->getBody();
                $result = json_decode($response, true);
            }
        }

        return $result;
    }
}
