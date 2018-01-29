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
                "trend": "rest",
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
                "trend": "rest",
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
        "trend": "rest",
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
    "trend": "rest",
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

* [<code>GET</code> /stations/:id/start/:timestamp/end/:timestamp](https://heichwaasser.lu/api/v1/stations/81/start/1513728000/end/1513764000)

*The returned measurements are strictly greater than the start timestamp and strictly smaller than the end timestamp.*

`:timestamp` must be provided as UNIX timestamp in seconds since Jan 01 1970.

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
    "trend": "rest",
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
            "timestamp": "2017-12-20T00:15:00",
            "value": 133.6,
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

##### Explanation of "trend" field
On import the new water level is compared to the previous one, and the trend field is updated.
* `down` new level < previous level
* `rest` new level = previous level
* `up`   new level > previous level

### Admin Interface
The Admin Interface located at `<installed directory>/admin` allows you to update the river and station names.
You can also add and modify alert levels. 
Don't change the `Shortname` Field on River and Station classes, as this field is used on the import command to correctly map the new measurements.


Development
------------
* Install [Composer](https://getcomposer.org/).
* Run `composer install` in the project's root.
* Make sure you have a MariaDB/MySQL server running and run `php bin/console doctrine:schema:update --force` to initiate the database

If you want to access the `/admin` page, you have to set up a user:
* Run `bin/console fos:user:create` and enter a username, email and password.
* Run `bin/console fos:user:promote [created username] ROLE_ADMIN` to promote your user and enable access to `\admin` page.

License
------------
Check out the [LICENSE](LICENSE) file.
