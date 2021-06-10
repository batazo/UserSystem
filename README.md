# UserSystem
User Dasboard. User handler system with JSON api (PHP based backend) and Fetch API (Javascript based frontend)

## This project is currently under development.
- Expect heavy code breaking changes.
- Version: 0.46-dev

## Frontend demos
- [Github.io DEMO](https://bzozoo.github.io/UserSystem/public/Frontends/dashboard.html)
- [Codepen.io DEMO](https://codepen.io/bzozoo/pen/yLMqMPj?editors=0010) Actual Codepen Version: v23-dev

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

***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/login
Method: POST
```

***QueryParams:***
| KEY | Description |
|-----|-------------|
| nameField | UserName |
| passField | UserPassword |


***Responses:***

- If Status 401 (Unauthorized) | Type: JSON
```js
{
    "Login": "Failed",
    "SessionId": "Failed",
    "UTOK": "Failed",
    "UserName": "Failed",
    "UserScore": "Failed"
}
```

- If Status 200 (OK) | Type: JSON
```js
{
    "Login": "Success",
    "SessionId": "String",
    "UTOK": " JWT String ",
    "UserName": "String",
    "UserScore": Number
}
```

##### CALL FOR USER REGISTRATION #####
***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/register
Method: POST
```

***QueryParams:***
| KEY | Description |
|-----|-------------|
| reguser | UserName |
| regpwd | UserPassword |

***Responses:***
- If Status 201 (Created)
```js
{
    "UserExisted": "NO",
    "Registration": "Success"
}
```

- If Status 409 (Conflict) | If User already exist | Type: JSON
```js
{
    "UserExisted": "YES",
    "Registration": "Failed"
}
```
- If Status 409 (Conflict) | If other problem | Type: JSON
```js
{
    "UserExisted": "NO",
    "Registration": "Failed"
}
```

 ##### CALL FOR MEMBER DATAS #####
 - IF JWT Authentication with POST
 
 ***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/user
Method: POST
```
***QueryParams:***
| KEY | Description |
|-----|-------------|
| jwtKEY | JWT string |

 - IF JWT Authentication with Authorizon header
 
 ***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/user
Method: -
```
***Headers:***
| Key | Value | Description |
| --- | ------|-------------|
| Authorization | Bearer + JWTstring | The JWT received by the login endpoint  |

- IF SessionID Authentication with POST | Type: JSON
 
 ***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/userprofile
Method: POST
```
***QueryParams:***
| KEY | Description |
|-----|-------------|
| sessid | Sessionstring |

- IF SessionID Authentication on same domain
 
 ***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/profile
Method: GET
Notes: POST key or Header is not necessary. PHPSESSION cookie need
```

***Responses:***

- If Status 401 (Unauthorized) | Type: JSON
```js
{
    "UserName": "Failed",
    "User": "DoesnotExist"
}
```

- If Status 200 (OK) | Type: JSON
```js
{
    "CreatedTimeStamp": <<Timestamp>>,
    "ActuallTimeStamp": <<Timestamp>>,
    "ExpiredTimeStamp": <<Timestamp>>,
    "UserRegistredAt": <<Date>>,
    "UserName": <<String>>,
    "UserAvatar": <<URL or null>>,
    "UserScore": <<Number>>,
    "UserSpeed": <<Number or null>>,
    "User": "Exist"
}
```

- UserExist check
 
 ***Endpoint:***
 ```bash
<ins>Path:</ins> {{YourDomain}}/api/membercheck/<<QueryParam>>
Method: GET
```
***QueryParams:***
| KEY | Description |
|-----|-------------|
| <<QueryParam>> | UserName string. Example: '/api/membercheck/Bzozoo' |

***Responses:***

- If User exist. | Type: Text
YES

- If User does not exist. | Type: Text
NO


##### CALL FOR USER SCORES #####
- All User Scores:

***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/userscore
Method: GET
```
This endpoint don't wait datas. It will return automaticaly with all username data and their scores in JSON format

- Single User Score:

***Endpoint:***
```bash
<ins>Path:</ins> {{YourDomain}}/api/userscore/<<QueryParam>>
Method: GET
```
***QueryParams:***
| KEY | Description |
|-----|-------------|
| <<QueryParam>> | UserName string. Example: '/api/userscore/Bzozoo' |

***Responses:***

- If User Exist | Status 200 | Type: JSON
```js
{
    "UserName": <<String>>,
    "UserScore": <<Number>>
}
```

- If User does not exist | Status 200 | Type: JSON
```js
{
    "UserName": "UserName does not exist",
    "UserScore": "UserScore does not exist"
}
```

## JavaScript/FetchAPI USAGE (FRONTEND)
##### SEND DATA FOR LOGIN #####
```
    let nameField = 'USERNAME FOR LOGIN'
    let nameField = 'PASSWORD FOR LOGIN'
    
  	var formData = new FormData();
	    formData.append("nameField", nameField);
	    formData.append("passField", passField);
    
   	let loginFetchOptions = {method: "POST", credentials: "include", mode: "cors", body: formData};
    
    let loginEndpoint = 'YOURSERVERPATH/api/login'
    
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
  
  const regEndpoint = "YOURSERVERPATH/api/register";
  
  fetch(regEndpoint, regFetchOptions)
			.then((response) => {
				if (response.ok) {
					return response.json();
				} else {
					// Do something, if response is not ok
				}
			})
			.then(function (data) {
			
			  	if (data.UserExisted === "NO" && data.Registration === "Success") {
                               // Do something, if user registration is success
			       }
			       			       
			  	if (data.UserExisted === "YES" && data.Registration === "Failed") {
                               // Do something, if user registration is failed, becouse User is already exist
			       }
			       
			       			
			  	if (data.UserExisted === "NO" && data.Registration === "Failed") {
                               // Do something, if user registration is failed, becouse there are other problem
			       }
				
			})
			.catch((error) => {
			        console.error("Catch error" + error);
				// Do something if you got an error in connection
			});
  
```

##### SEND DATA FOR USER PROFILE #####
- With JWT key
```
async function sendRequestForActualUserProfile(jwtKEY) {
	let jwtData = new FormData();
	jwtData.append("jwtKEY", jwtKEY);

	let fetchOptions = {method: "POST", body: jwtData,	credentials: "include",	mode: "cors", cache: "no-cache"};
        let memberEndpoint = "YOURSERVERPATH/api/userprofile"
	
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

async function getLoggedInUserProfile(jwt) {
	let data = await sendRequestForActualUserProfile(jwt));
	let profile = await data;
	return profile;
}

let jwtFromCookie = 'JWT string'
let actualUserProfileDatas = await getLoggedInUserProfile(jwtFromCookie)
```

- With Session
```
async function sendRequestForActualUserProfile(session) {
	let sessionData = new FormData();
	sessionData.append("sessid", session);

	let fetchOptions = {method: "POST", body: sessionData,	credentials: "include",	mode: "cors", cache: "no-cache"};
        let memberEndpoint = "YOURSERVERPATH/api/userprofile"
	
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
        
	let userScoreEndpoint = 'YOURSERVERPATH/BACKEND/userscore/'
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