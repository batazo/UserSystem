let version = 'v22-dev';
console.log(version)

//API Endpoints
const loginEndpoint = "https://usersystem.mysqhost.tk/api/login";
const regEndpoint = "https://usersystem.mysqhost.tk/api/register";
const memberEndpointJWT = "https://usersystem.mysqhost.tk/api/user";
const memberEndpointSes = "https://usersystem.mysqhost.tk/api/userprofile";
const userScoreEndpoint = "https://usersystem.mysqhost.tk/api/userscore";

//constanes and variables
const errorBoxes = document.querySelectorAll(".errorBox");
const successBoxes = document.querySelectorAll(".successBox");
const regSuccessBox = document.querySelector("#regSuccessBox");
const inputBoxes = document.querySelectorAll("#container input");

const defaultAvatar = 'https://raw.githubusercontent.com/bzozoo/UserSystem/main/public/Frontends/img/avatar.png';
let storedConnectionResponse;
let storedLoginDatas; //Used in: loginProcess()
let storedRegisterDatas; //Used in: registerProcess()
let getAutHeader;
let identifier;
let loadCounter = 0;

///INITIALIZATION

init();

function init() {
	checkIdentifiers()
	if (checkUserCookiesExist()) {
		userLoggedIn();
	} else {
		userLogOut();
	}
}

///INITIALIZATION END

// Connections
//Main connection
async function connection(url, options) {
	const response = await fetch(url, options);
	storedConnectionResponse = response
	console.log('Actual response.status changed to ', storedConnectionResponse.status)
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
	checkIdentifiers()
	let postKEY = (identifier.Mode === 'UTOK')? 'jwtKEY' : 'sessid'
	let endpointForMember = (identifier.Mode === 'UTOK')? memberEndpointJWT : memberEndpointSes
	console.log('Identifier methods :')
	console.log(identifier.Type, postKEY, endpointForMember)
	
	let formData = new FormData();
	formData.append(postKEY, identifier.Value);

	let options = {
		method: "POST",
		body: formData,
		credentials: "include",
		mode: "cors",
		cache: "no-cache"
	};
	return await connection(endpointForMember, options);
}

// Connections END

//PROCESSES
async function loginProcess() {
	let checkedLoginDatas = checkLoginInputDatas();
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
			if (storedLoginDatas.Login === "Failed" || storedConnectionResponse.status === 401) {
				console.log("Login failed");
				showLoginFail();
			}
			if (storedLoginDatas.Login === "Success" || storedConnectionResponse.status === 200) {
				console.log("Login Success");
				createUserCookies();
				removeAllMessages()			
				init();
			}
		}
	}
}

