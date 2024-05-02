


## Fairy Godmother Project (FGP) Software
## May 2024


Contributors: Eric Bae, Gabe Carlton, Joshua Cottrell, Grayson Jones, Harmony Peura, Aelliana Seidenstein


## Purpose
This project is the result of a semesters' worth of collaboration among UMW students. The goal of the project was to create a web application that the Fairy Godmother Project could utilize to make it easier to provide assistance to families battling pediatric cancer. At a glance, families can create an account, log in, and fill out the Points Program Form and Community Care Package Form to request assistance. They can also view their own account information. Administrators can view and modify families and manage gift cards (to be provided to families). Superadministrators can manage other admin accounts. 

## Authors

The FGP Software was built on the base code taken from the ODHS Medicine Tracker.

The ODHS Medicine Tracker is based on an old open source project named "Homebase". [Homebase](https://a.link.will.go.here/) was originally developed for the Ronald McDonald Houses in Maine and Rhode Island by Oliver Radwan, Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker.

Modifications to the original Homebase code were made by the Fall 2022 semester's group of students. That team consisted of Jeremy Buechler, Rebecca Daniel, Luke Gentry, Christopher Herriott, Ryan Persinger, and Jennifer Wells.

A major overhaul to the existing system took place during the Spring 2023 semester, throwing out and restructuring many of the existing database tables. Very little original Homebase code remains. This team consisted of Lauren Knight, Zack Burnley, Matt Nguyen, Rishi Shankar, Alip Yalikun, and Tamra Arant. Every page and feature of the app was changed by this team.

The Gwyneth's Gifts VMS code was modified in the Fall of 2023, revamping the code into the present ODHS Medicine Tracker code. Many of the existing database tables were reused, and many other tables were added. Some portions of the software's functionality were reused from the Gwyneth's Gifts VMS code. Other functions were created to fill the needs of the ODHS Medicine Tracker. The team that made these modifications and changes consisted of Garrett Moore, Artis Hart, Riley Tugeau, Julia Barnes, Ryan Warren, and Collin Rugless.

Finally, the code was taken over and overhauled for the Fairy Godmother Project in the Spring of 2024. This effort was done by Eric Bae, Gabe Carlton, Josh Cottrell, Grayson Jones, Harmony Peura, and Aelliana Seidenstein. 

## User Types
There are three types of users within the FGP software.
* Families
* Admins
* SuperAdmins

Families have the ability to create an account, log in, fill out the Points Program Form, fill out the Community Care Package form, and view their own account information.

Admins have the ability to approve family accounts, view families, modify families, delete families, add/delete volunteers, manage gift cards, update passwords, and generate reports. 

SuperAdmins inherit all the Admin abilities and can also add/modify/delete Admin accounts. 

There is also a root admin account with username and password'vmsroot'.

## Features
Features in the system are as follows:
*Login
*View account information
*Modify account information
*Update password (admin/super admin)
*Fill out community care package form (family)
*Create family account
*Admin Account Module 
  *View admin accounts
  *Modify admin accounts
  *Add admin accounts
  *Delete admin accounts
*Family Account Module 
  *View family accounts
  *Modify family accounts
  *Change family status
  *Delete family accounts  
  *Approve family account
*Reports Module
  *Generate current families report
  *Generate remission/survivor report
  *Generate stargazer report
*Gift card module (admin unless noted)
  *Fill out points program form (for families)
  *Sign off on gift card receival (families)
  *Add gift card vendor
  *Delete gift card vendor
  *View gift card vendors
  *Generate gift card order
  *View past gift card order reports
  *View gift card sign off
  *Facilitate gift card sign off
  *Generate gift card sign off form

## Design Documentation
Several types of diagrams describing the design of the FGP Software, including sequence diagrams and use case diagrams, are available. Please contact Dr. Polack for access.

## "localhost" Installation
Below are the steps required to run the project on your local machine for development and/or testing purposes.
1. [Download and install XAMPP](https://www.apachefriends.org/download.html)
2. Open a terminal/command prompt and change directory to your XAMPP install's htdocs folder
  * For Mac, the htdocs path is `/Applications/XAMPP/xamppfiles/htdocs`
  * For Ubuntu, the htdocs path is `/opt/lampp/htdocs/`
  * For Windows, the htdocs path is `C:\xampp\htdocs`
3. Clone the ODHS Medicine Tracker repo by running the following command: 'https://github.com/harmonyrose/FGP.git'
4. Start the XAMPP MySQL server and Apache server
5. Open the PHPMyAdmin console by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
6. Create a new database named `homebasedb`. With the database created, navigate to it by clicking on it in the lefthand pane
7. Import the `vms.sql` file located in `htdocs/FGP/sql` into this new database
8. Create a new user by navigating to `Privileges -> New -> Add user account`
9. Enter the following credentials for the new user:
  * Name: `homebasedb`
  * Hostname: `Local`
  * Password: `homebasedb`
  * Leave everything else untouched
10. Navigate to [http://localhost/FGP/](http://localhost/FGP/) 
11. Log into the root user account using the username `vmsroot` with password `vmsroot`
12. Change the root user password to a strong password

Installation is now complete.

## Reset root user credentials
In the event of being locked out of the root user, the following steps will allow resetting the root user's login credentials:
1. Using the PHPMyAdmin console, delete the `vmsroot` user row from the `dbPersons` table
2. Clear the SiteGround dynamic cache [using the steps outlined below](#clearing-the-siteground-cache)
3. Navigate to gwyneth/insertAdmin.php. You should see a message that says `ROOT USER CREATION SUCCESS`
4. You may now log in with the username and password `vmsroot`

## Platform
Dr. Polack chose SiteGround as the platform on which to host the project. Below are some guides on how to manage the live project.

### SiteGround Dashboard
Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground
Follow these steps to transfter your localhost version of the Fairy Godmother Project Software code to Siteground. For a video tutorial on how to complete these steps, contact Dr. Polack.
1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:
  - Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the 'Create Database' button. Database name is auto-generated and can be changed if you like.
  - User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the 'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be changed  if you like.
  - Password - Created when user is created. Password is auto generated and can be changed if you like.
4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and impor the "vms.sql" file from your software files.
  - Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the database.
6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3. 

### Clearing the SiteGround cache
There may occasionally be a hiccup if the caching system provided by SiteGround decides to cache one of the application's pages in an erroneous way. The cache can be cleared via the Dashboard by navigating to Speed -> Caching on the lefthand side of the control panel, choosing the DYNAMIC CACHE option in the center of the screen, and then clicking the Flush Cache option with a small broom icon under Actions.

## External Libraries and APIs
The only outside library utilized by the FGP Software is the jQuery library. The version of jQuery used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and the hiding/showing of certain page elements.

## Potential Improvements
* Implement incomplete use cases (notifications, reports, viewing received assistance)
* Updating remission to survivor automatically could be implemented more robustly, current implementation does not change status in back-end and instead check for remission end dat
* Remove unused database tables
* Remove unused PHP files
* Continue testing for edge cases
* Implement more robust error handling for forms
* Implement search feature for families, volunteers, vendors, and admins


## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements
Thank you to Dr. Polack for the chance to work on this exciting project. A lot of love went into making it!
