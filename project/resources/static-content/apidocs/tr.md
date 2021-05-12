# Geliştiriciler için Exbita API

Exbita platformu, mobil uygulamalar, ödeme sistemleri, alım/satım motorları gibi 3. parti yazılımların entegre olabilmesi için basit bir REST API servisi sağlamaktadır.

Bütün API istekleri "application/json" içerik tipini kullanmaktadır. API anahtarı gerektiren isteklerde HTTP istek header'ı içerisinde aşağıdaki şekilde api anahtarı gönderilmelidir.

    Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImViZjJhYmM4NTYyMWVjNTA0YTAxNjEwNzUzN2M3NmEyMTQwZmM3ZDQ3YjRhNTM4MTQ3MWQ5OWEzZWI2Yzg4ZTllMmZiY2I3Y2Q0Yzg4ZmMzIn0.eyJhdWQiOiIxIiwianRpIjoiZWJmMmFiYzg1NjIxZWM1MDRhMDE2MTA3NTM3Yzc2YTIxNDBmYzdkNDdiNGE1MzgxNDcxZDk5YTNlYjZjODhlOWUyZmJjYjdjZDRjODhmYzMiLCJpYXQiOjE1MjI0MDIwMjcsIm5iZiI6MTUyMjQwMjAyNywiZXhwIjoxNTUzOTM4MDI3LCJzdWIiOiIxIiwic2NvcGVzIjpbIm1hbmFnZV9vcmRlcnMiXX0.vlwZQ3xO9fglHqI9jOekUwzufidwhiUBp9iE5-6jEpN7IzKipappdGmZSOUUuQnvznR7DZ5OlkdLh2kvWhQaSV6fqGGtdrOCtZ5gNvVObTZsn3OT1ukRmr3GR-aeIZLkpRM4jCBmKqKPfJWUTSfegT0LD1U6Xk1075f8D2QTMExsd1SuCVbpmFVOnUrGt11kc7c8o6G9GBnT30cNkyLhOQP2ZWXh-n8L-OLf60a3ffXV3YSF5A169evsKaykM7-UPwP8Q8BikYarlvbazfjx4YUlB-PMdzmQckCLLXwfQvOR8v4HZJ1KDiKyxCSeYK1gM6Sylikxu-Wm-y5yffvNo2tplCT_EtnV1It4a6uw3QApDza1maIn_WELUGUA6zV3qT8amfYVviusZ6GaAjXl0khmmJQTHmUaYTtKeixK1BotoowY8cSoc4gMn1NcbetpEzKk40UsDvSBdoUVwtLzmF9R3Ekgh4ZKr8SX1MT8yOmwJNnHcgI31iLYsuYNaxvi_359uZCYoD4WTKqbilGHwIjwYNpXhCxhf8AongFQ5uOPvGnAF3xpNvmyeUWAb1RZ7N9IsG1GO9ngZeaFLLWAr4DXBl57fdjsUpQDWsVHXgpW9RcnjY_SouGR0M5A0HCGVYUtorcLPNlXbHZY6XvHPmzxXEp6GTwylJrto-Prllo

## API Bölümleri

Exbita API, aşağıdaki ana gruplar altında sınıflandırılmıştır.

1. Currency API - Kurlarla ilgili üst verileri sunar.
2. Market API - Aktif pazarlarla ilgili verileri sunar.
3. Order API - Alım-Satım emirlerinin okunması ve yönetimini sağlar. API anahtarı gerektirir. Emir yönetimi için API anahtarında emir yönetim yetkileri bulunması gerekir.
4. Wallet API - Kullanıcıya ait para ve kripto para cüzdanlarındaki bakiyeleri sunar. API anahtarı gerektirir.

## Currency API

Kurlarla ilgili üst verileri sunar.

### Kripta Para Listesi
   
Sistemde mevcut tüm kripto paraların listesini verir.

#### Yol
    
    GET /api/v1/currencies
     
#### Cevap Örneği:
        
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

### Para Listesi 

Sistemde mevcut paraların listesini verir

#### Yol

    GET /api/v1/currencies/fiat
    
#### Cevap Örneği

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

Aktif pazarlarla ilgili verileri sunar.

### Tüm Pazarları listeleme

Sistem bulunan tüm pazarların listesini güncel değerleri ile birlikte verir
    
#### Yol
    GET /api/v1/markets
     
#### Cevap Örneği:
        
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
        
### Tek bir pazarı listeleme

Adı verilen pazarın güncel değerlerini verir.

#### Yol

    GET /api/v1/market/{market_adı}
    
#### Cevap Örneği:

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

  
### Pazar Emir Defteri

Bir pazara ait tüm açık emirleri verir.

#### Parametreler

    - market: [zorunlu] market adı (BTC-USD)
    - type: [zorunlu] {buy|sell|both} 

#### Yol

    GET /api/v1/market/{market}/orderbook/{type}
   
#### Cevap Örneği:
    
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

