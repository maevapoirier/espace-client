<?php

namespace App\Zoho;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZohodeskApiManager
{
    public function __construct(
        private HttpClientInterface $zohodeskApi
    ) {
        $this->params = [
            'query' => [
                'refresh_token' => $_ENV['ZOHODESK_API_REFRESH_TOKEN'],
                'client_id' => $_ENV['ZOHODESK_API_CLIENT_ID'],
                'client_secret' => $_ENV['ZOHODESK_API_CLIENT_SECRET'],
                'grant_type' => 'refresh_token'
            ]
        ];

        $accessToken = $this->getAccessToken();
        $this->zohodeskApi = $zohodeskApi->withOptions([
            'headers' => [
                'orgid' => $_ENV['ZOHODESK_API_ORG_ID'],
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken
            ]
        ]);
    }

    public function getAccessToken(): ?string {
        try {
            $response = $this->zohodeskApi->request('POST', 'https://accounts.zoho.eu/oauth/v2/token', $this->params);
            $accessToken = json_decode($response->getContent(), true)['access_token'];
            return $accessToken;
        } catch (\Exception $e) {
            $this->lastRequestException = $e;
            return null;
        }
    }

    public function getIssue(string $id): ?array
    {
        try {
            $response = $this->zohodeskApi->request('GET', 'https://desk.zoho.eu/api/v1/tickets/search?ticketNumber=' . $id);
            $issue = json_decode($response->getContent(), true)['data'][0];
            return $issue;
        } catch (\Exception $e) {
            $this->lastRequestException = $e;
            return null;
        }
    }

    public function getIssues(string $id): ?array
    {
        return null;
    }
}