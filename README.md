# RSVP Form Project

As a Drupal Developer, you've been tasked with the development of a RSVP Form that can collect the user's name
and email for upcoming Events on the client's Drupal website. The RSVP will be displayed as a block on the event's
page, which contains the Event Name, Event Description, Event Location and List of Attendees.


## Prerequisites:

* Docker
* Docker Compose
* Make

## Running Locally with Docker


The following command starts the application.
```
$ make up
```

The following command puts you inside an container with bash.
If you are running on windows, you should type "winpty" as prefix.
```
$ make in
```

The following command download the drupal core and contrib modules, run inside of container:
```
$ composer install
```

### Importing Database ###
  Run using drush inside of container:
```
drush sqlc < db/{database_name}.sql
```

Thats it! Open `rsvp.localhost` in our browser.

