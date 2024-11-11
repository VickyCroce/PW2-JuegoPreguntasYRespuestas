// Función para manejar el temporizador
let tiempoRestante = 20;

function iniciarTemporizador() {
    const contador = document.getElementById('contador_tiempo');
    const interval = setInterval(() => {
        if (tiempoRestante <= 0) {
            clearInterval(interval);
            alert("¡Se acabó el tiempo!");
        } else {
            contador.textContent = tiempoRestante;
            tiempoRestante--;
        }
    }, 1000);
}

// Inicializa el temporizador al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    iniciarTemporizador();
});
