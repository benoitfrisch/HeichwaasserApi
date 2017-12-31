Héichwaasser API
======

This uses the [Measured water levels](https://data.public.lu/en/datasets/measured-water-levels/) and creates a history and a JSON Api.

This API is also published on [data.public.lu](https://data.public.lu/en/reuses/heichwaasser-api/).

Documentation
------------

Important notice: The time is given in Western European Time all year, the European Summer Time is not considered. You may have to adapt the time to your needs.

### Root

```bash
https://heichwaasser.lu/api/v1
```

### Endpoints

#### Rivers
* [<code>GET</code> /rivers](https://heichwaasser.lu/api/v1/rivers)

```bash
[
    {
        "id": 17,
        "name": "Alzette",
        "stations": [
            {
                "id": 61,
                "city": "Ettelbr\u00fcck",
                "latitude": 49.84373,
                "longitude": 6.09713,
                "current": {
                    "timestamp": "2017-12-27T16:45:00",
                    "value": 69.8,
                    "unit": "cm"
                },
                "minimum": {
                    "timestamp": "2017-12-27T15:30:00",
                    "value": 65.5,
                    "unit": "cm"
                },
                "maximum": {
                    "timestamp": "2017-12-27T16:45:00",
                    "value": 69.8,
                    "unit": "cm"
                },
                "alert_levels": [
                    {
                        "name": "Level 1",
                        "value": 180,
                        "unit": "cm"
                    },
                    {
                        "name": "Level 2",
                        "value": 230,
                        "unit": "cm"
                    }
                ]
            },...
        ]
    }
]
```
* [<code>GET</code> /rivers/:id](https://heichwaasser.lu/api/v1/rivers/17)

```bash
[
    {
        "id": 17,
        "name": "Alzette",
        "stations": [
            {
                "id": 61,
                "city": "Ettelbr\u00fcck",
                "latitude": 49.84373,
                "longitude": 6.09713,
                "current": {
                    "timestamp": "2017-12-27T16:45:00",
                    "value": 69.8,
                    "unit": "cm"
                },
                "minimum": {
                    "timestamp": "2017-12-27T15:30:00",
                    "value": 65.5,
                    "unit": "cm"
                },
                "maximum": {
                    "timestamp": "2017-12-27T16:45:00",
                    "value": 69.8,
                    "unit": "cm"
                },
                "alert_levels": [
                    {
                        "name": "Level 1",
                        "value": 180,
                        "unit": "cm"
                    },
                    {
                        "name": "Level 2",
                        "value": 230,
                        "unit": "cm"
                    }
                ]
            },...
        ]
    }
]
```
#### Stations
* [<code>GET</code> /stations](https://heichwaasser.lu/api/v1/stations)
```bash
[
    {
        "id": 81,
        "river": {
            "id": 3,
            "name": "S\u00fbre"
        },
        "city": "Bigonville",
        "latitude": 49.86875,
        "longitude": 5.79996,
        "current": {
            "timestamp": "2017-12-27T16:45:00",
            "value": 108.2,
            "unit": "cm"
        },
        "minimum": {
            "timestamp": "2017-12-27T16:45:00",
            "value": 108.2,
            "unit": "cm"
        },
        "maximum": {
            "timestamp": "2017-12-27T16:45:00",
            "value": 108.2,
            "unit": "cm"
        },
        "alert_levels": [
            {
                "name": "Level 1",
                "value": 250,
                "unit": "cm"
            },
            {
                "name": "Level 2",
                "value": 300,
                "unit": "cm"
            }
        ]
    },...
]
```
* [<code>GET</code> /stations/:id](https://heichwaasser.lu/api/v1/stations/81)
```bash
{
    "id": 81,
    "river": {
        "id": 3,
        "name": "S\u00fbre"
    },
    "city": "Bigonville",
    "latitude": 49.86875,
    "longitude": 5.79996,
    "current": {
        "timestamp": "2017-12-27T16:45:00",
        "value": 108.2,
        "unit": "cm"
    },
    "minimum": {
        "timestamp": "2017-12-27T16:45:00",
        "value": 108.2,
        "unit": "cm"
    },
    "maximum": {
        "timestamp": "2017-12-27T16:45:00",
        "value": 108.2,
        "unit": "cm"
    },
    "measurements": [
        {
            "timestamp": "2017-12-27T16:45:00",
            "value": 108.2,
            "unit": "cm"
        },..
    ],
    "alert_levels": [
        {
            "name": "Level 1",
            "value": 250,
            "unit": "cm"
        },
        {
            "name": "Level 2",
            "value": 300,
            "unit": "cm"
        }
    ]
}
```


License
------------

 Check out the [LICENSE](LICENSE) file.
