/* Carosello */

function aggiornaCarosello(e) {
	var caller = e.target || e.srcElement;
	var nav = caller.parentElement.getElementsByClassName("caroselloDots")[0];
	var current = Math.round(caller.scrollLeft/caller.scrollWidth * nav.children.length);

	for (var i = 0; i < nav.children.length; i++) { 
		nav.children[i].children[0].disabled = current == i;
	}
}

function prevCarosello(e) {
	var caller = e.target || e.srcElement;
	var nav = caller.parentElement.getElementsByClassName("caroselloDots")[0];
	var slides = caller.parentElement.getElementsByClassName("slides")[0];
	var current = Math.round(slides.scrollLeft/slides.scrollWidth * nav.children.length);

	current = current > 0 ? current - 1 : nav.children.length - 1;
	slides.scrollLeft = current * slides.children[0].offsetWidth;
}

function dotsCarosello(e, id) {
	var caller = e.target || e.srcElement;
	var nav = caller.parentElement.parentElement.parentElement;
	var slides = caller.parentElement.parentElement.parentElement.getElementsByClassName("slides")[0];

	slides.scrollLeft = (id-1) * slides.children[0].offsetWidth;
}

function nextCarosello(e) {
	var caller = e.target || e.srcElement;
	var nav = caller.parentElement.getElementsByClassName("caroselloDots")[0];
	var slides = caller.parentElement.getElementsByClassName("slides")[0];
	var current = Math.round(slides.scrollLeft/slides.scrollWidth * nav.children.length);

	current = current < nav.children.length - 1 ? current + 1 : 0;
	slides.scrollLeft = current * slides.children[0].offsetWidth;
}

/* Hamburger */

var hamStatus = "false";
function hambToggle() {
	var ls = document.getElementsByClassName("hamToggle");
	hamStatus = hamStatus == "false" ? "true" : "false";
	for (var i = 0; i < ls.length; i++) {
		ls[i].setAttribute("data-hambOn", hamStatus);
		// Questo non fa nulla se non far capire a IE 10 che deve aggiornare il rendering...
		var tmp = ls[i].style.display;
		ls[i].style.display = "none";
		ls[i].style.display = tmp;
	}
}

/* Validazione */

var valid = {
	"user_login" : ["regex", /^.{1,}$/, "user-login", "Inserire l'<span lang=\"en\">email</span>"],
	"pass_old" : ["regex", /^.{1,}$/, "pass-old", "Inserire la <span lang=\"en\">password</span>"],
	"user" : ["regex", /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/, "user-error", "<span lang=\"en\">email</span> non valida"],
	"pass" : ["regex", /^.{8,32}$/, "pass-error", "La <span lang=\"en\">password</span> deve essere lunga tra gli 8 e i 32 caratteri"],
	"pass_rep" : ["same", "pass", "pass-rep-error", "Le 2 <span lang=\"en\">password</span> devono coincidere"],
	"fname" : ["regex", /^[a-zA-Z\u00C0-\u00FF'][a-zA-Z\s\u00C0-\u00FF']*$/, "name-error", "Inserire un nome valido (solo lettere)"],
	"fsurname" : ["regex", /^[a-zA-Z\u00C0-\u00FF'][a-zA-Z\s\u00C0-\u00FF']*$/, "surname-error", "Inserire un cognome valido (solo lettere)"],
	"fphone" : ["regex", /^\+?\s?\d+[\s\d\-\.\(\)]*$/, "phone-error", "Inserire un numero di telefono valido"],
	"fmex" : ["regex", /^.{1,1000}$/, "mex-error", "Inserire un messaggio (massimo 1000 caratteri)"],
	"nome" : ["regex", /^.{1,64}$/, "nome-err", "Inserire un nome (massimo 64 caratteri)"],
	"descrizione" : ["regex", /^.{1,5000}$/, "descrizione-err", "Inserire una descrizione (massimo 5000 caratteri)"],
	"img_upload" : ["file", /^.*\.(PNG|png|jpeg|JPEG|jpg|JPG|gif|GIF)$/, "img-up-err", "Caricare un'immagine in formato <abbr lang=\"en\" title=\"Portable Network Graphics\">PNG</abbr>, <abbr lang=\"en\" title=\"Joint Photographic Experts Group\">JPG</abbr> o <abbr lang=\"en\" title=\"Graphics Interchange Format\">GIF</abbr>"],
	"banner_upload" : ["file", /^.*\.(PNG|png|jpeg|JPEG|jpg|JPG|gif|GIF)$/, "banner-err", "Caricare un <span lang=\"en\">banner</span> in formato <abbr lang=\"en\" title=\"Portable Network Graphics\">PNG</abbr>, <abbr lang=\"en\" title=\"Joint Photographic Experts Group\">JPG</abbr> o <abbr lang=\"en\" title=\"Graphics Interchange Format\">GIF</abbr>"],
	"alt" : ["regex", /^.{1,32}$/, "alt-err", "Inserire una descrizione alternativa all'immagine (massimo 32 caratteri)"],
};