async function registerProcess() {
	console.log("Register process function...");
	let checkRegData = checkRegInputDatas();

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


//PROCESSES END

// CHECKERS
//Login input checker and error message render. Used in: registerProcess()
function checkLoginInputDatas() {
	if (nameField.value === "") {
		errorBoxName.innerHTML = "Name is empty";
		errorBoxName.classList.remove("hidden");
	}

	if (passField.value === "") {
		errorBoxPass.innerHTML = "Password is empty";
		errorBoxPass.classList.remove("hidden");
	}
	
	if (passField.value.length < 8) {
		errorBoxPass.innerHTML = "The password must be 8 characters long ";
		errorBoxPass.classList.remove("hidden");
	}

	if(passField.value != "" && nameField.value != "" && passField.value.length >= 8){
		return true;
	}
}

function checkRegInputDatas() {
	if (regPassField.value.length < 8) {
		errorBoxRegPass.innerHTML = "The register password must be 8 characters long";
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
		getCookie('una') === undefined ||
		!checkIdentifiers()
	) {
		return false;
	} else {
		console.log("User cookies exist.");
		return true;
	}
}

//Check and set identifier
function checkIdentifiers(){
	identifier = false
	
	if(document.cookie.indexOf("UTOK=") >= 10 || getCookie('UTOK') != undefined){
		identifier = {'Type':'Cookie', 'Mode':'UTOK', 'Value': getCookie('UTOK')}
	} else if((localStorage.getItem("UTOK") != null)){
		identifier = {'Type':'localStorage', 'Mode':'UTOK', 'Value': localStorage.getItem("UTOK")}
	} else if((sessionStorage.getItem("UTOK") != null)){
		identifier = {'Type':'sessionStorage', 'Mode':'UTOK', 'Value': sessionStorage.getItem("UTOK")}
	} else 	if(document.cookie.indexOf("ses=") >= 10 || getCookie('ses') != undefined){
		identifier = {'Type':'Cookie', 'Mode':'ses', 'Value': getCookie('ses')}
	} else if((localStorage.getItem("ses") != null)){
		identifier = {'Type':'localStorage', 'Mode':'ses', 'Value': localStorage.getItem("ses")}
	} else if((sessionStorage.getItem("ses") != null)){
		identifier = {'Type':'sessionStorage', 'Mode':'ses', 'Value': sessionStorage.getItem("ses")}
	}
	
	return identifier
}

// CHECKERS END

//RENDER helpers
//Used in: userLoggedIn(), init()
function userLogOut() {
	console.log(identifier)
	profileCOntainer.classList.add("hidden");
	loadingContainer.classList.add("hidden");
	profileCOntainer.innerHTML = "";
	deleteUserCookies();
	formsDiv.classList.remove("hidden");
	if(loadCounter != 1){console.log("User logged out ...")}
}

//Used in: loginProcess(), init()
async function userLoggedIn() {
	let profile = await connectForUserDatas();
	console.log("User Profile Datas : ");
	console.log(profile);

	if (profile.User === "DoesnotExist" && profile.UserName === "Failed") {
		errorBoxCommon.innerHTML = "User session expired! Login again";
		errorBoxCommon.classList.remove("hidden");
		profile = false;
		userLogOut();
		return;
	}

	if (profile) {
		formsDiv.classList.add("hidden");
		loadingContainer.classList.add("hidden");
		regSuccessBox.classList.add("hidden");
		

		profileCOntainer.innerHTML = `
		<h1>WELCOME HERE <red style="color: red"><b> ${profile.UserName} </b></red> !</h1>
		<p><img src="${(profile.UserAvatar != null || profile.UserAvatar === 'undefined')?profile.UserAvatar:defaultAvatar}" title="${profile.UserName}'s avatar" alt="avatar" width="250" height="250"/></p>
		<p><b>Registed At :</b>  ${profile.UserRegistredAt.replace(/-/g, ". ").splice(12, '.')}</p><!-- Splice helper!! -->
		<p><b>Logined At :</b>  ${new Date(profile.CreatedTimeStamp * 1000).toLocaleString()}</p>
		<p><b>Login will be expired At :</b>  ${new Date(profile.ExpiredTimeStamp * 1000).toLocaleString()}</p>
		<p><b>User Score :</b> ${profile.UserScore}</p>
		<p><a target="_blank" href="https://codepen.io/bzozoo/full/VwmKOVj">
		<button class="linkbuttons">SCORE TABLE</button></a></p>
		<p><div class="buttoncontainer">
		<input class="actionbuttons" id="logoutButton" name="logoutButton" type="button" value="LOGOUT" onclick="userLogOut()">
		</div>
		</p>`;
	
		profileCOntainer.classList.remove("hidden");
		console.log('Profile Container was generated')
	}

	
}

function showLoginFail() {
	errorBoxCommon.innerHTML = "Wrong username or password";
	errorBoxCommon.classList.remove("hidden");
}

function removeAllMessages() {
	errorBoxes.forEach(function (errorBox) {
		errorBox.innerHTML = "";
		errorBox.classList.add("hidden");
	});
	successBoxes.forEach(function (successBox) {
		successBox.classList.add("hidden");
	});
	console.log('All error messages was deleted')
}


//Userd in: registerProcess()
function regFormMessages(storedRegDatas){
	if(storedRegDatas){
		if(storedConnectionResponse.status === 409 || storedRegDatas.Registration === "Failed"){
			let userexistMessage = (storedRegDatas.UserExisted === "YES")? 'User already exist!' : 'Something wrong!';
			let regErrorMessage = 'Registration failed!'
			errorBoxReg.innerHTML = userexistMessage + ' ' + regErrorMessage
			errorBoxReg.classList.remove("hidden");
			regProgressBox.classList.add("hidden");
			regSuccessBox.classList.add("hidden");	
		}


		if (storedConnectionResponse.status === 201 && storedRegDatas.UserExisted === "NO" && storedRegDatas.Registration === "Success"){
			console.log("Registration success!");
			regSuccessBox.innerHTML = 'Registration Success! You can login!'
			errorBoxReg.classList.add("hidden");
			regSuccessBox.classList.remove("hidden");
			regProgressBox.classList.add("hidden");
		}
	}
}

//RENDER helpers END

//Cookie handlers
function createUserCookies() {
	let stayloggedcheck = document.querySelector("#stayloggedcheck");
	var now = new Date();
	var time = now.getTime();
	var expireTime = time + 30 * 24 * 60 * 60 *1000;
	now.setTime(expireTime);

	let expiration = stayloggedcheck.checked
		? `expires=${now.toUTCString()};`
		: "";
	console.log(`Cookie expirations : ${expiration}`);
	
	//Create cookies
	document.cookie = `una=${storedLoginDatas.UserName}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `UTOK=${storedLoginDatas.UTOK}; ${expiration} path=/; SameSite=None; Secure`;
	document.cookie = `ses=${storedLoginDatas.SessionId}; ${expiration} path=/; SameSite=None; Secure`;
	if(stayloggedcheck.checked){
		document.cookie = `sescrea=${time}; ${expiration} path=/; SameSite=None; Secure`;
		document.cookie = `sesexp=${expiration}; ${expiration} path=/; SameSite=None; Secure`;
	}
	
	//Save to sessionStorage
	if(!stayloggedcheck.checked){ 
		sessionStorage.setItem('una', storedLoginDatas.UserName)
		sessionStorage.setItem('UTOK', storedLoginDatas.UTOK)
		sessionStorage.setItem('ses', storedLoginDatas.SessionId)
		sessionStorage.setItem('ses-exp', expireTime)
	}
	
	//Save to localStorage
	if(stayloggedcheck.checked){ 
		localStorage.setItem('una', storedLoginDatas.UserName)
		localStorage.setItem('UTOK', storedLoginDatas.UTOK)
		localStorage.setItem('ses', storedLoginDatas.SessionId)
		localStorage.setItem('ses-crea', time)
		localStorage.setItem('ses-exp', expireTime)
	}
}

//Used in: userLogOut()
function deleteUserCookies() {
	//Cookies
	document.cookie = `PHPSESSID=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `una=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `UTOK=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	document.cookie = `ses=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=None; Secure`;
	
	//sessionStorage-s
	sessionStorage.removeItem('una')
	sessionStorage.removeItem('UTOK')
	sessionStorage.removeItem('ses')
	
	//localStorage  -s
	localStorage.removeItem('una')
	localStorage.removeItem('UTOK')
	localStorage.removeItem('ses')
	localStorage.removeItem('ses-crea')
	localStorage.removeItem('ses-exp')
	
	//Identifier init
	identifier = false
	
	
	loadCounter += 1
	if(loadCounter != 1){console.log('All user cookies was deleted!')}
}

function getCookie(name) {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(";").shift();
}

//Cookie handlers END

//GENERAL helpers

// String splice helper
if (String.prototype.splice === undefined) {
  /**
   * Splices text within a string.
   * @param {int} offset The position to insert the text at (before)
   * @param {string} text The text to insert
   * @param {int} [removeCount=0] An optional number of characters to overwrite
   * @returns {string} A modified string containing the spliced text.
   */
  String.prototype.splice = function(offset, text, removeCount=0) {
    let calculatedOffset = offset < 0 ? this.length + offset : offset;
    return this.substring(0, calculatedOffset) +
      text + this.substring(calculatedOffset + removeCount);
  };
}

function elementChildren(element) {
    var childNodes = element.childNodes,
        children = [],
        i = childNodes.length;

    while (i--) {
        if (childNodes[i].nodeType == 1) {
            children.unshift(childNodes[i]);
        }
    }

    return children;
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
	inputBox.addEventListener("input", removeAllMessages);
});

submitButton.addEventListener("click", loginProcess);
regSubmitButton.addEventListener("click", registerProcess);
//EVENT listeners END