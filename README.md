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
##### CALL FOR LOGIN #####
<ins>Endpoint:</ins> [BACKEND/login.php](BACKEND/login.php) .
- Login endpoint waits `$_POST['nameField']` and `$_POST['passField']` datas in `POST` method
- If Username and Password is not found in database , the login endpoint will return with this JSON object:
  ``` 
  '{"Login": "Failed", "UserID":"Failed", "UserName":"Failed", "UserRegistredAt":"Failed", "UserSecret":"Failed", "UserToken":"Failed"}'
  ```
- If Username and Password is match , the login endpoint will return with this JSON object:
  ```
  '{"Login": "Success", "SessionId":"'. session_id() .'" ,"UserID":"'. $_SESSION['UserID'] .'","UserName":"'. $_SESSION['UserName'] .'", "UserRegistredAt":"'. $memberProfile[0]['UserRegTime'] .'", "UserSecret":"'. $memberProfile[0]['UserSecret'] .'", "UserToken":"'. $memberProfile[0]['UserToken'] .'"}'
  ```
  Where `session_id()` is session of logged-in user, `$_SESSION['UserID']` ID of logged-in user, `$_SESSION['UserName']` Name of logged-in user, `$memberProfile[0]['UserRegTime']` is registration date and time of logged-in user, `$memberProfile[0]['UserSecret']` is Secret token of logged-in user, `$memberProfile[0]['UserToken']` is a general token of logged-in user

##### CALL FOR USER REGISTRATION #####
<ins>Endpoint:</ins> [BACKEND/register.php](BACKEND/register.php)
- Register endpoint waits `$_POST["reguser"]` and `$_POST["regpwd"]` datas in `POST` method
- If User exists, the registration will be failed and the regitration endpoint will return with this JSON object:
  ```
  {'UserExist' : "YES", 'Registration' : "Failed"}
  ```
