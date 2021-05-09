//API Endpoints
const loginEndpoint = "https://usersystem.mysqhost.tk/api/login";
const regEndpoint = "https://usersystem.mysqhost.tk/api/register";
const memberEndpoint = "https://usersystem.mysqhost.tk/api/userprofile";
const userScoreEndpoint = "https://usersystem.mysqhost.tk/api/userscore";

//constanes and variables
let loginForm = document.querySelector("#loginForm");
let nameField = document.querySelector("#nameField");
let passField = document.querySelector("#passField");
let submitButton = document.querySelector("#submitButton");
let regForm = document.querySelector("#regForm");
let regNameField = document.querySelector("#regNameField");
let regPassField = document.querySelector("#regPassField");
let regConfirmPassField = document.querySelector("#regConfirmPassField");
let regSubmitButton = document.querySelector("#regSubmitButton");
const errorBoxCommon = document.querySelector("#errorBoxCommon");
const errorBoxName = document.querySelector("#errorBoxName");
const errorBoxPass = document.querySelector("#errorBoxPass");
const errorBoxReg = document.querySelector("#errorBoxReg");
const errorBoxes = document.querySelectorAll(".errorBox");
const loginProgressBox = document.querySelector("#loginProgressBox");
const regProgressBox = document.querySelector("#regProgressBox");
const regSuccessBox = document.querySelector("#regSuccessBox");
const inputBoxes = document.querySelectorAll("#container input");
const userDatasDiv = document.querySelector("#userDatasDiv");
let storedLoginDatas;
let storedUserScore;
let userProfileObject;

initLoginForm();

function initLoginForm() {
	if (checkUserCookiesExist()) {
		userLoggedIn();
	} else {
		userLogOut();
	}
}

function checkUserCookiesExist() {
	if (
		document.cookie.indexOf("ui=") === -1 ||
		document.cookie.indexOf("una=") === -1 ||
		document.cookie.indexOf("use=") === -1 ||
		document.cookie.indexOf("utk=") === -1
	) {
		return false;
	} else {
		console.log("User cookie exist. User Logged in");
		return true;
	}
}

function loginFormValues() {
	console.log(nameField.value);
	console.log(passField.value);
}

function loginError() {
	console.log("Login error");
}

function regError() {
	console.log("Register error");
}

function processLogin(nameField, passField) {
	let checkData = checkDatas(nameField, passField);

	if (checkData) {
		submitButton.disabled = true;

		loginProgressBox.classList.remove("hidden");
		var formData = new FormData();
		formData.append("nameField", nameField);
		formData.append("passField", passField);
		//console.log(formData)

		let loginFetchOptions = {
			method: "POST",
			credentials: "include",
			mode: "cors",
			body: formData
		};

		fetch(loginEndpoint, loginFetchOptions)
			.then((response) => {
				if (response.ok) {
					return response.json();
				} else {
					loginError();
				}
			})
			.then(function (data) {
				console.log("I got login DATAS : ");
				console.log(data);
				submitButton.disabled = false;
				loginProgressBox.classList.add("hidden");
				storedLoginDatas = data;
				//  storedLoginDatas = JSON.parse(data);

				if (storedLoginDatas.Login === "Success") {
					console.log(` Login ${storedLoginDatas.Login}`);
					createUserCookies();
					userLoggedIn();
				}

				if (storedLoginDatas.Login === "Failed") {
					console.log(` Login ${storedLoginDatas.Login}`);
					showLoginFail();
				}
			})
			.catch((error) => {
				console.error("Catch error" + error);
				submitButton.disabled = false;
				loginProgressBox.classList.add("hidden");
				errorBoxCommon.innerHTML = "Server connection error";
				errorBoxCommon.classList.remove("hidden");
			});
	}
}

function checkDatas(nameField, passField) {
	let valueLengthOfPass = document.querySelector("#passField").value.length;
	if (valueLengthOfPass < 8) {
		errorBoxPass.innerHTML = "The password must be 8 characters long ";
		errorBoxPass.classList.remove("hidden");
	}

	if (nameField === "") {
		errorBoxName.innerHTML = "Name is empty";
		errorBoxName.classList.remove("hidden");
	}

	if (passField === "") {
		errorBoxPass.innerHTML = "Password is empty";
		errorBoxPass.classList.remove("hidden");
	}

	if (passField != "" && nameField != "" && valueLengthOfPass >= 8) {
		return true;
	}
}

