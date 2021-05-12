# Exbita API for Developers

Exbita provides a REST API for developers to integrate their 3rd party mobil apps, payment systems, trading engines etc.

All API requests use the content type "application/json". Requests requiring user authentication should send their API key inside the HTTP request header as follows.

    Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImViZjJhYmM4NTYyMWVjNTA0YTAxNjEwNzUzN2M3NmEyMTQwZmM3ZDQ3YjRhNTM4MTQ3MWQ5OWEzZWI2Yzg4ZTllMmZiY2I3Y2Q0Yzg4ZmMzIn0.eyJhdWQiOiIxIiwianRpIjoiZWJmMmFiYzg1NjIxZWM1MDRhMDE2MTA3NTM3Yzc2YTIxNDBmYzdkNDdiNGE1MzgxNDcxZDk5YTNlYjZjODhlOWUyZmJjYjdjZDRjODhmYzMiLCJpYXQiOjE1MjI0MDIwMjcsIm5iZiI6MTUyMjQwMjAyNywiZXhwIjoxNTUzOTM4MDI3LCJzdWIiOiIxIiwic2NvcGVzIjpbIm1hbmFnZV9vcmRlcnMiXX0.vlwZQ3xO9fglHqI9jOekUwzufidwhiUBp9iE5-6jEpN7IzKipappdGmZSOUUuQnvznR7DZ5OlkdLh2kvWhQaSV6fqGGtdrOCtZ5gNvVObTZsn3OT1ukRmr3GR-aeIZLkpRM4jCBmKqKPfJWUTSfegT0LD1U6Xk1075f8D2QTMExsd1SuCVbpmFVOnUrGt11kc7c8o6G9GBnT30cNkyLhOQP2ZWXh-n8L-OLf60a3ffXV3YSF5A169evsKaykM7-UPwP8Q8BikYarlvbazfjx4YUlB-PMdzmQckCLLXwfQvOR8v4HZJ1KDiKyxCSeYK1gM6Sylikxu-Wm-y5yffvNo2tplCT_EtnV1It4a6uw3QApDza1maIn_WELUGUA6zV3qT8amfYVviusZ6GaAjXl0khmmJQTHmUaYTtKeixK1BotoowY8cSoc4gMn1NcbetpEzKk40UsDvSBdoUVwtLzmF9R3Ekgh4ZKr8SX1MT8yOmwJNnHcgI31iLYsuYNaxvi_359uZCYoD4WTKqbilGHwIjwYNpXhCxhf8AongFQ5uOPvGnAF3xpNvmyeUWAb1RZ7N9IsG1GO9ngZeaFLLWAr4DXBl57fdjsUpQDWsVHXgpW9RcnjY_SouGR0M5A0HCGVYUtorcLPNlXbHZY6XvHPmzxXEp6GTwylJrto-Prllo

## API Sections

Exbita API is divided into the following sections.

