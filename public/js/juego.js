// abrir_cerrar_ventana.js

export function abrirVentana(selector) {
    const ventana = document.querySelector(selector);
    if (ventana) {
        ventana.style.display = 'block'; // O 'flex', dependiendo del estilo que estés usando
    }
}

export function cerrarVentana(selector) {
    const ventana = document.querySelector(selector);
    if (ventana) {
        ventana.style.display = 'none';
    }
}

// juego.js

// Importar funciones para abrir y cerrar ventanas
import { abrirVentana, cerrarVentana } from "./abrir_cerrar_ventana.js";

// Función para manejar el temporizador
let tiempoRestante = 20; // Tiempo en segundos

function iniciarTemporizador() {
    const contador = document.getElementById('contador_tiempo');
    const interval = setInterval(() => {
        if (tiempoRestante <= 0) {
            clearInterval(interval);
            // Lógica para cuando el tiempo se acaba, puedes redirigir o mostrar un mensaje
            alert("¡Se acabó el tiempo!");
            // Aquí podrías llamar a la función que maneje la respuesta automática
            // manejarRespuestaAutomaticamente();
        } else {
            contador.textContent = tiempoRestante;
            tiempoRestante--;
        }
    }, 1000);
}

// Inicializa el temporizador al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    iniciarTemporizador();

    // Manejador de eventos para abrir y cerrar el popup de reportar
    document.addEventListener("click", (e) => {
        if (e.target.matches("#reportar__cerrar") || e.target.matches("#reportar__cerrar *")) {
            cerrarVentana(".pop__up__reportar");
        }

        if (e.target.matches("#boton__reportar") || e.target.matches("#boton__reportar *")) {
            abrirVentana(".pop__up__reportar");
        }
    });
});
