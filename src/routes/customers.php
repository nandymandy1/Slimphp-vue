<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Enabling Cors
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Get all the customers
$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM customers";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($customers);
    } catch(PDOException $e){
        $err = ["err" => $e->getMessage()];
        echo json_encode($err);
    }
});

// Get Single Customer by Id
$app->get('/api/customer/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM customers WHERE id = $id";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);

    } catch(PDOException $e){
        $err = ["err" => $e->getMessage()];
        echo json_encode($err);
    }
});

// Add New customer
$app->post('/api/customer/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $city = $request->getParam('city');
    $state = $request->getParam('state');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $phone = $request->getParam('phone');
    
    $sql = "INSERT INTO customers (first_name, last_name, phone, email, state, address, city) VALUES
    (:first_name, :last_name, :phone, :email, :state, :address, :city)";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":state", $state);

        $stmt->execute();

        echo json_encode(['msg' => 'Customer Added', "success" => true]);

    } catch(PDOException $e){
        $err = ["err" => $e->getMessage()];
        echo json_encode($err);
    }
});

// Update customer by id
$app->put('/api/customer/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $city = $request->getParam('city');
    $state = $request->getParam('state');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $phone = $request->getParam('phone');
    
    $sql = "UPDATE customers SET
        first_name = :first_name,
        last_name = :last_name,
        state = :state,
        city = :city,
        email = :email,
        phone = :phone,
        address = :address
        WHERE id = $id";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":state", $state);
        
        $stmt->execute();
        echo json_encode(['msg' => 'Customer Updated successfully', 'success' => true]);

    } catch(PDOException $e){
        $err = ["err" => $e->getMessage()];
        echo json_encode($err);
    }
});


// Delete Single Customer by Id
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM customers WHERE id = $id";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo json_encode(['msg' => 'Customer Deleted Successfully', 'success' => true]);

    } catch(PDOException $e){
        $err = ["err" => $e->getMessage()];
        echo json_encode($err);
    }
});