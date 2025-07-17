# Patrick Victor Cruz - MaxComply Backend Code Challenge

## Database Design

Reading through the brief about the database 
```
Start by designing a SQL database structure which can store technical data about vehicles 
(i.e. top speed, dimensions, engine data, type ) and their make.
```
I decided that `Manufacturers` and `Vehicle Types` would be separate from `Vehicles`. The `Vehicles` table
would only store data about it's name and the the foreign keys for its manufacturer and type of vehicle it is.
From there I decided that the specifications of the vehicles would be it's own table `Vehicle Specs` that would
hold all the information about the given Vehicle.

![Database Diagram.png](Database%20Diagram.png)

## Implementation
I have implemented the 3 endpoints outlined in the brief along with the other expected behaviours. 
* Endpoint 1 `/manufacturersByType/{vehicleType}` returns all the manufacturers that produce the specific vehicle type.
* Endpoint 2 `/vehicleSpecs/{vehicle}` returns all the information about a specific vehicle in the `vehicles` tables and 
`vehicle_specs` table.
* Endpoint 3 `/vehicleSpecs/{vehicle}` I implemented this as a `PATCH` method with checks for the 
various fields within the `vehicle_specs` table.
* I implemented a token authentication system to authorize endpoint requests. I regret it's only a basic
implementation using `make:security`

## Other Endpoints
```
Comment what other endpoints you consider critical for a standard REST API implementation.
```
From reading through the API contract other endpoints I would consider.
* GET all manufacturers
* GET all vehicles from a specific manufacturers
* POST to create a vehicle and it's corresponding specifications
* GET certain vehicles with a specific specification, in my case an endpoint that returns all the 
vehicles that have a `Petrol` as their `engine_type` or `RWD` as their `drive_type` 

## Notes
I've provided csv files of the data I was using in the `fixtures` folder.


