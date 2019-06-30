
/**
 * @file
 * Javascript functionality for the calculate location between user and event.
 */
(function ($, window, Drupal) {
  'use strict';

  /**
   * Attach rsvlist functionality.
   *
   */
  Drupal.behaviors.rsvplist = {
    attach: function (context, settings) {
      // If the browser supports W3C Geolocation API.
      if (navigator.geolocation) {

        // Get the geolocation from the browser.
        navigator.geolocation.getCurrentPosition(

          // Success handler for getCurrentPosition()
          function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // Get LatLong values from Event Location field elements.
            var eventLatitude = $('[property="latitude"]')[0].content;
            var eventLongitude = $('[property="longitude"]')[0].content;

            var distanceBetweenUserEvent = distance(lat, lng, eventLatitude, eventLongitude);

            // If the user is within a 20 miles radius from the event location
            // show the RSVP form.
            if (distanceBetweenUserEvent < 20) {
              $(".rsvplist-email-form").show();
            }
          },

          // Error handler for getCurrentPosition()
          function (error) {
            // Alert with error message.
            switch (error.code) {
              case error.PERMISSION_DENIED:
                alert(Drupal.t('No location data found. Reason: PERMISSION_DENIED.'));
                break;
              case error.POSITION_UNAVAILABLE:
                alert(Drupal.t('No location data found. Reason: POSITION_UNAVAILABLE.'));
                break;
              case error.TIMEOUT:
                alert(Drupal.t('No location data found. Reason: TIMEOUT.'));
                break;
              default:
                alert(Drupal.t('No location data found. Reason: Unknown error.'));
                break;
            }
          },

          // Options for getCurrentPosition()
          {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 6000
          }
        );

      }
      else {
        alert(Drupal.t('No location data found. Your browser does not support the W3C Geolocation API.'));
      }
    }
  };

  /**
  * Calculate the distance between two points (given the latitude/longitude of
  * those points) using the haversine formula.
  *
  * @param int latitude1
  * @param int longitude1
  * @param int latitude2
  * @param int longitude2
  *
  * @return int distance
  */
  function distance(lat1, lon1, lat2, lon2) {
    var radius = 3956; // Radius of the earth in miles, for kilometers use 6367.
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var distance = radius * c;
    return distance;
  }

})(jQuery, window, Drupal);
