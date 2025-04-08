<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PayPal JS SDK Standard Integration</title>
    </head>
    <body>
        <div id="paypal-button-container"></div>
        <p id="result-message"></p>

       
        <!-- Initialize the JS-SDK -->
        <script
            src="https://www.paypal.com/sdk/js?client-id=ARE5wNyXQql5Zx3bEOCH1-5tIbFvq7H7luRkYzcLtzK1W1NUCsAoyOlh1lunumlWhMzGnp5hVZKlyLaE&buyer-country=FR&currency=EUR&components=buttons&enable-funding=venmo,card&disable-funding=paylater"
            data-sdk-integration-source="developer-studio"
        ></script>
        <script src="js/paypalfuncionboton.js"></script>
       
    </body>
</html>