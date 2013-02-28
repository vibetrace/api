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

The API base endpoint is `https://app.vibetrace.com/api/v3/apps/:appId/` where `:appId` is the `APP_ID` string received upon registration.

The API data format, both for payloads and responses, is [JSON](http://en.wikipedia.org/wiki/Json)

Response Types
--------------

Standard HTTP response codes are used:

1. `200 Ok` - returned by successful GET/PUT requests.
2. `201 Created` - returned by succesfull POST requests.
3. `204 No Content` - returned by successfull DELETE requests to show that the resource is now deleted.
4. `400 Bad Request` - returned when a request is badly formatted or has incorrect parameters.
5. `401 Unauthorized` - returned by all http methods when auth credentials are missing or are wrong.
6. `403 Forbidden` - returned by all http methods when the client is authenticated but he does not have access to the requested resource.
6. `404 Not Found` - returned by all http methods when the resource specified in the URI does not exist.
7. `500 Internal Server Error` - returned by all http methods when an error has occured on Vibetrace servers. Please report these errors at [alext@vibetrace.com](mailto:alext@vibetrace.com)

Auth
----

All Vibetrace API traffic must be done via [HTTPS](http://en.wikipedia.org/wiki/Https) and using [Basic HTTP authentication](http://en.wikipedia.org/wiki/Basic_access_authentication)

An `APP_ID`, `API_KEY` and `API_SECRET` will be generated upon app registration. Keep them safe!

Basic HTTP Authorization is required for most API calls. Make sure you add the following http header to the endpoints that require it:
````
Authorization: Basic aHR0cHdhdGNoOmY=
````
Where `aHR0cHdhdGNoOmY=` is an _example_ of a base64 encoded string formed like so: `API_KEY:API_SECRET`

`401 Unauthorized` Http Error will be returned if the `Authorization` header is not supplied or if it contains incorrect credentials.


Items
-----

`https://app.vibetrace.com/api/v3/apps/:appId/items`

Allows apps to upload/inspect/modify/remove items of interest.


1. `POST https://app.vibetrace.com/api/v3/apps/:appId/items`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - uploads a new item to Vibetrace Data Store.
 - payload should be a simple JSON'd object where the following fields are mandatory:

    ````
    @param {Object} item - the body of the http request should be a JSON object.
    @param {String} [item.id] - REQUIRED app's unique identifier for the particular item. Needed in the `events` endpoint to identify an event's item.
    @param {String} [item.name] - REQUIRED item's name
    @param {String} [item.category] - REQUIRED item's category
    @param {String} [item.url] - REQUIRED item's URL, needs to be unique, no two products can have the same url.
    @param {String} [item.image] - REQUIRED item's image URL
    @param {String} [item.description] - REQUIRED item's description
    @param {Number} [item.price] - REQUIRED item's standard price in EURO
    @param {String} [item.location] - OPTIONAL item's standard price in EURO
    ````

 - _NOTE_ all properties that are not specified in the signature above will be ignored.
 - if payload is not correct, a `400 Bad Request` is returned, together with a detailed error payload in json format.
 - if successful, the response will be `201 Created`, and the payload of the response is a json representation of the resource.
 - a successful POST response payload returns the same data as the request payload and has the following signature:

    ````
    @param {Object} item - the body of the response
    @param {String} [item.id]
    @param {String} [item.name]
    @param {String} [item.category]
    @param {String} [item.url]
    @param {String} [item.image]
    @param {String} [item.description]
    @param {Number} [item.price]
    @param {String} [item.location]
    ````

 - below is an example of using `curl` for creating a new item:

    ````
    curl --request POST --header "Content-Type: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"id":"1","name":"product1", "category": "category1", "url": "http://domain.com/products/1", "image": "http://domain.com/products/1.png", "description": "product1 in category1", "price": 100, "location": "Romania"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/items
    ````

 - the response for the above request is:

    ````json
    {
       "id":"1",
       "name":"product1",
       "category":"category1",
       "url":"http://domain.com/products/1",
       "image":"http://domain.com/products/1.png",
       "description":"product1 in category1",
       "price":100,
       "location":"Romania"
    }
    ````


2. `GET https://app.vibetrace.com/api/v3/apps/:appId/items/:itemId`

 - `Accept: application/json`
 - usefull to inspect a previously uploaded item
 - for `:itemId` use the `id` specified when the item was created. Note that it's the app's responsability to make sure these id's are accurate. This API will only check for it's uniqueness.
 - if no resource with the specified `:itemId` and a matching `:appId` is found, the response will be `404 Not Found`.
 - if successfull, the response will be `200 Ok` and the payload will be a JSON of the document initially uploaded.
 - the response has the following signature:

    ````
    @param {Object} item - the body of the response
    @param {String} [item.id]
    @param {String} [item.name]
    @param {String} [item.category]
    @param {String} [item.url]
    @param {String} [item.image]
    @param {String} [item.description]
    @param {Number} [item.price]
    @param {String} [item.location]
    ````

 - below is an example of using `curl` to inspect an existing item by it's id:

    ````bash
    curl --request GET --header "Accept: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/items/1
    ````

 - the response for the above request is:

    ````json
    {
      "id": "1",
      "name": "product1",
      "category": "category1",
      "url": "http://yourshop.com/products/1",
      "image": "http://yourshop.com/products/1.png",
      "description": "product1 in category1",
      "price": 100,
      "location": "Bucuresti"
    }
    ````


3. `HEAD https://app.vibetrace.com/api/v2/apps/:appId/items/:itemId`

 - usefull for checking if a item identified by `:itemId` exists and belongs to an app identified by `:appId`.
 - this endpoint returns 200 if such an item exists or 404 if it doesn't.
 - _NOTE_ This endpoint does not require HTTP Basic Auth, meaning you can request this resource without authentication.
 - below is an example of using `curl` to issue a HEAD request.

    ````bash
    curl --request HEAD --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/items/1
    ````


4. `PUT https://app.vibetrace.com/api/v3/apps/:appId/items/:itemId`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - usefull to updating the properties of an existing item in the Vibetrace Data Store.
 - for `:itemId` use the `id` specified when the item was created. Note that it's the app's responsability to make sure these id's are accurate. This API will only check for it's uniqueness.
 - _NOTE_ you cannot update the id of an item, the `id` property will be ignored. _!You cannot change the id of an item._
 - _NOTE_ when updating the url of an item, make sure it's unique within the domain of items synchronized with vibetrace.
 - if successful, the response will be `200 Ok` and the new resource will be returned as json.
 - accepted payload is similar to the POST request, here's the payload signature:

    ````
    @param {Object} item - the body of the response
    @param {String} [item.name]
    @param {String} [item.category]
    @param {String} [item.url]
    @param {String} [item.image]
    @param {String} [item.description]
    @param {Number} [item.price]
    @param {String} [item.location]
    ````

 - _NOTE_ all properties that are not specified in the signature above will be ignored.
 - below is an example of using `curl` for updating an existing item:

    ````bash
    curl --request PUT --header "Content-Type: application/json" --header "Accept: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"category": "category2"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/items/1
    ````

 - the response will be

    ````json
    {
       "id":"1",
       "name":"product1",
       "category":"category2",
       "url":"http://domain.com/products/1",
       "image":"http://domain.com/products/1.png",
       "description":"product1 in category1",
       "price":100,
       "location":"Romania"
    }
    ````


5. `DELETE https://app.vibetrace.com/api/v3/apps/:appId/items/:itemId`

 - usefull when an item is no longer in the app's collection and should be removed from Vibetrace's recommendation engine.
 - for `:itemId` use the `id` specified when the item was created. Note that it's the app's responsability to make sure these id's are accurate. This API will only check for it's uniqueness.
 - if sucessful, the response will be `204 No Content` to notify the app that the resource no longer exists
 - _NOTE_ For reasons of efficiency, this method does not check if the item exists. If it doesn't it will still return `204 No Content`.
 - below is an example of using `curl` to delete an existing item:

    ````bash
    curl --request DELETE  --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/items/1
    ````

 - the response payload will be empty


Events
------

`https://app.vibetrace.com/api/v3/apps/:appId/events`

Allows apps to register user's events with Vibetrace and to receive recommendations in real-time based on this and previous events.

App can only _write_ events to Vibetrace, thus only POST endpoints are exposed.

**NOTE** The app is responsable to make sure the information sent to Vibetrace is accurate. Sending inconsistent values will result in poor recommendations. If you encounter dificulties, please contact us at [alext@vibetrace.com](mailto:alext@vibetrace.com)


1. `POST https://app.vibetrace.com/api/v3/apps/:appId/events/viewitem`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers an `viewItem` event on Vibetrace. Events are unique and immutable.
 - this endpoint requires ID's for current user, session and item. This way Vibetrace keeps track of user sessions and viewed items.
 - the payload is a JSON object with the following signature:

    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.userId] - OPTIONAL, unique identifier for the app's user. Only for registered users. This allows vibetrace to track users' preferences across multiple sessions.
    @param {String} [payload.sessionId] - REQUIRED, unique identifier for the user's session.
    @param {String} [payload.itemId] - REQUIRED, unique identifier of the added item. This id **must** be already declared and syncd with Vibetrace using the `/items` API. This endpoint will return 400 otherwise.
    @param {String} [payload.referer] - OPTIONAL, url of the referer site, only relevant when the url is external. Vibetrace parses the referral page to extract further information about the user's interests.
    ````

 - if successful, it returns `201 Created` status code with an empty http body.
 - below is an example of using `curl` for creating a new viewitem event:

    ````
    curl --request POST --header "Content-Type: application/json"  --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"referer": "http://google.com/q=some+query", "sessionId": "1", "itemId": "1", "userId": "1"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/events/viewitem
    ````


2. `POST https://app.vibetrace.com/api/v3/apps/:appId/events/viewcategory`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers an `viewCategory` event on Vibetrace which means the user is on a category page.
 - this endpoint requires ID's for current user, session and category. This way Vibetrace keeps track of user sessions and viewed categories.
 - the payload is a JSON object with the following signature:

    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.userId] - OPTIONAL, unique identifier for the app's user. Only for registered users. This allows vibetrace to track users' preferences across multiple sessions.
    @param {String} [payload.sessionId] - REQUIRED, unique identifier for the user's session.
    @param {String} [payload.category] - REQUIRED, unique identifier of the category being viewed. This should be the same string as the one supplied when adding items to vibetrace in the `category` field of the POST `/items` endpoint.
    @param {String} [payload.referer] - OPTIONAL, url of the referer site, only relevant when the url is external. Vibetrace parses the referral page to extract further information about the user's interests.
    ````

 - if successful, it returns `201 Created` status code with an empty http body.
 - below is an example of using `curl` for creating a new viewitem event:

    ````
    curl --request POST --header "Content-Type: application/json"  --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"referer": "http://google.com/q=some+query", "sessionId": "1", "categoryId": "1", "userId": "1"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/events/viewcategory
    ````


