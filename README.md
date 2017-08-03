[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Yandex/functions?utm_source=RapidAPIGitHub_YandexFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# YandexPlaces Package
Yandex is a Russian multinational technology company specializing in Internet-related services and products. Yandex operates the largest search engine in Russia with about 65% market share in that country.The purpose of the Places API is to search for geographical features (toponyms) and businesses.
                                                                                                                                                                                                             The search can be performed in two directions. Forward search means using a search query to get the coordinates of places, and reverse search means using coordinates to find places.
* Domain: [yandex.com](https://yandex.com)
* Credentials: apiKey

## How to get credentials:
1. Navigate to [Developers Console](https://developer.tech.yandex.com/keys).
2. Create API app.

## Custom datatypes:
  |Datatype|Description|Example
  |--------|-----------|----------
  |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
  |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
  |List|Simple array|```["123", "sample"]```
  |Select|String with predefined values|```sample```
  |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```



## YandexPlaces.searchByOrganization
Search service by organizations is designed to search organizations. The service allows you to search for houses, streets, attractions, cafes and other facilities.

| Field  | Type       | Description
|--------|------------|----------
| apiKey | Credentials| apikey for Yandex Place.
| text   | String     | The text of the search query. For example, the name of the geographical object, address, coordinates, organization name, telephone. Examples (without URL encoding): text=swan lake; text=55.750788,37.618534; text=Saint Petersburg, 15 Blohina ulitsa; text=+7 495 739-70-70 ;text=Yandex, Ltd.
| lang   | String     | Preferred response language.
| mapCenter     | Map        | Search area center. It is determined with the help of longitude and latitude, separated by a comma.Used in conjunction with the parameter mapExtent .
| viewportRange    | String     | Dimensions of the search area. It is determined by means of lengths in longitude and latitude, separated by a comma.Use with mapCenter param.Example - 0.552069,0.400552 . 
| alternativeSearch   | String     | An alternative way to specify a search scope (see mapCenter + mapExtent).The borders of the search area are defined as the geographical coordinates of the lower-left and upper-right corners of the area (in the order "longitude, latitutude").Example - 36.83,55.67~38.24,55.91.
| hardLimitation   | Number     | A sign of a 'hard' limitation of the search area,1 or 0.Use with alterCord or mapExtent + mapCenter.
| resultsLimit| Number     | Number of objects returned. The default is 10. The maximum allowable value is 500.
| skip   | Number     | The number of objects in the response (starting with the first) that you want to skip.
