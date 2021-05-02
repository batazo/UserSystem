# UserSystem
User Dasboard. User handler system with JSON api (PHP based backend) and Fetch API (Javascript based frontend)

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
- If User does not exist in system but there are an other error, the register endpoint will return with this JSON object:
  ```
  {'UserExist' : "NO", 'Registration' : "Failed"}
  ```
- If User does not exist and there are not an other error, the register endpoint will return with this JSON object:
  ```
  {'UserExist' : "NO", 'Registration' : "Success"}
  ```
  In this case, the registration will be complete.
 
 ##### CALL FOR MEMBER DATAS #####
 <ins>Endpoint:</ins> [BACKEND/member.php?profile](BACKEND/member.php)
 - Member endpoint waits `$_POST['sessid']` data from frontend
 - If this session does not exist in the server, the member endpoint will return with this JSON object:
   ```
     { 'UserName' : 'Failed', 'User' : 'DoesnotExist' }
   ```
 -  If this session does not exist in the server, the member endpoint will return same datas as the login endpoint.
  <ins>Endpoint:</ins> [BACKEND/member.php?profile-local](BACKEND/member.php)
  - If the frontend are on same server if the frontend is on the same server as the backend, this endpoint will use the PHPSESSION cookie datas and it will return same data as login endpoint
<ins>Endpoint:</ins> [BACKEND/member.php?memberCheck](BACKEND/member.php)
- This endpoint waits `$_POST['checkeMember']` data in `POST` method and it will return with simple YES or NO in text format, depending on user exists or does not exist

##### CALL FOR USER SCORES #####
<ins>Endpoint:</ins> [BACKEND/userscore.php?userName=USERNAME][BACKEND/userscore.php] (where USERNAME is the name of the user whose score information I want to retrieve )
- This endpoint waits `$_GET["userName"]` datas in `GET` method and it will return with `{ 'UserName' : 'USERNAME', 'UserScore' : 'USERSCORE'}` object if user exists and with `{"UserName": "UserName does not exist", "UserScore":"UserScore does not exist"}` object if user does not exist
