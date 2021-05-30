# UserSystem
User Dasboard. User handler system with JSON api (PHP based backend) and Fetch API (Javascript based frontend)

## This project is currently under development.
Expect heavy code breaking changes.
Version: 0.4-dev

## Frontend demos
- [Github.io DEMO](https://bzozoo.github.io/UserSystem/public/Frontends/dashboard.html)
- [Codepen.io DEMO](https://codepen.io/bzozoo/full/xxgvEQp) Actual Codepen Version: v15 

## System Reuirements:
* PHP 7.2 or newer
* MySQL
* Webserver

## INSTALLATION:
* Clone or download package
* Move the allsrc folder to a private folder
* Move the files from public folder to your webdocroot
* Use `composer install` to initialize the project 
* Create a database on your hosting or own server and run SQL code from [/install/install.sql](/install/install.sql) file
* You can create the structure in Phpmyadmin too
![Database structure](/install/table-structure.jpg?raw=true)
* Set Admin password in 'UserPassword' column in database. Use a passwordhash generator
* Set your own database connection datas in [private/db-config.ini](private/db-config.ini) file

> I've created an index.php file inside the public folder to create an single entry
> point for this project. If you go to the public folder and use `php -S localhost:8080`
> u can fire up the project. Can be reached at http://localhost:8080

## API USAGE (BACKEND)
##### CALL FOR LOGIN #####
<ins>Endpoint:</ins> [YourDomain/api/login](src/Views/login.php) .
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
<ins>Endpoint:</ins> [YourDomain/api/register](src/Views/register.php)
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
 <ins>Endpoint:</ins> [YourDomain/api/userprofile](src/Views/member.php)
 - Member endpoint waits `$_POST['sessid']` data from frontend
 - If this session does not exist in the server, the member endpoint will return with this JSON object:
   ```
     { 'UserName' : 'Failed', 'User' : 'DoesnotExist' }
   ```
 -  If this session does not exist in the server, the member endpoint will return same datas as the login endpoint.
  <ins>Endpoint:</ins> [YourDomain/api/profile](src/Views/member.php)
  - If the frontend are on same server if the frontend is on the same server as the backend, this endpoint will use the PHPSESSION cookie datas and it will return same data as login endpoint

<ins>Endpoint:</ins> [YourDomain/api/membercheck/SEARCHED-MEMBERNAME](src/Views/member.php)
- This endpoint waits `SEARCHED-MEMBERNAME` data from URL in `POST` method and it will return with simple YES or NO in text format, depending on user exists or does not exist

##### CALL FOR USER SCORES #####
<ins>Endpoint:</ins> [YourDomain/api/userscore/SEARCHED-USERNAME][src/Views/userscore.php] (where SEARCHED-USERNAME is the name of the user whose score information I want to retrieve )
- This endpoint waits `SEARCHED-USERNAME` data in `GET` method from URL and it will return with `{ 'UserName' : 'USERNAME', 'UserScore' : 'USERSCORE'}` object if user exists and with `{"UserName": "UserName does not exist", "UserScore":"UserScore does not exist"}` object if user does not exist
<ins>Endpoint:</ins> [YourDomain/api/userscore][src/Views/userscore.php]
- This endpoint don't wait datas. It will return automaticaly with all username data and their scores

## JavaScript/FetchAPI USAGE (FRONTEND)
##### SEND DATA FOR LOGIN #####
```
    let nameField = 'USERNAME FOR LOGIN'
    let nameField = 'PASSWORD FOR LOGIN'
    
  	var formData = new FormData();
	    formData.append("nameField", nameField);
	    formData.append("passField", passField);
    
   	let loginFetchOptions = {method: "POST", credentials: "include", mode: "cors", body: formData};
    
    let loginEndpoint = 'YOURSERVERPATH/BACKEND/login.php'
    
    fetch(loginEndpoint, loginFetchOptions)
			.then((response) => {
				if (response.ok) {
					return response.json();
				} else {
					// do something if response not ok
				}
			})
			.then(function (data) {
				console.log("I got login DATAS : ");
				console.log(data);
				
				storedLoginDatas = data;
				

				if (storedLoginDatas.Login === "Success") {
					// Do something if login success
				}

				if (storedLoginDatas.Login === "Failed") {
					// Do something if login failed
				}
			})
			.catch((error) => {
				console.error("Catch error" + error);
				// Do something if you got an error
			});
    
```


##### SEND DATA FOR REGISTRATION #####
```
  let regNameFieldValue = 'NAME OF USER, WE WATNT TO REGISTER'
  let regPassFieldValue = 'PASSWORD OF USER, WE WATNT TO REGISTER'
  
  var formData = new FormData();
  formData.append("reguser", regNameFieldValue);
  formData.append("regpwd", regPassFieldValue);
  
  let regFetchOptions = {method: "POST", credentials: "include", mode: "cors", body: formData};
  
  const regEndpoint = "YOURSERVERPATH/BACKEND/register.php";
  
  fetch(regEndpoint, regFetchOptions)
			.then((response) => {
				if (response.ok) {
					return response.json();
				} else {
					// Do something, if response is not ok
				}
			})
			.then(function (data) {
			
			  	if (data.UserExist === "NO" && data.Registration === "Success") {
                               // Do something, if user registration is success
			       }
			       			       
			  	if (data.UserExist === "YES" && data.Registration === "Failed") {
                               // Do something, if user registration is failed, becouse User is already exist
			       }
			       
			       			
			  	if (data.UserExist === "NO" && data.Registration === "Failed") {
                               // Do something, if user registration is failed, becouse there are other problem
			       }
				
			})
			.catch((error) => {
			        console.error("Catch error" + error);
				// Do something if you got an error in connection
			});
  
```

##### SEND DATA FOR USER PROFILE #####
```
async function sendRequestForActualUserProfile(session) {
	let sessionData = new FormData();
	sessionData.append("sessid", session);

	let fetchOptions = {method: "POST", body: sessionData,	credentials: "include",	mode: "cors", cache: "no-cache"};
        let memberEndpoint = "YOURSERVERPATH/BACKEND/member.php?profile"
	
	let responsedjson = false;
	try {
		const response = await fetch(memberEndpoint, fetchOptions);
		responsedjson = await response.json();
	} catch (error) {
		console.log("Error in member request");
		console.log(Error);
		responsedjson = false;
	}
	return responsedjson;
}

async function getLoggedInUserProfile(session) {
	let data = await sendRequestForActualUserProfile(session));
	let profile = await data;
	return profile;
}

let sessionFromCookie = 'SESSION COOKIE SESSION HASH'
let actualUserProfileDatas = await getLoggedInUserProfile(sessionFromCookie)
```
##### SEND DATA FOR USER SCORE BY NAME #####
```
async function getUserScoreEndpoint(user) {
	let userScoreFetchOptions = {method: "GET", cache: "no-cache", mode: "cors"};
        
	let userScoreEndpoint = 'YOURSERVERPATH/BACKEND/userscore.php?userName='
	let responsedjson = false;
	try {
		const response = await fetch(userScoreEndpoint + user, userScoreFetchOptions);
		responsedjson = await response.json();
	} catch (error) {
		console.log("Error in getScore");
	}
	return responsedjson;
}

async function getScoreByUsername(uname) {
	let user = await getUserScoreEndpoint(uname);
	let userscore = "Server unavailable";
	if (user) {
		userscore = await user.UserScore;
	}
	return userscore;
}

let uname = 'Name of the user whose score information We want to retrieve'
let scoreOfName = await getScoreByUsername(uname)

```
