=== Login History ===
Contributors: opalplugins
Tags: login history, login log, security
Requires at least: 3.3
Tested up to: 5.9
Stable tag: 2.1.2
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Login history provides full visibility to all login attempts on your site. Fully searchable and easy to use.

== Description ==

Login history provides full visibility to all login attempts on your site. Capture detailed information for every login attempt.

= Login History Features =
* **Full, comprehensive login history**
* **Records both successful and failed login attempts**
* **Captures any error messages shown for failed login attempts**
* **Adds a Last Login column to the users page in the admin area**
* **Login history is fully searchable by username or IP address**
* **Login history can be filtered according to login outcome**
* **Modern, elegant and easy to use plugin**

Login history provides essential visibility to all login attempts and greatly assists in troubleshooting security issues or login problems. 

A record of user logins is a key component of many security standards and governance policies.

The login history page provides the following information for each login attempt:

* **Date and time of login attempt**
Shown in your local timezone
* **IP address**
Of the device that attempted to login
* **IP location**
The geographic location of the connecting device
* **Device type**
e.g. Windows, Mac, iPhone etc.
* **Username**
The username that was used to login
* **Result code**
e.g. success, incorrect_password
* **Message** 
The error message that was displayed to the user (if any)

Login history is a modern, lightweight plugin that will not impact the performance of your site. GDPR Compliant.


= Last Login =

Login to the admin area of your site and select 'Users' from the left sidebar.

A last login time for each user is shown in the 'last login' column.


= Login history =

In the sidebar of the 'Users' section there will be a new link for 'Login history'. Select this page to view the login history.

You can also view the login history for a single user by clicking the 'Login history' link displayed in the 'Users' table.
 

== Installation ==

**Using the WordPress dashboard**

* Navigate to 'Plugins' in the WordPress dashboard.
* Click on 'Add new' to add a new plugin.
* Search for ‘login history’
* Click ‘Install Now’
* Click the 'Activate' link when your download has completed.


**Uploading via the WordPress Dashboard**

First, download the Login History plugin file to your computer.

* Navigate to 'Plugins' in the WordPress dashboard.
* Click on 'Add new' to add a new plugin.
* Click on 'Upload Plugin' at the top of the page.
* Click 'Choose File'
* Select login-history.zip from your computer
* Click ‘Install Now’
* Click the 'Activate' link when your download has completed.

== Frequently Asked Questions ==

= What level of expertise do I need to configure login history? =

Login history is very easy to use and requires no configuration. Simply download the plugin and activate it.

= Is login history compatible with Cloudflare or other CDNs? =

Yes.

Load balancers and CDNs are known as reverse proxies. Login history will detect a reverse proxy and provide the correct IP address.

= How do I view the login history for my website? =

The login history page can be found in the 'Users' section of the admin area. By default, the login log will show user login attempts from the past 90 days. 
You can filter the login log by:

1. Time period for all login history entries you wish to see.

2. The type of login attempt you wish to see:

* Successful logins to your website.
* Failed logins to your website.
* All login attempts.

3. Search login history entries by IP address.

4. Search login history entries by username.

The time and date of each login attempt is shown along with username, IP address, IP location, Device type and outcome/message.

= How do I view the login history for a specific user? =

There are 2 ways:

Each user in the Users page will have a 'login history' link that will take you directly to their login history.

Alternatively, you can access the login history page and type the username in the search box.

= Why do I need a login history? =

Keeping and analyzing a login history is an essential task for a site administrator. Examining login records (both failed and successful) will alert you to any potential security breaches or unauthorized use of your system. For example, remote logins from unknown IP addresses or multiple failed login attempts on a single account should raise a red flag.


== Screenshots ==

1. The **login history** page showing detailed information about login attempts.
2. The Users page with added link to show  a **login log** on a per user basis.

== Changelog ==

= 2.1.2 =
* Added last login functionality

= 2.1.1 =
* Minor bug fixes and improvements

= 2.1.0 =
* Initial release to WordPress.
