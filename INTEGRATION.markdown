Vibetrace API Integration Guide
===============================


This documents describes the process of integrating the Vibetrace Recommendation API with client app.

The target audience for this document are the web developers charged with integrating Vibetrace API into the client application.

Prior knowledge of software development, [HTTP protocol](http://en.wikipedia.org/wiki/Representational_state_transfer), [REST](http://ro.wikipedia.org/wiki/HTTP) web services and the [javascript](http://en.wikipedia.org/wiki/JavaScript) programming language is assumed.

To ensure recommendations of the highest quality, a tight communication with the vibetrace dev team is required.

Please let us know how the integration is progressing, what problems have you encountered and what additional features you need.

For that and any other equiry or suggestion please email use at [admin@vibetrace.com](mailto:admin@vibetrace.com)

There are three steps to integrate vibetrace api. Please refere to the [Vibetrace API document](github.com/vibetrace/api/README.markdown) for a detailed description of how to use the API.



1. Synchronize your item catalog with vibetrace
-----------------------------------------------

This process must be continuous and should be performed every time a new item is created, it's properties are modified or when it is removed from the database.

We advice you to integrate these tasks into the business logic of your application for a seamless synchronization.

Use the vibetrace API's `/items` endpoint to upload, modify, inspect or delete items.
Checkout the [API description](github.com/vibetrace/api/README.markdown) to find out how to do that.

_NOTE_ It is important to keep your item catalog synchronized with vibetrace, recommendation quality might suffer if for instance the engine recommends an item that is no longer in your catalog.


2. Gather User Events
---------------------

This process involves the Vibetrace Javascript SDK.

- Step One: add the javascript SDK by copy/pasting a line of js into the html of your application.
- Step Two: configure the SDK using the `APP_ID`, `API_KEY` and `API_SECRET` received upon registration.
- Step Three: use the SDK to define and send relevant user events to the Vibetrace engine. We currently support two types of events:
    - `pageview`: the user of the application opens an item page.
    - `search`: the user searches the site for specific items.
- Step Four: the Vibetrace engine adjusts to the specifics of your application data and event flow. This may take anywhere from 3 day to a week, depending on the number of items in the catalog and generated events.


3. Deliver recommendations
--------------------------

After the gathering of item information and user events, the vibetrace engine will start to deliver recommendations.

The system is flexible enough to display personalized recommendation in real-time anywhere on the application user interface.
