<?php


namespace App\Services;


use RetailCrm\ApiClient;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
class ChangeDate
{
    private $client;
    public function __construct()
    {
        $this->client = new ApiClient(
        'https://demo.retailcrm.ru',
        'cgUqZJZVhswXpq7D5Kd2s3FiN5nr6qoL',
        ApiClient::V5);
    }
    public function getOrderDate(string $id)
    {
        try {
            $response = $this->client->request->ordersGet($id, 'id');
        } catch (\RetailCrm\Exception\CurlException $e) {
            return ['status' => 500,
                'message' =>"Connection error: " . $e->getMessage()
            ];
        }

        if ($response->isSuccessful()) {
            return [
                      'status' => $response->getStatusCode(),
                       'createdAt' => $response->order['createdAt'],
                       'data_order_change' => $response->order['customFields']['data_order_change']
                   ];
        } else {
            return [
                'status' => $response->getStatusCode(),
                'message' =>sprintf(
                    "Error: [HTTP-code %s] %s",
                    $response->getStatusCode(),
                    $response->getErrorMsg()
                )
            ];
        }
    }

    public function setNewOrderDate(string $id, string $date,string $data_order_change,$act)
    {
        $dateNew = new DateTime($date);
        $datePlus = $dateNew->modify($act.$data_order_change.' day');

        try {
            $response = $this->client->request->ordersEdit(['id'=>$id,'createdAt'=>$datePlus->format('Y-m-d H:i:s')],'id');
        } catch (\RetailCrm\Exception\CurlException $e) {
            return ['status' => 500,
                'message' =>"Connection error: " . $e->getMessage()
            ];
        }

        if ($response->isSuccessful()) {
            return [
                'status' => $response->getStatusCode(),
                'message' => $response->order['createdAt']

            ];
        } else {
            return [
                'status' => $response->getStatusCode(),
                'message' =>sprintf(
                    "Error: [HTTP-code %s] %s",
                    $response->getStatusCode(),
                    $response->getErrorMsg()
                )
            ];
        }
    }
}