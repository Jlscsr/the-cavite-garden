<?php

class Route
{
    private $routes;

    public function __construct()
    {
        $this->routes = [
            "api/subcategory/add" => [
                "handler" => "SubCategoriesController@addNewSubCategory",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/employees/edit/:id" => [
                "handler" => "EmployeesController@editEmployee",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/employees/add" => [
                "handler" => "EmployeesController@addNewEmployee",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/employee/info" => [
                "handler" => "EmployeesController@getEmployeeById",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/employees" => [
                "handler" => "EmployeesController@getAllEmployees",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/customer/address/add" => [
                "handler" => "CustomersController@addNewUserAddress",
                "middleware" => true,
                "requiredRole" => "customer"
            ],
            "api/customer/info" => [
                "handler" => "CustomersController@getCustomerById",
                "middleware" => true,
                "requiredRole" => "both"
            ],
            "api/customers" => [
                "handler" => "CustomersController@getAllCustomers",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/transactions/:status" => [
                "handler" => "TransactionController@getAllTransactions",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/transaction/status/:id" => [
                "handler" => "TransactionController@updateTransactionStatus",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/transaction/add" => [
                "handler" => "TransactionController@addNewTransaction",
                "middleware" => true,
                "requiredRole" => "customer"
            ],
            "api/cart/delete/:id" => [
                "handler" => "CartController@deleteProductFromCart",
                "middleware" => true,
                "requiredRole" => "customer"
            ],
            "api/cart/add" => [
                "handler" => "CartController@addToCart",
                "middleware" => true,
                "requiredRole" => "customer"
            ],
            "api/cart" => [
                "handler" => "CartController@getCostumerCartProducts",
                "middleware" => true,
                "requiredRole" => "customer"
            ],
            "api/auth/logout" => [
                "handler" => "AuthenticationController@logout",
                "middleware" => false
            ],
            "api/auth/check" => [
                "handler" => "AuthenticationController@checkToken",
                "middleware" => false
            ],
            "api/auth/login" => [
                "handler" => "AuthenticationController@login",
                "middleware" => false
            ],
            "api/auth/register" => [
                "handler" => "AuthenticationController@register",
                "middleware" => false,
            ],
            "api/category/edit/:id" => [
                "handler" => "CategoriesController@editCategory",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/category/delete/:id" => [
                "handler" => "CategoriesController@deleteCategory",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/product/category/:id" => [
                "handler" => "ProductsController@getAllProductsByCategory",
                "middleware" => true,
                "requiredRole" => "both"
            ],
            "api/product/delete/:id" => [
                "handler" => "ProductsController@deleteProduct",
                "middleware" => false
            ],
            "api/product/edit/:id" => [
                "handler" => "ProductsController@editProduct",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/category/add" => [
                "handler" => "CategoriesController@addNewCategory",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/product/add" => [
                "handler" => "ProductsController@addNewProduct",
                "middleware" => true,
                "requiredRole" => "admin"
            ],
            "api/product/:id" => [
                "handler" => "ProductsController@getProductByID",
                "middleware" => true,
                "requiredRole" => "both"
            ],
            "api/categories" => [
                "handler" => "CategoriesController@getAllPlantCategories",
                "middleware" => true,
                "requiredRole" => "both"
            ],
            "api/products" => [
                "handler" => "ProductsController@getAllProducts",
                "middleware" => true,
                "requiredRole" => "both"
            ],
        ];
    }

    public function get_route($url_request)
    {
        foreach ($this->routes as $route => $handler) {
            // Check for direct match
            if ($route === $url_request) {
                return $handler;
            }

            // Check for dynamic parameters
            $route_parts = explode('/', $route);
            $requests_parts = explode('/', $url_request);

            if (count($route_parts) === count($requests_parts)) {
                $params = [];
                for ($i = 0; $i < count($route_parts); $i++) {
                    if (strpos($route_parts[$i], ':') === 0) {
                        $param_name = substr($route_parts[$i], 1);
                        $params[$param_name] = $requests_parts[$i];
                    } else if ($route_parts[$i] !== $requests_parts[$i]) {
                        break;
                    }
                }

                if (!empty($params)) {
                    return [
                        'handler' => $handler,
                        'params' => $params
                    ];
                }
            }
        }

        return null;
    }
}
