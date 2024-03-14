// Funktion zum Setzen des Cookie-Werts
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Funktion zum Auslesen des Cookie-Werts
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Funktion zum Überprüfen, ob ein Cookie existiert
function checkCookie(name) {
    var cookieValue = getCookie(name);
    return cookieValue !== null && cookieValue !== "";
}

// Wenn ein Cookie für die Auswahl vorhanden ist, setze die Auswahl entsprechend
window.onload = function() {
    var option1Button = document.getElementById("option1");
    var option2Button = document.getElementById("option2");
    var option3Button = document.getElementById("option3");
    var option4Button = document.getElementById("option4");
    var option5Button = document.getElementById("option5");

    option1Button.addEventListener("click", function() {
        setSelection("option1");
    });

    option2Button.addEventListener("click", function() {
        setSelection("option2");
    });

    option3Button.addEventListener("click", function() {
        setSelection("option3");
    });

    option4Button.addEventListener("click", function() {
        setSelection("option4");
    });

    option5Button.addEventListener("click", function() {
        setSelection("option5");
    });

    // Überprüfe, ob ein Cookie für die Auswahl vorhanden ist
    var selectionCookie = getCookie("selectedOption");
    if (selectionCookie) {
        // Setze die Auswahl entsprechend
        document.getElementById(selectionCookie).classList.add("selected");
    }
};

// Funktion zum Setzen der Auswahl und des Cookies
function setSelection(optionId) {
    // Entferne zuerst die Auswahl von allen Optionen
    var buttons = document.querySelectorAll("button");
    buttons.forEach(function(button) {
        button.classList.remove("selected");
    });

    // Füge die Auswahl dem ausgewählten Button hinzu
    var selectedButton = document.getElementById(optionId);
    selectedButton.classList.add("selected");

    // Setze das Cookie für die Auswahl
    setCookie("selectedOption", optionId, 30); // Cookie für 30 Tage speichern
}