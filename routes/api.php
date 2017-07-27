<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Authentication Routes
Route::post('/v1/login', 'LoginAPI@authenticate'); //Login
Route::post('/v1/register', 'RegisterAPI@userCreate'); //Register

//Customer Add, Edit, View, Check
Route::post('/v1/customeradd', 'CustomerAPI@customerCreate'); //Add Customer
Route::post('/v1/customeredit', 'CustomerAPI@customerEdit'); //Edit Customer
Route::post('/v1/customerview', 'CustomerAPI@customerFetchAll'); //Fetch All Customers
Route::post('/v1/customercheck', 'CustomerAPI@customerFetch'); //Check Customer

//Order & Item Add, Order View
Route::post('/v1/ordercreate', 'OrderAPI@orderCreate'); //Create Order
Route::post('/v1/orderview', 'OrderAPI@orderFetch'); //Fetch All Orders
Route::post('/v1/customerorderview', 'OrderAPI@customerOrderFetch'); //Fetch Customers Orders

//Item View / Update
Route::post('/v1/itemview', 'ItemAPI@itemFetchAll'); //Fetch All Orders
Route::post('/v1/itememployeeupdate', 'ItemAPI@itemEmployeeUpdate'); //Assign an employee to an item
Route::post('/v1/itemcustomermeasurements', 'ItemAPI@itemCustomerMeasurements'); //Fetch Customer Measurements for a specific dress type

//Employee Create / View / Update
Route::post('/v1/employeecreate', 'EmployeeAPI@employeeCreate'); //Creates new Employee for the store
Route::post('/v1/employeefetchall', 'EmployeeAPI@employeeFetchAll'); //Fetches all employees of the store
Route::post('/v1/employeeassign', 'EmployeeAPI@employeeAssign'); //Assigns a employee to a given item