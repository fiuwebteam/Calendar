<?php
header("Content-Type:text/Calendar");
header("Content-Disposition:inline;filename=calendar.ics");
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Florida International University//Events Calendar//EN
X-WR-CALNAME:Florida International University
METHOD:PUBLISH
BEGIN:VTIMEZONE
TZID:US/Eastern
BEGIN:DAYLIGHT
TZOFFSETFROM:-0500
TZOFFSETTO:-0400
DTSTART:20070311T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
TZNAME:EDT
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:-0400
TZOFFSETTO:-0500
DTSTART:20071104T020000
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
TZNAME:EST
END:STANDARD
END:VTIMEZONE
<?php foreach($events as $event) { ?>
BEGIN:VEVENT
DTSTART;TZID=US/Eastern:<?= date("Ymd\THis", strtotime($event["CalendarsEvent"]["start_date_time"])) ?>

DTEND;TZID=US/Eastern:<?= date("Ymd\THis", strtotime($event["CalendarsEvent"]["end_date_time"])) ?>

DESCRIPTION:<?= str_replace(chr(13).chr(10),"  ", $event['Event']['description']); ?>

LOCATION:<?= str_replace(chr(13).chr(10),"  ", $event['Event']['location']); ?>

SUMMARY:<?= $event['Event']['title'] ?>

UID:<?= $event['Event']['id'] ?>

CONTACT:<?= str_replace(chr(13).chr(10),"  ", $event['Event']['contact']); ?>

SEQUENCE:0
DTSTAMP:<?=date('Ymd\THis') ?>

END:VEVENT
<?php } ?>
END:VCALENDAR