3. `POST https://app.vibetrace.com/api/v3/apps/:appId/events/search`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers a `search` event on Vibetrace. Events are unique and immutable.
 - use this endpoint to let Vibetrace know what the user is searching for on your site and allows the app to make personalized recommendations to the user.
 - this endpoint requires ID's for current user, session. This way Vibetrace keeps track of user sessions.
 - the payload is a JSON object with the following signature:

    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.userId] - OPTIONAL, unique identifier for the app's user. Only for registered users. This allows vibetrace to track users' preferences across multiple sessions.
    @param {String} [payload.sessionId] - REQUIRED, unique identifier for the user's session.
    @param {String} [payload.query] - REQUIRED, the string introduced in the query input.
    @param {String} [payload.referer] - OPTIONAL, url of the referer site, only relevant when the url is external. Vibetrace parses the referral page to extract further information about the user's interests.
    ````

 - if successful, it returns `201 Created` status code with an empty http body.
 - below is an example of using `curl` for creating a new `search` event:

    ````
    curl --request POST --header "Content-Type: application/json"  --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"query": "awesome product", "referer": "http://google.com/q=some+query", "sessionId": "1", "userId": "1"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/events/search
    ````


4. `POST https://app.vibetrace.com/api/v3/apps/:appId/events/addtocart`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers a `search` event on Vibetrace. Events are unique and immutable.
 - this event should be sent when the user adds an item into the app's cart. Currently the api does not support adding multiple items to cart at once so for quantity larger than 1, use this endpoint multiple times.
 - to identify the cart the app must supply an unique cart id. This way Vibetrace can keep track of user shopping sessions.
 - the payload is a JSON object with the following signature:

    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.userId] - OPTIONAL, unique identifier for the app's user. Only for registered users. This allows vibetrace to track users' preferences across multiple sessions.
    @param {String} [payload.sessionId] - REQUIRED, unique identifier for the user's session.
    @param {String} [payload.cartId] - REQUIRED, unique identified of a shopping cart session.
    @param {String} [payload.itemId] - REQUIRED, unique identifier of the added item. This id **must** be already declared and syncd with Vibetrace using the `/items` API. This endpoint will return 400 otherwise.
    @param {String} [payload.referer] - OPTIONAL, url of the referer site, only relevant when the url is external. Vibetrace parses the referral page to extract further information about the user's interests.
    ````

 - if successful, it returns `201 Created` status code with an empty http body.
 - below is an example of using `curl` for creating a new `add to cart` event:

    ````
    curl --request POST --header "Content-Type: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"sessionId": "1", "itemId": "1", "userId": "1", "cartId": "1", "referer": "http://some-campaign.com"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/events/addtocart
    ````


