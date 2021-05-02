# UserSystem
User Dasboard. User handler system with JSON api and Fetch API frontend

## System Reuirements:
* PHP 5.6 - PHP 7.4
* MySQL
* Webserver

## INSTALLATION:
* Clone or download package
* Create a database on your hosting or own server and run SQL code from [/install/install.sql](/install/install.sql) file
* You can create the structure in Phpmyadmin too
![Database structure](/install/table-structure.jpg?raw=true)
* Set Admin password in 'UserPassword' column in database. Use a passwordhash generator
* Set your own database connection datas in [private/db-config.ini](private/db-config.ini) file

## API USAGE (BACKEND)
