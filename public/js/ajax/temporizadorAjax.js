// var h1 = document.getElementsByTagName('h1')[0];
// var t;
//
// function tick() {
//     sec--;
// }
// function add() {
//     tick();
//     h1.textContent = (sec > 9 ? sec : '0' + sec);
//     // Verifica si el tiempo ha llegado a cero
//     if (sec <= 0) {
//         clearTimeout(t); // Detiene el temporizador
//         alert("Tiempo agotado. Tu puntaje final es" +""+ puntuacion);
//         // Redirige a la página principal
//         window.location.href = "/PW2-JuegoPreguntasYRespuestas/ControllerHome/get";
//     } else {
//         timer();
//     }
// }
// function timer() {
//     t = setTimeout(add, 1000);
// }
//
// timer();

let tiempoRestante = 30;

function iniciarTemporizador() {
    const contador = document.getElementById('contador_tiempo');
    const interval = setInterval(() => {
        contador.textContent = tiempoRestante;

        if (tiempoRestante <= 0) {
            clearInterval(interval);
            alert("Tiempo agotado. Tu puntaje final es: " + puntuacion);
            window.location.href = "/PW2-JuegoPreguntasYRespuestas/ControllerHome/get";
        } else {
            tiempoRestante--;
        }
    }, 1000);
}

document.addEventListener("DOMContentLoaded", iniciarTemporizador);


//
// document.addEventListener("DOMContentLoaded", function () {
//     let tiempoRestante = parseInt(document.getElementById("tiempo-restante").textContent, 10);
//
//     const intervalo = setInterval(() => {
//         tiempoRestante -= 1;
//         document.getElementById("tiempo-restante").textContent = tiempoRestante;
//
//         if (tiempoRestante <= 0) {
//             clearInterval(intervalo);
//             window.location.href = "/ruta-finalizacion-tiempo";  // Redirige cuando el tiempo termine
//         }
//     }, 1000);
// });

