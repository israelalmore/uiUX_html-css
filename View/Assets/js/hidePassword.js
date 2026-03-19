function togglePassword(button) {
  const inputId = button.getAttribute("aria-controls");
  const input = document.getElementById(inputId);

  const isVisible = button.getAttribute("aria-pressed") === "true";

  input.type = isVisible ? "password" : "text";

  button.setAttribute("aria-pressed", String(!isVisible));
  button.setAttribute(
    "aria-label",
    isVisible ? "Mostrar contraseña" : "Ocultar contraseña",
  );
}