1. Currency API - Provides meta info about the currencies.
2. Market API - Provides meta info about the active markets.
3. Order API - Allows to read and manage the sell/buy orders of a user. Requires API key and the API key needs to have `manage orders permission to be able to manage the orders. 
4. Wallet API - Used to get the balances of fiat and crypto currency wallets owned by the user.

## Currency API

Provides meta info about the currencies.

### Crypto Currency List
   
Lists all crypto currencies in the platform. 

#### Path

    
    GET /api/v1/currencies
     
#### Response Example:
        
    {
        "data": [
            {
                "id": 1,
                "currency": "BTC",
                "currency_long": "Bitcoin",
                "min_deposit": 0.001,
                "fee_withdraw": 0.0001,
                "min_withdraw": 0.001
            }
        ],
        "success": true
    }     

### Fiat Currency List 

Lists all fiat currencies in the platform.

#### Path

    GET /api/v1/currencies/fiat
    
#### Response Example

    {
        "data": [
            {
                "id": 1,
                "currency": "USD",
                "name": "US Dollar",
                "withdraw_fee": 0,
                "withdraw_min": 0,
                "withdraw_max_daily": 0,
                "withdraw_max__monthly": 0,
                "deposit_min": 0
            }
        ],
        "success": true
    }
___

## Market API

Provides meta info about the active markets.

### List All Markets

Returns the statistics and latest prices of all active trade markets in the platform.
    
#### Path
    GET /api/v1/markets
     
#### Response Example:
        
        {
            "data": [
                 {
                     "id": 1,
                     "name": "BTC-USD",
                     "bid": 0,
                     "ask": 29967.81,
                     "last": 29993.82,
                     "change_24h": 854.12,
                     "change_24h_percent": 2.56,
                     "high_24h": 29987.29,
                     "low_24h": 29987.29,
                     "volume_24h": 422.16771977,
                     "currency_id": 1,
                     "currency_type": 1,
                     "currency": "BTC",
                     "currency_decimals": 8,
                     "currency_format_decimals": 4,
                     "currency_name": "Bitcoin",
                     "quote_currency_id": 1,
                     "quote_currency_type": 2,
                     "quote_currency": "USD",
                     "quote_currency_decimals": 2,
                     "quote_currency_format_decimals": 2,
                     "quote_currency_name": "US Dollar"
                 }
            ],
            "success": true
        }
        
### List A Market

Returns the statistics and latest prices of a given trade market.

#### Path

    GET /api/v1/market/{market_name}
    
#### Response Example:

    {
        "data": {
             "id": 1,
             "name": "BTC-USD",
             "bid": 0,
             "ask": 29967.81,
             "last": 29993.82,
             "change_24h": 854.12,
             "change_24h_percent": 2.56,
             "high_24h": 29987.29,
             "low_24h": 29987.29,
             "volume_24h": 422.16771977,
             "currency_id": 1,
             "currency_type": 1,
             "currency": "BTC",
             "currency_decimals": 8,
             "currency_format_decimals": 4,
             "currency_name": "Bitcoin",
             "quote_currency_id": 1,
             "quote_currency_type": 2,
             "quote_currency": "USD",
             "quote_currency_decimals": 2,
             "quote_currency_format_decimals": 2,
             "quote_currency_name": "US Dollar"
         },
        "success": true
    }

  
### Market Orderbook

Returns the given type of open orders in the given market. 

#### Parameters

    - market: [required] market name (BTC-USD)
    - type: [required] {buy|sell|both} 

#### Path

    GET /api/v1/market/{market}/orderbook/{type}
   
#### Response Example:
    
    {
        "success": true,
        "data": {
            "buy": [
                {
                    "type": "BUY_LIMIT",
                    "rate": 29969.12,
                    "quantity": 0.09743362,
                    "uuid": "a32156a0-33e9-11e8-85b9-1dd3d56bca30"
                },
                {
                    "type": "BUY_LIMIT",
                    "rate": 29969.29,
                    "quantity": 0.09001519,
                    "uuid": "adbfb9b0-33e9-11e8-89a0-e3d6ca7a5e50"
                },
                {
                    "type": "BUY_LIMIT",
                    "rate": 29969.54,
                    "quantity": 0.08962786,
                    "uuid": "ae8f0860-33e9-11e8-92a4-95b55ca67088"
                }
            ],
            "sell": [
                {
                    "type": "SELL_LIMIT",
                    "rate": 29982.42,
                    "quantity": 0.06953358,
                    "uuid": "9e96c090-33e9-11e8-9ac3-ef4a685b87c1"
                },
                {
                    "type": "SELL_LIMIT",
                    "rate": 29986.51,
                    "quantity": 0.378,
                    "uuid": "9ef8cf40-33e9-11e8-9c07-f126627cb8e6"
                },
                {
                    "type": "SELL_LIMIT",
                    "rate": 29984.93,
                    "quantity": 0.376,
                    "uuid": "9f6f5240-33e9-11e8-b29f-fdfaf1b79b12"
                },
                {
                    "type": "SELL_LIMIT",
                    "rate": 29990.24,
                    "quantity": 0.231,
                    "uuid": "9f9fba60-33e9-11e8-b395-7f47b7de13f9"
                }
            ]
        }
    }
   
___
 
## ORDER API

Allows to read and manage the sell/buy orders of a user. Requires API key and the API key needs to have `manage orders permission to be able to manage the orders.

### List Orders

Returns the orders of the user in the given market having the given status

#### Parameters 

    - market: market name (BTC-USD) [optional]
    - status {open|filled} [optional|default:open]
    
optional parameters can be sent as a http query string
    
#### Path

    GET /api/v1/orders
    
#### Request Example

    GET /api/v1/orders?market=BTC-USD&status=open
    
#### Response Example

    {
        "data": [
            {
                "uuid": "aa4c59a0-33e9-11e8-aebf-290b6fa6e5c7",
                "market": "BTC-USD",
                "market_id": 1,
                "type": "SELL_LIMIT",
                "quantity": 0.33184951,
                "quantity_remaining": 0.33184951,
                "rate": 29985.69,
                "created_at": "2018-03-30T07:12:11+00:00",
                "updated_at": "2018-03-30T07:12:11+00:00",
                "deleted_at": null
            }
        ],
        "success": true
    }
    
### List An Order

Returns the order of the user with given uuid
    
#### Path

    GET /api/v1/order/{uuid}
    
#### Response Example

    {
        "data": {
                "uuid": "aa4c59a0-33e9-11e8-aebf-290b6fa6e5c7",
                "market": "BTC-USD",
                "market_id": 1,
                "type": "SELL_LIMIT",
                "quantity": 0.33184951,
                "quantity_remaining": 0.33184951,
                "rate": 29985.69,
                "created_at": "2018-03-30T07:12:11+00:00",
                "updated_at": "2018-03-30T07:12:11+00:00",
                "deleted_at": null
            },
        "success": true
    }
    
    
### Market Buy Order (Quick)

Places a order which spends the given amount (quantity) to buy in the given market. Given amount includes the tax and commissions, the actual buy amount is calculated after excluding the costs. 

