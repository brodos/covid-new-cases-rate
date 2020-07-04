## COVID-19 new cases rate

This project tracks and calculates the rate of the new COVID-19 cases, by looking at the new cases in the last 14 days, reported to 100k people.

Example: https://covid-rate.stagr.dev/?country=GR



### API endpoints

This app also exposes 2 api endpoints to fetch the countries and new cases rate per country.



##### Countries

```
GET /api/countries
```
This endpoint returns all the available countries.



##### New cases rate by country

```
GET /api/new-cases-rate/{ISO2}
```

This endpoint returns the details for the specified country.





### COVID-19 data source

```
https://api.covid19api.com/
```



### Countries population data source

```
https://restcountries.eu
```
