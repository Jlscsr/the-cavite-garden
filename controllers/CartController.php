<?php

// Helpers
use Helpers\JWTHelper;
use Helpers\ResponseHelper;
use Helpers\CookieManager;

use Validators\CartValidator;

// Models
use Models\CartModel;

class CartController
{
    private $jwt;
    private $cartModel;
    private $cookieManager;

    public function __construct($pdo)
    {
        $this->jwt = new JWTHelper();
        $this->cartModel = new CartModel($pdo);
        $this->cookieManager = new CookieManager();
    }

    /**
     * Retrieves the products in the customer's cart.
     *
     * This function retrieves the products in the customer's cart by using the
     * customer ID obtained from the token. It then calls the
     * `getCostumerCartProducts` method of the `cartModel` object to fetch the
     * cart products. If no products are found, it sends a 404 error response.
     * Otherwise, it sends a success response with the retrieved cart products.
     *
     * @throws RuntimeException if there is an error retrieving the cart products
     * @return void
     */
    public function getCostumerCartProducts(): void
    {
        try {
            $customerID = $this->getCustomerIDFromToken();

            $response = $this->cartModel->getCostumerCartProducts((int) $customerID);

            if (!$response) {
                ResponseHelper::sendErrorResponse('No products found in cart', 404);
                exit;
            }

            ResponseHelper::sendSuccessResponse($response, 'Cart products retrieved successfully', 200);
        } catch (RuntimeException $e) {
            ResponseHelper::sendErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * A function that adds a product to the cart.
     *
     * @param array $payload An array containing the product information.
     * @throws RuntimeException If there is an error adding the product to the cart.
     * @throws InvalidArgumentException If the payload is invalid.
     * @return void
     */
    public function addProductToCart(array $payload): void
    {
        try {
            CartValidator::validateAddProductToCartRequest($payload);

            $payload['customerID'] = $this->getCustomerIDFromToken();

            $response = $this->cartModel->addProductToCart($payload);

            if (!$response) {
                ResponseHelper::sendErrorResponse('Failed to add product to cart', 400);
                exit;
            }

            ResponseHelper::sendSuccessResponse([], 'Product added to cart successfully', 201);
        } catch (RuntimeException $e) {
            ResponseHelper::sendErrorResponse($e->getMessage(), 500);
        } catch (InvalidArgumentException $e) {
            ResponseHelper::sendErrorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Deletes a product from the cart.
     *
     * @param array $parameter An array containing the product information.
     * @throws RuntimeException If there is an error deleting the product from the cart.
     * @throws InvalidArgumentException If the parameter is invalid.
     * @return void
     */
    public function deleteProductFromCart(array $parameter): void
    {
        try {
            CartValidator::validateDeleteProductToCartRequest($parameter);

            $customerID = $this->getCustomerIDFromToken();

            $response = $this->cartModel->deleteProductFromCart($customerID, (int) $parameter['id']);

            if (!$response) {
                ResponseHelper::sendErrorResponse('Failed to delete product from cart', 400);
                exit;
            }

            ResponseHelper::sendSuccessResponse([], 'Product deleted from cart successfully', 201);
        } catch (RuntimeException $e) {
            ResponseHelper::sendErrorResponse($e->getMessage(), 500);
        } catch (InvalidArgumentException $e) {
            ResponseHelper::sendErrorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Retrieves the customer ID from the JWT token.
     *
     * @throws RuntimeException if there is an error decoding the JWT token
     * @return int The customer ID extracted from the token
     */
    private function getCustomerIDFromToken(): int
    {
        $cookieHeader = $this->cookieManager->validateCookiePressence();
        $response = $this->cookieManager->extractAccessTokenFromCookieHeader($cookieHeader);
        $decodedToken = $this->jwt->decodeJWTData($response['token']);

        return $decodedToken->id;
    }
}