Alım-Satım emirlerinin okunması ve yönetimini sağlar. API anahtarı gerektirir. Emir yönetimi için API anahtarında emir yönetim yetkileri bulunması gerekir.

### Emirleri listeleme

Kullanıcıya ait istenen kriterdeki tüm emirleri verir

#### Parametreler

    - market: market adı (BTC-USD) [opsiyonel]
    - status {open|filled} [opsiyonel|varsayılan:open]
    
opsiyonel parametreler url içerisinde http sorgu cümlesi olarak iletilebilir.

#### Yol

    GET /api/v1/orders
    
#### İstek Örneği

    GET /api/v1/orders?market=BTC-USD&status=open
    
#### Cevap Örneği

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
    
### Tekil Emir listeleme

Kullanıcıya ait verilen uuid'ye sahip emiri verir.
    
#### Yol

    GET /api/v1/order/{uuid}
    
#### Cevap Örneği

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
    
    
### Piyasa Alış Emri Girme

Güncel piyasa fiyatından belirtilen tutar harcanarak alım yapar. Verilen tutar (quantity), vergi ve komisyonlar dahil harcanacak toplam tutardır. Satın alınma işlemi vergi ve komisyonlar düşüldükten sonra kalan tutar üzerinden yapılır. 

#### Parametreler

    - market [zorunlu] market adı (BTC-USD)
    - quantity [zorunlu] satın alım için harcanacak tutar.

#### Yol

    POST /api/v1/order/buy
    
#### Olumlu Cevap Örneği:
    
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
    
Cevap içerisinde verilen quantity, piyasa fiyatlarına göre gerçeklemiş olan toplam satın alma adedidir. 

#### Olumsuz Cevap Örneği:
    
    {
        "message": "order_validation_failed",
        "errors": {
            "market": [
                "Order is not accepted. There is no market sell order"
            ]
        },
        "success": false
    }
    
#### Olumsuz Cevap Örneği 2:
    
    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Limit Alış Emri Girme 

İstenen birim fiyattan belirtilen adette alım yapmak üzere emir girilir. Alış emrinizle eşleşen bir satış emri olduğunda otomatik olarak emir işlenir. 

#### Parametreler

    - market [zorunlu] market adı (BTC-USD)
    - quantity [zorunlu] satın alınacak tutar
    - rate [zorunlu] satın alım için belirlenen birim fiyat.

#### Yol

    POST /api/v1/order/buylimit
    
#### Cevap Örneği:
    
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
    
#### Olumsuz Cevap Örneği:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Piyasa Satış Emri Girme

Güncel piyasa fiyatından belirtilen adette satış yapar. 

#### Parametreler

    - market [zorunlu] market adı (BTC-USD)
    - quantity [zorunlu] satılacak adet.

#### Yol

    POST /api/v1/order/sell
    
#### Cevap Örneği:
    
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
    
#### Olumsuz Cevap Örneği: 

    {
        "message": "order_validation_failed",
        "errors": {
            "market": [
                "Order is not accepted. There is no market buy order"
            ]
        },
        "success": false
    }

#### Olumsuz Cevap Örneği 2:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
    
### Limit Satış Emri Girme 

İstenen birim fiyattan belirtilen adette satış yapmak üzere emir girilir. Satış emrinizle eşleşen bir alış emri olduğunda otomatik olarak emir işlenir. 

#### Parametreler

    - market [zorunlu] market adı (BTC-USD)
    - quantity [zorunlu] satılacak adet
    - rate [zorunlu] satış için belirlenen birim fiyat.

#### Yol

    POST /api/v1/order/selllimit
    
#### Cevap Örneği:
    
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
    
#### Olumsuz Cevap Örneği:

    {
        "message": "order_validation_failed",
        "errors": {
            "quantity": [
                "Insufficient Balance"
            ]
        },
        "success": false
    }
    
### Emir İptal Etme

UUID değeri verilmiş henüz tamamı işlenmemiş olan limit emirini iptal eder. Emir kısmen işlenmiş olabilir, bu durumda kalan miktar iptal edilir.

#### Parametreler

    - uuid [zorunlu]

#### Yol

    POST /api/v1/order/cancel
    
#### Cevap Örneği:
    
    {
        "success": true,
        "uuid": "5fbe41a0-eb24-11e7-85a6-79876db97cbd"
    }
    
#### Olumsuz Cevap Örneği:

    {
        "message": "order_already_canceled",
        "errors": [],
        "success": false
    }
    
___

## WALLET API

Kullanıcıya ait para ve kripto para cüzdanlarındaki bakiyeleri sunar. API anahtarı gerektirir.

#### Para Cüzdanlarını Listeleme

Kullanıcıya ait TL, USD gibi para cüzdanlarını getirir.

#### Yol

    GET /fiatwallets
    
#### Cevap Örneği

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
    
#### Kripto-Para Cüzdanlarını Listeleme

Kullanıcıya ait kripto para cüzdanlarını getirir.

#### Yol

    GET /wallets
    
#### Cevap Örneği

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
