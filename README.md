Héichwaasser API
======

This uses the [Measured data levels](https://data.public.lu/en/datasets/measured-water-levels/) and creates a history and a JSON Api.


Documentation
------------

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
        "id": 28,
        "name": "Alzette",
        "stations": [
            {
                "id": 81,
                "city": "Ettelbr\u00fcck",
                "supplement": "...",
                "latitude": ...,
                "longitude": ...
            }
        ]
    }
]
```
* [<code>GET</code> /rivers/:id](https://heichwaasser.lu/api/v1/rivers/{id})

```bash 
[
    {
        "id": 28,
        "name": "Alzette",
        "stations": [
            {
                "id": 81,
                "city": "Ettelbr\u00fcck",
                "supplement": "...",
                "latitude": ...,
                "longitude": ...
            }
        ]
    }
]
```
#### Stations
* [<code>GET</code> /stations](https://heichwaasser.lu/api/v1/stations)
```bash 
[
    {
        "id": 86,
        "river": {
            "id": 21,
            "name": "S\u00fbre"
        },
        "city": "Bigonville",
        "supplement": "...",
        "latitude": ...,
        "longitude": ...,
        "alert_levels": [
            {
                "name": "Level1",
                "value": 100,
                "unit": "cm"
            }
        ]
    },...
]
```
* [<code>GET</code> /stations/:id](https://heichwaasser.lu/api/v1/stations/{id})
```bash 
{
    "id": 86,
    "river": {
        "id": 21,
        "name": "S\u00fbre"
    },
    "city": "Bigonville",
    "supplement": "...",
    "latitude": ...,
    "longitude": ...,
    "measurements": [
        {
            "timestamp": "2017-12-21T18:00:00+0000",
            "value": 124.4,
            "unit": "cm"
        },..
    ],
    "alert_levels": [
            {
                "name": "Level1",
                "value": 100,
                "unit": "cm"
            }
        ]
} 
```

 
 
License
------------

 Check out the [LICENSE](LICENSE) file.