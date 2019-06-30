# RSVP Form Project

As a Drupal Developer, you've been tasked with the development of a RSVP Form that can collect the user's name
and email for upcoming Events on the client's Drupal website. The RSVP will be displayed as a block on the event's
page, which contains the Event Name, Event Description, Event Location and List of Attendees.


## Architecture:

- Created a Programmatically form to submit users information.
- The event is a Drupal content type.
- I'm using Drupal ajax commands to submit RSVP for without reload the page.
- To create all the structure that is necessary it's used Features module and hook hook_schema.
- The module is using js to calculate distance between the event location and the user location.


## Assumptions

I assumed the user location is from the browser and the user isn't a Drupal user entity.

## Prerequisites:

* Docker
* Docker Compose
* Make

## Build instructions

The following command starts the application.
```
$ make up
```

The following command puts you inside an container with bash.
If you are running on windows, you should type "winpty" as prefix.
```
$ make in
```

The following command download the drupal core, vendor and contrib modules,
run inside of container.

Grab a cup of coffee and wait because this command takes a long time. <img src="https://media.giphy.com/media/ryYnIF0q0vJ8k/giphy.gif" width="200px" height="200px" />

```
$ composer install
```

### Install Drupal ###

Standard profile is a prerequisite to RSVP List module works.

Thats it! Open `rsvp.localhost` in our browser.

