<?php

use Helpers\JWTHelper;
use Helpers\ResponseHelper;
use Helpers\HeaderHelper;

use Models\TransactionModel;

class TransactionController
{
    private $pdo;
    private $jwt;
    private $transaction_model;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->jwt = new JWTHelper();
        $this->transaction_model = new TransactionModel($pdo);
    }

    public function getAllTransactions($param)
    {
        if (!is_array($param) || empty($param)) {
            ResponseHelper::sendErrorResponse("Invalid data or data is empty", 400);
            return;
        }
        HeaderHelper::setResponseHeaders();
        $status = null;
        if (strpos($param['status'], '-') !== false) {
            $parts = explode("-", $param['status']);
            $status[] = $parts[0];
            $status[] = $parts[1];
        } else {
            $status = $param['status'];
        }

        $response = $this->transaction_model->getAllTransactions($status);

        if (empty($response)) {
            ResponseHelper::sendSuccessResponse([], 'No Transactions found', 200);
            return;
        }

        ResponseHelper::sendSuccessResponse($response, 'Successfully fetched transactions', 200);
    }

    public function getAllTransactionsByCustomerId()
    {
        //
    }

    public function addNewTransaction($data)
    {
        if (!is_array($data) || empty($data)) {
            ResponseHelper::sendErrorResponse("Invalid data or data is empty", 400);
            return;
        }

        HeaderHelper::setResponseHeaders();
        $headers = getallheaders();

        if (!isset($headers['Cookie'])) {
            $this->cookie_manager->resetCookieHeader();
            ResponseHelper::sendUnauthorizedResponse('Invalid Token');
            return;
        }

        $token = $headers['Cookie'];
        $token = str_replace("tcg_access_token=", "", $token);

        $customer_id = $this->jwt->decodeJWTData($token)->id;

        $response = $this->transaction_model->addNewTransaction($customer_id, $data);

        if (!$response) {
            ResponseHelper::sendErrorResponse('Something went wrong', 500);
            return;
        }

        ResponseHelper::sendSuccessResponse(
            $response,
            'Transaction added successfully',
            201
        );
    }

    public function updateTransactionStatus($param, $payload)
    {
        if (!is_array($param) || empty($param) || !is_array($payload) || empty($payload)) {
            ResponseHelper::sendErrorResponse("Invalid data or data is empty", 400);
            return;
        }

        HeaderHelper::setResponseHeaders();

        $transaction_id = $param['id'];
        $status = $payload['status'];

        $response = $this->transaction_model->updateTransactionStatus($transaction_id, $status);

        if (!$response) {
            ResponseHelper::sendErrorResponse('Something went wrong', 500);
            return;
        }

        ResponseHelper::sendSuccessResponse(null, 'Transaction approved successfully', 201);
    }
}
