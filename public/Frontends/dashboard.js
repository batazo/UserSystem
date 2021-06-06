//API Endpoints
const loginEndpoint = "https://usersystem.mysqhost.tk/api/login";
const regEndpoint = "https://usersystem.mysqhost.tk/api/register";
const memberEndpoint = "https://usersystem.mysqhost.tk/api/user";
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
const profileCOntainer = document.querySelector("#profileCOntainer");
const defaultAvatar = 'https://raw.githubusercontent.com/bzozoo/UserSystem/main/public/Frontends/img/avatar.png';
let responseStatus;
let storedLoginDatas;
let storedRegisterDatas;
let getAutHeader;

///INITIALIZATION

initLoginForm();

function initLoginForm() {
	if (checkUserCookiesExist()) {
		userLoggedIn();
	} else {
		userLogOut();
	}
}

///INITIALIZATION END

// Connections
async function connection(url, options) {
	const response = await fetch(url, options);
	responseStatus = response.status;
	console.log('Actual response.status changed to ', responseStatus)
	let getAutHeaderAll = response.headers.get("authorization");
	getAutHeader = getAutHeaderAll
		? getAutHeaderAll.slice(7, getAutHeaderAll.length)
		: false;
	let responsedjson = await response.json();

	return responsedjson;
}

async function connectForLogin() {
	var formData = new FormData();
	formData.append("nameField", nameField.value);
	formData.append("passField", passField.value);

	let options = {
		method: "POST",
		credentials: "include",
		mode: "cors",
		body: formData
	};

	return await connection(loginEndpoint, options);
}

async function connectForRegister() {
	var formData = new FormData();
	formData.append("reguser", regNameField.value);
	formData.append("regpwd", regPassField.value);

	let options = {
		method: "POST",
		credentials: "include",
		mode: "cors",
		body: formData
	};

	return await connection(regEndpoint, options);
}

async function connectForUserDatas() {
	let jwtFormData = new FormData();
	jwtFormData.append("jwtKEY", getCookie("UTOK"));

	let options = {
		method: "POST",
		body: jwtFormData,
		credentials: "include",
		mode: "cors",
		cache: "no-cache"
	};
	return await connection(memberEndpoint, options);
}

// Connections END

//PROCESSES
async function loginProcess() {
	let checkedLoginDatas = checkLoginInputDatas(nameField.value, passField.value);
	if (checkedLoginDatas) {
		submitButton.disabled = true;
		loginProgressBox.classList.remove("hidden");
		try {
			storedLoginDatas = await connectForLogin();
		} catch (error) {
			console.error("Catch error" + error);
			storedLoginDatas = false;
			submitButton.disabled = false;
			loginProgressBox.classList.add("hidden");
			errorBoxCommon.innerHTML = "Server connection error";
			errorBoxCommon.classList.remove("hidden");
		}
		console.log(storedLoginDatas);
		if (storedLoginDatas) {
			console.log("We got login datas..");
			submitButton.disabled = false;
			loginProgressBox.classList.add("hidden");
			if (storedLoginDatas.Login === "Failed" || responseStatus === 401) {
				console.log("Login failed");
				showLoginFail();
			}
			if (storedLoginDatas.Login === "Success" || responseStatus === 200) {
				console.log("Login Success");
				createUserCookies();
				userLoggedIn();
			}
		}
	}
}

async function registerProcess() {
	console.log("Register process function...");
	let checkRegData = checkRegDatas();

	if (checkRegData) {
		console.log("Register input datas are okey...");
		regSubmitButton.disabled = true;
		regProgressBox.classList.remove("hidden");
		try {
			storedRegisterDatas = await connectForRegister();
		} catch (error) {
			console.error("Catch error" + error);
			regSubmitButton.disabled = false;
			storedRegisterDatas = false;
			regProgressBox.classList.add("hidden");
			errorBoxReg.innerHTML = "Server connection error";
			errorBoxReg.classList.remove("hidden");
		}

		if (storedRegisterDatas) {
			console.log("We got reg responses: ");
			console.log(storedRegisterDatas)
			regSubmitButton.disabled = false;
			regProgressBox.classList.add("hidden");
			regFormMessages(storedRegisterDatas);
		}
	}
}

