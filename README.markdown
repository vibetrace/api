Vibetrace API
=============

This document describes the Vibetrace API which offers clients realtime recommendations based on user events.
Please refer to this document for an up-to-date api documentation and submit your feature requests, bugs or errors in this repo's issue tracker.

General
-------

This document uses the following notations:
 - `app` to specify the client of the Vibetrace API. This is you.
 - `item` to specify the unit that is tracked and/or recommened by our server. This could be a product in an ecommerce site, etc.
 - `user` to specify the user of the app. This could be a shopper on an online store, etc.
The latest version of the api is `v3`. Previous versions will soon be deprecated.
The API closely follows [RESTful](http://en.wikipedia.org/wiki/Representational_state_transfer) principles and exposes two types of resources: `items` and `events`.
The API base endpoint is `https://vibetrace.com/api/v3/app/:appId/` where `:appId` is the `APP_ID` string received upon registration.
The API data format, both for payloads and responses, is [JSON](http://en.wikipedia.org/wiki/Json)

Response Types
--------------

Standard HTTP response codes are used:
1. `200 Ok` - returned by successful GET/PUT requests.
2. `201 Created` - returned by succesfull POST requests.
3. `204 No Content` - returned by successfull DELETE requests to show that the resource is now deleted.
4. `401 Unauthorized` - returned by all http methods when auth credentials are missing, are wrong or access to the specified resource is forbidden.
5. `404 Not Found` - returned by all http methods when the resource specified by URI does not exist.
6. `500 Internal Server Error` - returned by all http methods when an error has occured on Vibetrace servers. Please report these errors back to up at [support@vibetrace.com](mailto:support@vibetrace.com)

Auth
----

All Vibetrace API traffic must be done via [HTTPS](http://en.wikipedia.org/wiki/Https) and using [Basic HTTP authentication](http://en.wikipedia.org/wiki/Basic_access_authentication)
An `APP_ID` and `APP_SECRET` will be generated upon app registration. Keep them safe!

Basic HTTP Authorization is required for all API calls. Make sure you add the following http header to **all** your requests:
````
Authorization: Basic aHR0cHdhdGNoOmY=
````
Where `aHR0cHdhdGNoOmY=` is a base64 encoded string `APP_ID:APP_SECRET`
`401 Unauthorized` Http Error will be returned if the `Authorization` header is not supplied or if it contains incorrect credentials.

Products
--------
`https://vibetrace.com/api/v3/app/:appId/items`

Allows apps to upload/inspect/modify/remove items of interest.

1. `POST https://vibetrace.com/api/v3/app/:appId/items`
 - `Accept: application/json`
 - `Content-Type: application/json`
 - uploads a new item to Vibetrace Data Store.
 - payload should be a simple JSON'd object where the following fields are mandatory:
    @param {String} [item.id] - REQUIRED app's unique identifier for the particular item. Needed in the `events` endpoint to identify an event's item.
    @param {String} [item.name] - REQUIRED item's name
    @param {String} [item.category] - REQUIRED item's category
 - if successful, the response will be `201 Created`

2. `GET https://vibetrace.com/api/v3/app/:appId/items/:itemId`
 - `Accept: application/json`
 - usefull to inspect a previously uploaded item
 - for `:itemId` use the `id` specified in the POST request
 - if successfull, the response will be `200 Ok` and the payload will be a JSON of the document initially uploaded

3. `PUT https://vibetrace.com/api/v3/app/:appId/items/:itemId`
 - `Accept: application/json`
 - `Content-Type: application/json`
 - usefull to updating the properties of an existing item in the Vibetrace Data Store.
 - accepted payload is similar to the POST request, except that `id` properties will be ignored. _!You cannot change the id of an item._
 - if successful, the response will be `200 Ok` and the new resource will be returned as json.

4. `DELETE https://vibetrace.com/api/v3/app/:appId/items/:itemId`
 - usefull when an item is no longer in the app's collection and should be removed from Vibetrace's recommendation engine.
 - if sucessful, the response will be `204 No Content` to notify the app that the resource no longer exists

Events
------
`https://vibetrace.com/api/v3/app/:appId/events`

Allows apps to register user's events with Vibetrace and to receive recommendations based on this and previous events.



- upload products: rest api for products: POST, PUT, DELETE, GET
    /api/v3/apps/:appId/products
- upload event: rest api on events/actions: pageview, search
    /api/v3/apps/:appId/events
- payloads & responses: JSON

Recommendation
--------------

- events endpoint: GET, POST