function checkRegDatas(
	regNameFieldValue,
	regPassFieldValue,
	regConfirmPassFieldValue
) {
	let valueLengthOfRegPass = document.querySelector("#regPassField").value
		.length;

	if (valueLengthOfRegPass < 8) {
		errorBoxRegPass.innerHTML =
			"The register password must be 8 characters long ";
		errorBoxRegPass.classList.remove("hidden");
	}

	if (regPassFieldValue != regConfirmPassFieldValue) {
		errorBoxRegConfirmPass.innerHTML = "The passwords do not match ";
		errorBoxRegConfirmPass.classList.remove("hidden");
	}

	if (regNameFieldValue === "") {
		errorBoxRegName.innerHTML = "Name field is empty";
		errorBoxRegName.classList.remove("hidden");
	}

	if (regPassFieldValue === "") {
		errorBoxRegPass.innerHTML = "Password field is empty";
		errorBoxRegPass.classList.remove("hidden");
	}

	if (regConfirmPassFieldValue === "") {
		errorBoxRegConfirmPass.innerHTML = "Confirm Password field is empty";
		errorBoxRegConfirmPass.classList.remove("hidden");
	}

	if (
		regPassFieldValue === regConfirmPassFieldValue &&
		regNameFieldValue != "" &&
		regPassFieldValue != "" &&
		valueLengthOfRegPass >= 8
	) {
		return true;
	}
}

function showLoginFail() {
	errorBoxCommon.innerHTML = "Wrong username or password";
	errorBoxCommon.classList.remove("hidden");
}

function processRegister(regNameFieldValue,	regPassFieldValue, regConfirmPassFieldValue){
	console.log("Register process start...");
	let checkRegData = checkRegDatas(regNameFieldValue,	regPassFieldValue, regConfirmPassFieldValue);

	if (checkRegData) {
		console.log("All Reg Data is correct... I will send it to server now");
		regSubmitButton.disabled = true;
		regProgressBox.classList.remove("hidden");

		var formData = new FormData();
		formData.append("reguser", regNameFieldValue);
		formData.append("regpwd", regPassFieldValue);
		//console.log(formData)

		let regFetchOptions = {method: "POST", credentials: "include",	mode: "cors", body: formData};

		fetch(regEndpoint, regFetchOptions)
			.then((response) => {
				if (response.ok) {
					return response.json();
				} else {
					regError();
				}
			})
			.then(function (data) {
				console.log("I got DATAS : ");
				console.log(data);
				regSubmitButton.disabled = false;
				regProgressBox.classList.add("hidden");
				storedRegDatas = data;
				//  storedLoginDatas = JSON.parse(data);

				regFormMessages(storedRegDatas);
			})
			.catch((error) => {
				console.error("Catch error" + error);
				regSubmitButton.disabled = false;
				regProgressBox.classList.add("hidden");
				errorBoxReg.innerHTML = "Server connection error";
				errorBoxReg.classList.remove("hidden");
			});
	}
}

function regFormMessages(storedRegDatas) {
	if (
		storedRegDatas.UserExist === "YES" &&
		storedRegDatas.Registration === "Failed"
	) {
		console.log("User Exist! Registration failed");
		errorBoxReg.innerHTML = "User already exist!  Registration failed!";
		errorBoxReg.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
		regSuccessBox.classList.add("hidden");
	}

	if (
		storedRegDatas.UserExist === "NO" &&
		storedRegDatas.Registration === "Failed"
	) {
		console.log("Server Error. Registration failed! Try again later");
		errorBoxReg.innerHTML = "Server Error! Registration failed! Try again later!";
		errorBoxReg.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
		regSuccessBox.classList.add("hidden");
	}

	if (
		storedRegDatas.UserExist === "NO" &&
		storedRegDatas.Registration === "Success"
	) {
		console.log("Registration success!");
		errorBoxReg.classList.add("hidden");
		regSuccessBox.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
	}
}

function createUserCookies() {
	let stayloggedcheck = document.querySelector("#stayloggedcheck");
	var now = new Date();
	var time = now.getTime();
	var expireTime = time + 1000 * 400 * 36000;
	now.setTime(expireTime);

	let expiration = stayloggedcheck.checked
		? `expires=${now.toUTCString()};`
		: "";
	console.log(`Cookie expirations : ${expiration}`);

	document.cookie = `ui=${storedLoginDatas.UserID}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `una=${storedLoginDatas.UserName}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `use=${storedLoginDatas.UserSecret}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `utk=${storedLoginDatas.UserToken}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `ses=${storedLoginDatas.SessionId}; ${expiration} path=/; SameSite=None; Secure`;
}

function deleteUserCookies() {
	document.cookie = `PHPSESSID=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `ui=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `una=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `use=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `utk=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `ses=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
}

