const votarButtons = document.querySelectorAll(".votar");

votarButtons.forEach((button) => {
    button.addEventListener("click", () => {
        alert("¡Gracias por tu voto!");
        // Aquí puedes agregar la lógica para enviar el voto al servidor utilizando AJAX.
    });
});
