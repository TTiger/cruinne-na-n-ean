# Loop Returns: Universe of Birds

## Installation

Install Docker :)

Add the following entry to your hosts file:

```
127.0.0.1 localhost universeofbirds.test
```

Run the following commands in your terminal:

```shell
git clone https://github.com/eoghanobrien/cruinne-na-n-ean.git
cd ./cruinne-na-n-ean
sail up -d
sail npm install
sail npm run production
```

## Setup

```shell
sail artisan migrate
# open another terminal
sail artisan queue:work --tries=3 --queue=default
# in original terminal
sail artisan shop:sync:products
sail artisan shop:sync:orders
```

## Usage

Open up the site at http://universeofbirds.test

Run the tests via:

```shell
sail test
```

## Considerations

- Pull both the list of the products and, the order history, store that data locally and then aggregate.
- The test store has relatively few products and orders, consider that in production a given store might have thousands or millions of orders and hundreds or thousands of products.
- Use HTTP Basic authentication.
- The Shopify API has both REST and GraphQL options, use whichever you prefer.

## Work Log

- Install the latest version of Laravel (v8.5.8 at time of writing)
- Run docker composer (sail up -d)
- Add Debugbar
- Add Tailwind
- Create a boundary for Shop related code at `/app/Shop`
- Create a location for non-domain support code at `/app/Support`
- Create a feature test to driver out the initial controller logic
- Create a route, controller and view to display products
- Extract an `Application` class to store generic application info, e.g. name, version and locale/lang
- Research the Shopify API
- Research the leaky bucket algo
- Research Shopify API pagination headers
- Create a test to drive out the Shopify REST client request
- Create an interface for the client object
- Create a manager class to provide configuration for the client instance via drivers
- Move the instantiation of the client object to the service provider
- Write a command to call the API request
- Dispatch an event to start the sync process for products
- Parse the pagination link headers from the API resposone
- Extract a class to handle the Shopify response
- Allow the response class to accept the products in the response and the pagination links
- Queue the sync process
- When the pagination links are present, dispatch the same SyncRequest event with different params
- When the pagination links are not present, dispatch a SyncComplete event
- Give an example listener on complete
- Create some models/migrations/factories for Product related data
- Create the eloquent relationship methods, write tests to drive them out
- Extract the mapping of product data to a transformer class
- Register the transformer class in the IoC container so we can inject the transformer class with DI
- Run the product sync command, test on the queue
- Sync process is working, pull the data via the controller
- Create an eloquent query to get the required data
- Seems I should paginate by product variant and not product
- Create the models/migrations/factories for Order related data
- Create a command to sync Order data
- Create a method on the API client to request order data
- Abstract the response and pagination parsing
- Create an event to handle Order sync
- Extract an abstract Event class to share some code between events
- Extract an abstract Sync listener class to share some code between listeners
- Create an order transformer class and wire it up via the service provider
- Run the order sync command
- Profile the query in the debugbar: Queries are taking between 200-300ms, seems high
- Refactor to a regular query with joins instead of relationships... better... 30-50ms
- Style the results
- Publish the blade pagination views
- Style the pagination links
- Extract Tailwind to BEM style classes via @apply, I believe that's what's in use at Loop
- Pull down the product/variant images via sync and add them to the query
- Attempt to find the variant image first, then fall back to product
- Assume USD as currency, not something I would under normal circumstances
- Extract the query to a 'report', feels like in a real app it would be a 'Most Purchased Products' report

_**More than 6 hours have elapsed ... feels like time is up...**_

## Review

- I didn't get time to cover all the code with tests
- I didn't write any Vue components for the front-end table
- I would have liked to use a ResourceCollection for the database query results and provide pagination info to a VueJS frontend via an `api` route
- I should really implement a check for the 'X-Shopify-Shop-Api-Call-Limit' on the Sync listener, one idea would be to release the job back onto the queue with a 20-30 second delay if we've reached > 35/40
- There is probably a few more fields that I should be using from the API response, currency would be a good one, but also determining status from the given data is a little confusing
