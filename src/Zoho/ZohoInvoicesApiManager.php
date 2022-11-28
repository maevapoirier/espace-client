<?php

namespace App\Zoho;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZohoInvoicesApiManager
{
    public function __construct(
        private HttpClientInterface $zohoInvoicesApi
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
        $this->zohoInvoicesApi = $zohoInvoicesApi->withOptions([
            'headers' => [
                'orgid' => $_ENV['ZOHODESK_API_ORG_ID'],
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken
            ]
        ]);
    }

    public function getAccessToken(): ?string {
        try {
            $response = $this->zohoInvoicesApi->request('POST', 'https://accounts.zoho.eu/oauth/v2/token', $this->params);
            $accessToken = json_decode($response->getContent(), true)['access_token'];
            return $accessToken;
        } catch (\Exception $e) {
            $this->lastRequestException = $e;
            return null;
        }
    }

    public function getInvoices(string $id): ?array
    {
        try {
            $response = $this->zohoInvoicesApi->request('GET', 'https://invoice.zoho.eu/api/v3/invoices?customer_id='.$id);
            $invoices = json_decode($response->getContent(), true)['invoices'];
            return $invoices;
        } catch (\Exception $e) {
            $this->lastRequestException = $e;
            return null;
        }
    }

    public function getInvoicePdf(string $id)
    {
        try {
            $response = $this->zohoInvoicesApi->request('GET', 'https://invoice.zoho.eu/api/v3/invoices/'.$id.'?accept=pdf');
            $response->headers->set('Content-Type', 'application/pdf');
            //dd($response->getContent());
            return $response->getContent();
        } catch (\Exception $e) {
            $this->lastRequestException = $e;
            return null;
        }
    }

   
}