function processRegister(
	regNameFieldValue,
	regPassFieldValue,
	regConfirmPassFieldValue
) {
	console.log("Register process start...");
	let checkRegData = checkRegDatas(
		regNameFieldValue,
		regPassFieldValue,
		regConfirmPassFieldValue
	);

	if (checkRegData) {
		console.log("All Reg Data is correct... I will send it to server now");
		regSubmitButton.disabled = true;
		regProgressBox.classList.remove("hidden");

		var formData = new FormData();
		formData.append("reguser", regNameFieldValue);
		formData.append("regpwd", regPassFieldValue);
		//console.log(formData)

		let regFetchOptions = {
			method: "POST",
			credentials: "include",
			mode: "cors",
			body: formData
		};

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

//PROCESSES END

// CHECKERS

function checkLoginInputDatas(nameFieldValue, passFieldValue) {
	if (passFieldValue.length < 8) {
		errorBoxPass.innerHTML = "The password must be 8 characters long ";
		errorBoxPass.classList.remove("hidden");
	}

	if (nameFieldValue === "") {
		errorBoxName.innerHTML = "Name is empty";
		errorBoxName.classList.remove("hidden");
	}

	if (passFieldValue === "") {
		errorBoxPass.innerHTML = "Password is empty";
		errorBoxPass.classList.remove("hidden");
	}

	if (
		passFieldValue != "" &&
		nameFieldValue != "" &&
		passFieldValue.length >= 8
	) {
		return true;
	}
}

function checkRegDatas() {
	if (regPassField.value.length < 8) {
		errorBoxRegPass.innerHTML =
			"The register password must be 8 characters long ";
		errorBoxRegPass.classList.remove("hidden");
	}

	if (regPassField.value != regConfirmPassField.value) {
		errorBoxRegConfirmPass.innerHTML = "The passwords do not match ";
		errorBoxRegConfirmPass.classList.remove("hidden");
	}

	if (regNameField.value === "") {
		errorBoxRegName.innerHTML = "Name field is empty";
		errorBoxRegName.classList.remove("hidden");
	}

	if (regPassField.value === "") {
		errorBoxRegPass.innerHTML = "Password field is empty";
		errorBoxRegPass.classList.remove("hidden");
	}

	if (regConfirmPassField.value === "") {
		errorBoxRegConfirmPass.innerHTML = "Confirm Password field is empty";
		errorBoxRegConfirmPass.classList.remove("hidden");
	}

	if (
		regPassField.value === regConfirmPassField.value &&
		regNameField.value != "" &&
		regPassField.value != "" &&
		regPassField.value.length >= 8
	) {
		return true;
	}
}

function checkUserCookiesExist() {
	if (
		document.cookie.indexOf("una=") === -1 ||
		document.cookie.indexOf("una=") === "ubdefined" ||
		document.cookie.indexOf("UTOK=") === -1 ||
		document.cookie.indexOf("UTOK=") === "undefined" ||
		document.cookie.indexOf("ses=") === -1 ||
		document.cookie.indexOf("ses=") === "undefined"
	) {
		return false;
	} else {
		console.log("User cookies exist.");
		return true;
	}
}

// CHECKERS END

//RENDER helpers
//Used in: userLoggedIn(), initLoginForm()
function userLogOut() {
	removeAllErrorMessage();
	profileCOntainer.classList.add("hidden");
	loadingContainer.classList.add("hidden");
	profileCOntainer.innerHTML = "";
	deleteUserCookies();
	formsDiv.classList.remove("hidden");
	console.log("User logged out ...");
}

//Used in: loginProcess(), initLoginForm()
async function userLoggedIn() {
	let profile = await connectForUserDatas();
	console.log("User Profile Datas : ");
	console.log(profile);

	if (profile.User === "DoesnotExist" && profile.UserName === "Failed") {
		errorBoxCommon.innerHTML = "User session expired! Login again";
		errorBoxCommon.classList.remove("hidden");
		userLogOut();
		return;
	}

	if (profile) {
		removeAllErrorMessage();
		formsDiv.classList.add("hidden");
		loadingContainer.classList.add("hidden");
		regSuccessBox.classList.add("hidden");

		profileCOntainer.innerHTML = `
		<p>WELCOME HERE <red style="color: red"><b> ${profile.UserName} </b></red> !</p>
		<p><img src="${(profile.UserAvatar != null || profile.UserAvatar === 'undefined')?profile.UserAvatar:defaultAvatar}" title="${profile.UserName}'s avatar" alt="avatar" width="250" height="250"/></p>
		<p>UREGAT :  ${profile.UserRegistredAt}</p>
		<p id="userscoreinprofile">USER SCORE : ${profile.UserScore}</p>
		<p><a target="_blank" href="https://codepen.io/bzozoo/full/VwmKOVj">
		<button class="linkbuttons">SCORE TABLE</button></a></p>
		<p><div class="buttoncontainer">
		<input class="actionbuttons" id="logoutButton" name="logoutButton" type="button" value="LOGOUT" onclick="userLogOut()">
		</div>
		</p>`;
	}

	profileCOntainer.classList.remove("hidden");
}

function showLoginFail() {
	errorBoxCommon.innerHTML = "Wrong username or password";
	errorBoxCommon.classList.remove("hidden");
}

function removeAllErrorMessage() {
	errorBoxes.forEach(function (errorBox) {
		errorBox.innerHTML = "";
		errorBox.classList.add("hidden");
	});
}


//Userd in: registerProcess()
function regFormMessages(storedRegDatas) {
	if (
		storedRegDatas.UserExisted === "YES" &&
		storedRegDatas.Registration === "Failed"
	) {
		console.log("User Exist! Registration failed");
		errorBoxReg.innerHTML = "User already exist!  Registration failed!";
		errorBoxReg.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
		regSuccessBox.classList.add("hidden");
	}

	if (
		storedRegDatas.UserExisted === "NO" &&
		storedRegDatas.Registration === "Failed"
	) {
		console.log("Server Error. Registration failed! Try again later");
		errorBoxReg.innerHTML = "Server Error! Registration failed! Try again later!";
		errorBoxReg.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
		regSuccessBox.classList.add("hidden");
	}

	if (
		responseStatus === 201 &&
		storedRegDatas.UserExisted === "NO" &&
		storedRegDatas.Registration === "Success"
	) {
		console.log("Registration success!");
		errorBoxReg.classList.add("hidden");
		regSuccessBox.classList.remove("hidden");
		regProgressBox.classList.add("hidden");
	}
}

//RENDER helpers END

//Cookie handlers
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

	document.cookie = `una=${storedLoginDatas.UserName}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `UTOK=${storedLoginDatas.UTOK}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `ses=${storedLoginDatas.SessionId}; ${expiration} path=/; SameSite=None; Secure`;
}

//Used in: userLogOut()
function deleteUserCookies() {
	document.cookie = `PHPSESSID=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `una=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `UTOK=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `ses=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
}

function getCookie(name) {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(";").shift();
}

//Cookie handlers END

//GENERAL helpers

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

//GENERAL helpers END

// TEST functions

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

// TEST functions END

//EVENT listeners

inputBoxes.forEach(function (inputBox) {
	inputBox.addEventListener("input", removeAllErrorMessage);
});

submitButton.addEventListener("click", loginProcess);

regSubmitButton.addEventListener("click", registerProcess);
//EVENT listeners END