5. `POST https://app.vibetrace.com/api/v3/apps/:appId/events/checkout`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers a cart checkout to vibetrace. These events are unique and immutable.
 - this event should be sent when the user completed the checkout process and is on the `Thank you` page.
 - the cart session that has just finnished is determined by the `cartId` payload variable.
 - the payload is a JSON object with the following signature:

    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.userId] - OPTIONAL, unique identifier for the app's user. Only for registered users. This allows vibetrace to track users' preferences across multiple sessions.
    @param {String} [payload.sessionId] - REQUIRED, unique identifier for the user's session.
    @param {String} [payload.cartId] - REQUIRED, unique identified of a shopping cart session.
    @param {String} [payload.referer] - OPTIONAL, url of the referer site, only relevant when the url is external. Vibetrace parses the referral page to extract further information about the user's interests.
    ````

 - if successful, it returns `201 Created` status code with an empty http body.
 - below is an example of using `curl` for creating a new `add to cart` event:

    ````
    curl --request POST --header "Content-Type: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --data-binary '{"sessionId": "1", "userId": "1", "cartId": "1", "referer": "http://some-campaign.com"}' --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c/events/checkout
    ````


Apps
----

`https://app.vibetrace.com/api/v3/apps/`

Vibetrace allows apps to register in a programatic way using the endpoint described in this section.

