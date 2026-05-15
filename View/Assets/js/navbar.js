(function () {
  const header = document.querySelector(".nav-bar");
  if (!header) return;

  const toggleBtn = header.querySelector(".mobile-nav-icon");
  const navContainer = header.querySelector(".nav-container");
  if (!toggleBtn || !navContainer) return;

  const toggleIcon = toggleBtn.querySelector("i");
  const mobileBreakpoint = window.matchMedia("(min-width: 1024px)");

  // Crea el backdrop una sola vez y lo inyecta tras el header
  let backdrop = document.querySelector(".nav-backdrop");
  if (!backdrop) {
    backdrop = document.createElement("button");
    backdrop.type = "button";
    backdrop.className = "nav-backdrop";
    backdrop.setAttribute("aria-label", "Cerrar menú");
    backdrop.setAttribute("tabindex", "-1");
    header.insertAdjacentElement("afterend", backdrop);
  }

  if (!navContainer.id) navContainer.id = "primary-nav";
  toggleBtn.setAttribute("aria-expanded", "false");
  toggleBtn.setAttribute("aria-controls", navContainer.id);

  const setIcon = (open) => {
    if (!toggleIcon) return;
    toggleIcon.classList.toggle("fa-bars", !open);
    toggleIcon.classList.toggle("fa-xmark", open);
  };

  const openMenu = () => {
    header.classList.add("nav-active");
    backdrop.classList.add("is-visible");
    toggleBtn.setAttribute("aria-expanded", "true");
    toggleBtn.setAttribute("aria-label", "Cerrar menú");
    document.body.classList.add("nav-locked");
    setIcon(true);
  };

  const closeMenu = () => {
    header.classList.remove("nav-active");
    backdrop.classList.remove("is-visible");
    toggleBtn.setAttribute("aria-expanded", "false");
    toggleBtn.setAttribute("aria-label", "Abrir menú");
    document.body.classList.remove("nav-locked");
    setIcon(false);
  };

  toggleBtn.addEventListener("click", () => {
    if (header.classList.contains("nav-active")) closeMenu();
    else openMenu();
  });

  backdrop.addEventListener("click", closeMenu);

  navContainer.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      if (!mobileBreakpoint.matches) closeMenu();
    });
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && header.classList.contains("nav-active")) {
      closeMenu();
      toggleBtn.focus();
    }
  });

  mobileBreakpoint.addEventListener("change", (e) => {
    if (e.matches) closeMenu();
  });
})();
