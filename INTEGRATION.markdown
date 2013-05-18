Vibetrace API Integration Guide
===============================


This documents describes the process of integrating the Vibetrace Recommendation API with client app.

The target audience for this document are the web developers charged with integrating Vibetrace API into the client application.

Prior knowledge of software development, [HTTP protocol](http://en.wikipedia.org/wiki/Representational_state_transfer), [REST](http://ro.wikipedia.org/wiki/HTTP) web services and the [javascript](http://en.wikipedia.org/wiki/JavaScript) programming language is assumed.


Support
-------

The [Vibetrace API](https://github.com/vibetrace/api/blob/master/README.markdown) and the Integration process described in this document are fully supported by the Vibetrace team. Please let us know how the integration is progressing, what problems have you encountered and what additional features you may need in order to make your vibetrace experience smoother.

For that and any other equiry or suggestion please email use at [admin@vibetrace.com](mailto:admin@vibetrace.com)


Integration
-----------

The following steps detail the integration with the vibetrace service. Please referer to the [Vibetrace API document](https://github.com/vibetrace/api/blob/master/README.markdown) for a detailed description of how to use the API.


1. Register your application
----------------------------

Vibetrace provides an easy way to register you application, by using the `/apps` [REST api endpoint](https://github.com/vibetrace/api#apps)


2. Synchronize your item catalog with vibetrace
-----------------------------------------------

This process must be continuous and should be performed every time a new item is created, and item's properties are modified or when an item is removed from the app's database.

There are two ways to perform said process:

a. provide a product feed url for vibetrace that will be periodically crawled by our engines. This feed url can be declared upon app registration.

b. use the vibetrace API's `/items` endpoints to upload new items, modify existing ones (eg. change the price of a product), inspect existing items or delete old or out of stock products.

We advice you to integrate these tasks into the business logic of your application for a seamless synchronization.

Checkout the [API description](https://github.com/vibetrace/api/blob/master/README.markdown) to find out how to do that.

_NOTE_ It is important to keep your item catalog synchronized with vibetrace, service quality might suffer if for instance the engine recommends an item that is no longer in your catalog.


3. Publish eCommerce Events to Vibetrace
----------------------------------------

Every time the user performs an action of interest, the app is responsible for publishing the corresponding event to the Vibetrace Service. This process can be done in two ways:


a. by using the Vibetrace Javascript SDK and publishing events dirrectly from the web inteface

This process involves the Vibetrace Javascript SDK.

- Include the javascript SDK in your page by adding the vibetrace integration file to the html of your application. The file's url has the following format:
    `https://app.vibetrace.com/javascripts/loader/APP_ID.js`

- NOTE The `APP_ID` parameter is the id of your application received upon registration.

- For a copy/paste integration experience we provide apps with a tiny integration script that will inject the above file in an unobtrusive, asynchronous way. The integration script will be emailed to you during the app registration flow, but we are listing the script template below for reference. By replacing `APP_ID` with the id of your application received upon registration you will obtain a valid integration script.

    ````html
    <script id='vt-script-loader'>
        (function(){
            var l=document.createElement('script');
            l.type='text/javascript';
            l.async=true;
            l.src='https://app.vibetrace.com/loaders/APP_ID.js';
            var s=document.getElementsById('vt-script-loader');
            s.parentNode.insertBefore(l, s);
        })();
    </script>
    ````

- If you need complete control over the way you load assets in your application, you can use the raw javascript file.

- Publish events whenever you deem necessary by simply adding the parameters in an object to the global `window._vteq` event buffer. Below is an example of how you would register a `viewitem` event. _Note_ that this code can be placed wherever you see fit and it's not dependent on whether the sdk has loaded or not.

    ````html
    <script>
        _vteq = _vteq || [];
        _vteq.push({
            'viewitem': {
                userId: 'unique-user-id',
                itemId: 'unique-item-id',
                sessionId: 'unique-session-id'
            }
        });
    </script>
    ````

- Please review the [Events Section of the Vibetrace API](https://github.com/vibetrace/api#events) to see the types of events we support and the parameters they require.

- the Vibetrace engine will start to gather data and adjusts to the specifics of your application event flow. This may take anywhere from 3 days to a week, depending on the number of items in the catalog and generated events.


b. by directly using the Vibetrace HTTP RESTful API.

This flow is useful when the app wishes to publish events directly from it's backend infrastructure. All communication is performed through HTTPS and authorized with Basic HTTP Authentication. Please refer the the [Vibetrace API Documentation](https://github.com/vibetrace/api) for a detailed description of the api.

Feel free to use any programming language you wish, but note that, currently, Vibetrace does not provide an official implementation of it's rest api in any other language other than browser javascript. However, we would appreciate if you emailed us at [admin@vibetrace.com](mailto:admin@vibetrace.com) to let us know which plaform you are using to integrate with our API and describe your experience.


4. Deliver recommendations
--------------------------

After gathering item information and user events, the Vibetrace engine is ready to deliver recommendations.

The system is flexible enough to display personalized recommendation in real-time anywhere on the application user interface.

For recommendations integration in your website and emails you have more details in the administration panel.