**NOTE** The registration process has multiple steps. After a successfull `POST` api call. Vibetrace will then contact you, enable the app and start the integration process.


1. `POST https://app.vibetrace.com/api/v3/apps`

 - `Accept: application/json`
 - `Content-Type: application/json`
 - registers a new app in the Vibetrace engine.
 - _NOTE_ this endpoint requires an HTTPS request but does enforce authentication.
 - the payload is a JSON object with the following signature:
`
    ````
    @param {Object} payload - the body of the http request should be a JSON object.
    @param {String} [payload.email] - REQUIRED, an email address which we will use to contact you.
    @param {String} [payload.url] - REQUIRED, the url of your public-facing website where you want to integrate Vibetrace services
    @param {String} [payload.feed] - REQUIRED, the item feed of you web-site. This is required for the recommender service that Vibetrace provides.
    ````

 - the response of a successful requst will contain the now registered app's credentials. The payload signature is the following:

    ````
    @param {Object} payload - the body of the response is a JSON object.
    @param {String} [payload.id] - the `APP_ID` of the newly registered application.
    @param {String} [payload.apiKey] - the `API_KEY` used to authenticate requests made by the app to Vibetrace.
    @param {String} [payload.apiSecret] - the `API_SECRET` used to authenticate requests made by the app to Vibetrace.
    @param {String} [payload.url] - the app url sent in the request.
    @param {String} [payload.email] - the email sent in the request.
    @param {String} [payload.feed] - the feed url sent in the request.
    @param {Boolean} [payload.isActive] - a flag indicating whether the app is ready to send requests to Vibetrace or not.
    ````

 - an email notification will also be sent to the supplied `email` parameter to kickstart the collaboration.
 - exemple of using curl to register a new application:

    ````
    curl --request POST --header "Content-Type: application/json" --data-binary '{"email": "me@domain.com", "url": "http://domain.com", "feed": "http://domain.com/feed.json", "version": "v3"}' --insecure https://app.vibetrace.com/api/v3/apps
    ````

 - the response to the above request is:

    ````json
    {
        "id": "50fc3bb47cfd33723b00000c",
        "apiKey": "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=",
        "apiSecret": "U1tfKBtyJstc+LqOUem99YkI1hM=",
        "url": "http://domain.com",
        "email": "me@domain.com",
        "feed": "http://domain.com/feed.json",
        "isActive": false
    }
    ````



2. `GET https://app.vibetrace.com/api/v3/apps/:appId`

 - `Content-Type: application/json`
 - use this endpoint to fetch information about your app, including api tokens and app status withing Vibetrace.
 - _NOTE_ this endpoint requires HTTPS and authentication. Please refer to the [Auth](https://github.com/vibetrace/api#auth) section to find out how to authenticate your requests.
 - for `:appId` use the APP_ID received upon registration.
 - a correct request returns `200 Ok` with a payload that has the following signature:

    ````
    @param {Object} payload - the body of the response is a JSON object.
    @param {String} [payload.id] - the `APP_ID` of the newly registered application.
    @param {String} [payload.apiKey] - the `API_KEY` used to authenticate requests made by the app to Vibetrace.
    @param {String} [payload.apiSecret] - the `API_SECRET` used to authenticate requests made by the app to Vibetrace.
    @param {String} [payload.url] - the app url sent in the request.
    @param {String} [payload.email] - the email sent in the request.
    @param {String} [payload.feed] - the feed url sent in the request.
    @param {Boolean} [payload.isActive] - a flag indicating whether the app is ready to send requests to Vibetrace or not.
    ````

 - in case the `:appId` is wrongly set or doesn't match the auth tokens a `403 Unauthorized` will be returned.
 - exemple of using curl to fetch information about a registered application:

    ````
    curl --request GET --header "Content-Type: application/json" --user "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=:U1tfKBtyJstc+LqOUem99YkI1hM=" --insecure https://app.vibetrace.com/api/v3/apps/50fc3bb47cfd33723b00000c
    ````

 - the response for the above request is:

    ````json
    {
        "id": "50fc3bb47cfd33723b00000c",
        "apiKey": "Cf4S4qrr/OSKzKMl3Tm/NTMECRM=",
        "apiSecret": "U1tfKBtyJstc+LqOUem99YkI1hM=",
        "url": "http://domain.com",
        "email": "me@domain.com",
        "feed": "http://domain.com/feed.json",
        "isActive":false
    }
    ````


Support
-------
For any questions, feedback, issues or feature requests please use the issue tracker on this repo or email us at [alext@vibetrace.com](mailto:alext@vibetrace.com).