function userLogOut() {
	userDatasDiv.classList.add("hidden");
	userDatasDiv.innerHTML = "";
	deleteUserCookies();
	loginFormDiv.classList.remove("hidden");
	console.log("User logged out ...");
}

async function userLoggedIn() {
	let profile = await getLoggedInUserProfile();
	console.log("User Profile Datas : ");
	console.log(profile);

	if (profile.User === "DoesnotExist" && profile.UserName === "Failed") {
		errorBoxCommon.innerHTML = "User session expired! Login again";
		errorBoxCommon.classList.remove("hidden");
		userLogOut();
		return;
	}

	let profileUserRegistredAt = !profile
		? "Server unavailable"
		: profile.UserRegistredAt;

	let cookieUserName = getCookie("una");
	let cookieUserId = getCookie("ui");
	let cookieUserSecret = getCookie("use");
	let cookieUserToken = getCookie("utk");
	let cookiePHPses = getCookie("ses");

	removeAllErrorMessage();
	loginFormDiv.classList.add("hidden");
	regSuccessBox.classList.add("hidden");

	userDatasDiv.innerHTML = `<p>WELCOME HERE 
   <red style="color: red"><b> ${cookieUserName} </b></red> !</p>
   <p>UREGAT :  ${profileUserRegistredAt}</p>
   <p>UID :  ${cookieUserId}</p>
   <p>USEC :  ${cookieUserSecret}</p>
   <p>UTOK :  ${cookieUserToken}</p>
   <p>PHPSES :  ${cookiePHPses}</p>`;

	userDatasDiv.innerHTML += `<p id="userscoreinprofile">USER SCORE :
     Loading...<img src="https://bzozoo.github.io/UserSystem/public/Frontends/img/load.webp" /></p>
     <p><a target="_blank" href="https://codepen.io/bzozoo/full/VwmKOVj">
     <button class="linkbuttons">SCORE TABLE</button></a></p>

     <p>
     <div class="buttoncontainer">
    <input class="actionbuttons" id="logoutButton" name="logoutButton" type="button" value="LOGOUT" onclick="userLogOut()">
   </div>
    </p>`;

	renderUserScoreByName(
		cookieUserName,
		document.querySelector("#userscoreinprofile")
	);

	userDatasDiv.classList.remove("hidden");
}

async function getUserScoreEndpoint(user) {
	let userScoreFetchOptions = {
		method: "GET",
		cache: "no-cache",
		
	};

	let responsedjson = false;
	try {
		const response = await fetch(userScoreEndpoint + `/` + user, userScoreFetchOptions);
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

async function renderUserScoreByName(who, where) {
	let score = await getScoreByUsername(who);
	where.innerHTML = "USER SCORE : " + score;
}

function getCookie(name) {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(";").shift();
}

async function sendRequestForActualUserProfile(session) {
	let sessionData = new FormData();
	sessionData.append("sessid", session);

	let fetchOptions = {
		method: "POST",
		body: sessionData,
		credentials: "include",
		mode: "cors",
		cache: "no-cache"
	};

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

async function getLoggedInUserProfile() {
	let data = await sendRequestForActualUserProfile(getCookie("ses"));
	let profile = await data;
	return profile;
}

async function consoleLogLoggedInUserProfile() {
	let profile = await getLoggedInUserProfile();
	console.log(profile);
	console.log(
		"User logged in : " +
			profile.UserName +
			" - User actual Score : " +
			profile.UserScore
	);
	return profile;
}

function reqListener() {
	console.log(this.responseText);
}

function getMemberX() {
	var oReq = new XMLHttpRequest();
	oReq.addEventListener("load", reqListener);
	oReq.open("GET", memberEndpoint);
	oReq.withCredentials = true;
	oReq.send();
}

function loadXMLDoc(theURL) {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari, SeaMonkey
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			alert(xmlhttp.responseText);
		}
	};
	xmlhttp.open("GET", theURL, false);
	xmlhttp.send();
}

function removeAllErrorMessage() {
	errorBoxes.forEach(function (errorBox) {
		errorBox.innerHTML = "";
		errorBox.classList.add("hidden");
	});
}

inputBoxes.forEach(function (inputBox) {
	inputBox.addEventListener("input", removeAllErrorMessage);
});

submitButton.addEventListener("click", () =>
	processLogin(nameField.value, passField.value)
);

regSubmitButton.addEventListener("click", () =>
	processRegister(
		regNameField.value,
		regPassField.value,
		regConfirmPassField.value
	)
);