function validateFile(id) {
	var handle = document.getElementById(id);
	var pos = 1;
	if (handle.files.length > 0) {
		pos = handle.files[0].name.search(valid[id][1]);
	}
	var err = document.getElementById(valid[id][2])

	if (pos == 0) {
		handle.removeAttribute("aria-invalid");
		handle.removeAttribute("aria-describedby");
		err.removeAttribute("role");
		err.style.display = "none";
		return true;
	} else {
		handle.setAttribute("aria-invalid", "true");
		handle.setAttribute("aria-describedby", valid[id][2]);
		err.style.display = "block";
		err.setAttribute("role", "alert");
		err.innerHTML = valid[id][3];
		return false;
	}
}

function validateRegex(id) {
	var handle = document.getElementById(id);
	var pos = handle.value.trim().search(valid[id][1]);
	var err = document.getElementById(valid[id][2])

	if (pos == 0) {
		handle.removeAttribute("aria-invalid");
		handle.removeAttribute("aria-describedby");
		err.removeAttribute("role");
		err.style.display = "none";
		return true;
	} else {
		handle.setAttribute("aria-invalid", "true");
		handle.setAttribute("aria-describedby", valid[id][2]);
		err.style.display = "block";
		err.setAttribute("role", "alert");
		err.innerHTML = valid[id][3];
		return false;
	}
}

function validateRep(id) {
	var pass = document.getElementById(id);
	var err = document.getElementById(valid[id][2]);
	if (pass.value != document.getElementById(valid[id][1]).value) {
		pass.setAttribute("aria-invalid", "true");
		pass.setAttribute("aria-describedby", valid[id][2]);
		err.style.display = "block";
		err.setAttribute("role", "alert");
		err.innerHTML = valid[id][3];
		return false;
	} else {
		pass.removeAttribute("aria-invalid");
		pass.removeAttribute("aria-describedby");
		err.removeAttribute("role");
		err.style.display = "none";
		return true;
	}
}

function validate(id) {
	if (valid[id][0] == "regex") {
		return validateRegex(id);
	} else if (valid[id][0] == "same") {
		return validateRep(id);
	} else if (valid[id][0] == "file") {
		return validateFile(id);
	}
}

function closePop() {
	document.getElementById("confirmPop").style.display = "none";
	document.getElementById("elimina").focus();
}

function showConfirm() {
	document.getElementById("confirmPop").style.display = "block";
	document.getElementById("annulla").focus();
}

function validatorCheckAll() {
	if (document.activeElement.id == "del") {
		return true;
	}
	for (var key in valid) {
		if (!document.getElementById(key)) {
			continue;
		}
		if (document.getElementById(key).getAttribute("data-not-required")) {
			continue;
		}
		if (!validate(key)) {
			document.getElementById(key).focus();
			return false;
		}
	}
	return true;
}

function validatorLoad() {
	for (var key in valid) {
		if (!document.getElementById(key)) {
			continue;
		}
	
		document.getElementById(valid[key][2]).style.display = "none";

		if (document.getElementById(key).getAttribute("data-not-required")) {
			continue;
		}

		document.getElementById(key).onchange = function(e) {
			var caller = e.target || e.srcElement;
 			validate(caller.id);
		}

		document.getElementById(key).oninput = function(e) {
			var caller = e.target || e.srcElement;
 			if (document.getElementById(valid[caller.id][2]).style.display != "none" && document.getElementById(valid[caller.id][2]).innerHTML != "") {
				validate(caller.id);
			}
		}

		if (valid[key][0] == "same") {
			document.getElementById(valid[key][1]).oninput = function(e) {
				var caller = e.target || e.srcElement;
		 		if (document.getElementById(valid[caller.id][2]).style.display != "none" && document.getElementById(valid[caller.id][2]).innerHTML != "") {
					validate(caller.id);
				}
			}
		}
	}

	var form = document.getElementById("validatedForm");
	if (form) {
		form.onsubmit = function(event) {
			return validatorCheckAll();
		}
	}
}

/* Form contatti */
function send_email() {
	for (var key in valid) {
		if (!document.getElementById(key)) {
			continue;
		}
		if (document.getElementById(key).getAttribute("data-not-required")) {
			continue;
		}
		if (!validate(key)) {
			document.getElementById(key).focus();
			return;
		}
	}

	var oggetto = "Richiesta da parte di" + " " + document.getElementById('fname').value.trim() + " " + document.getElementById('fsurname').value.trim();
	var message = document.getElementById('fmex').value;
	var phone = document.getElementById('fphone').value.trim();

	oggetto = encodeURIComponent(oggetto);
	message = encodeURIComponent(message);
	phone = encodeURIComponent(phone);

	location.href = 'mailto:' + 'info@bonaegava.com' + '?Subject=' + oggetto + '&Body=' + message + '%0D%0A%0D%0A'+encodeURIComponent('Mi può contattare al seguente numero: ') + phone;
}