#### Parameters

    - market [required] market name (BTC-USD)
    - quantity [required] amount to be spend to buy

#### Path

    POST /api/v1/order/buy
    
#### Response Example:
    
    {
        "success": true,
        "uuid": "e87a7a90-3645-11e8-82f5-4f4389c4cb5b",
        "order": {
            "order_uuid": "e87a7a90-3645-11e8-82f5-4f4389c4cb5b",
            "market_id": 1,
            "market": "BTC-USD",
            "type": "BUY",
            "quantity": 0.00332608,
            "rate": 0,
            "quantity_remaining": 0
        }
    }
    
Quantity value in the response is the quantity of crypto-money bought from the lowest market rate.

#### Failed Response Example:
    
    {
        "message": "order_validation_failed",
        "errors": {
            "market": [
                "Order is not accepted. There is no market sell order"
            ]
        },
        "success": false
    }
    
#### Failed Response Example 2:
    
    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Limit Buy Order 

Places a limit buy order with the given rate and quantity. When a sell order matching this buy order exists, it will bi processed automatically. 

#### Parameters

    - market [required] market name (BTC-USD)
    - quantity [required] quantity to buy
    - rate [required] desired rate to buy from

#### Path

    POST /api/v1/order/buylimit
    
#### Response Example:
    
    {
        "success": true,
        "uuid": "bb75b250-3657-11e8-9c7b-7f2161426d61",
        "order": {
            "order_uuid": "bb75b250-3657-11e8-9c7b-7f2161426d61",
            "market_id": 1,
            "market": "BTC-USD",
            "type": "BUY_LIMIT",
            "quantity": 0.1,
            "rate": 30000.5,
            "quantity_remaining": 0.1
        }
    }
    
#### Failed Response Example:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Market Sell Order (Quick)

Sell the given amount (quantity) of coins from the highes market price.

#### Parameters

    - market [required] market name (BTC-USD)
    - quantity [required] quantity to sell.

#### Path

    POST /api/v1/order/sell
    
#### Response Example:
    
    {
        "success": true,
        "uuid": "e90cc610-365c-11e8-89a5-2fec4abda064",
        "order": {
            "order_uuid": "e90cc610-365c-11e8-89a5-2fec4abda064",
            "market_id": 1,
            "market": "BTC-USD",
            "type": "SELL",
            "quantity": 0.1,
            "rate": 0,
            "quantity_remaining": 0
        }
    }
    
#### Failed Response Example: 

    {
        "message": "order_validation_failed",
        "errors": {
            "market": [
                "Order is not accepted. There is no market buy order"
            ]
        },
        "success": false
    }

#### Failed Response Example 2:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
    
### Limit Sell Order 

Places a limit sell order with the given rate and quantity. When a buy order matching this sell order exists, it will bi processed automatically. 

#### Parameters

    - market [required] market name (BTC-USD)
    - quantity [required] quantity to sell
    - rate [required] desired rate to sell from

#### Path

    POST /api/v1/order/selllimit
    
#### Response Example:
    
    {
        "success": true,
        "uuid": "c6c7e830-365e-11e8-91bb-a9a021060cdb",
        "order": {
            "order_uuid": "c6c7e830-365e-11e8-91bb-a9a021060cdb",
            "market_id": 1,
            "market": "BTC-USD",
            "type": "SELL_LIMIT",
            "quantity": 0.1,
            "rate": 30000.7,
            "quantity_remaining": 0.1
        }
    }
    
#### Failed Response Example:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Cancel Order

Cancels the non-filled order with given uuid. If the order is filled partially, remaining amount is canceled.

#### Parameters

    - uuid [required]

#### Path

    POST /api/v1/order/cancel
    
#### Response Example:
    
    {
        "success": true,
        "uuid": "5fbe41a0-eb24-11e7-85a6-79876db97cbd"
    }
    
#### Failed Response Example:

    {
        "message": "order_already_canceled",
        "errors": [],
        "success": false
    }
    
___

## WALLET API

Used to get the balances of fiat and crypto currency wallets owned by the user.

#### List Fiat Wallets

Returns the wallet balances for the fiat currencies

#### Path

    GET /fiatwallets
    
#### Response Example

    {
        "data": [
            {
                "id": 1,
                "fiat_currency_id": 1,
                "currency": "USD",
                "balance": 99979.97,
                "available": 99979.97,
                "pending": 0,
                "withdraw_pending": 0
            }
        ]
    }
    
#### List Crypto-Wallets

Returns the wallet balances for the crypto-currencies 

#### Path

    GET /wallets
    
#### Response Example

    {
        "data": [
            {
                "id": 1,
                "currency_id": 1,
                "currency": "BTC",
                "balance": 10,
                "available": 10,
                "pending": 0,
                "withdraw_pending": 0,
                "crypto_address": "mtxzgpUJM7FbKGb6P7eL,qCgVD6THe3Rnk6"
            }
        ],
        "success": true
    }
