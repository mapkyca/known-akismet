Akismet Spam filter support for Known
=====================================

This plugin provides a native (library free) implementation of Akismet for Known, communicating
with the akismet API using Known Webservices.

It provides a basic implementation of Akismet which scans all annotation content types, including webmentions.

This is largely intended as a stop-gap until something better comes along (e.g. social graph reputation filtering)

Depends
-------

* This will only work with versions of known that have annotation filtering support (see <https://github.com/idno/Known/pull/1281>)

Installation
------------

* Drop the Akismet folder into the IndoPlugins folder of your idno installation.
* Log into known and click on Administration.
* Click "install" on the plugins page

Todo
----

* [ ] Spam management pages (so you can unspam false positives etc)
* [ ] Spam/Ham submission

See
---
 * Author: Marcus Povey <http://www.marcus-povey.co.uk> 

