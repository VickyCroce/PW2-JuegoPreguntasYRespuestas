var h1 = document.getElementsByTagName('h1')[0];
var t;

function tick() {
    sec--;
}
function add() {
    tick();
    h1.textContent = (sec > 9 ? sec : '0' + sec);
    // Verifica si el tiempo ha llegado a cero
    if (sec <= 0) {
        clearTimeout(t); // Detiene el temporizador
        alert("Tiempo agotado. Tu puntaje final es" +""+ puntuacion);
        // Redirige a la página principal
        window.location.href = "/PW2-JuegoPreguntasYRespuestas/ControllerHome/get";
    } else {
        timer();
    }
}
function timer() {
    t = setTimeout(add, 1000);
}

timer();

