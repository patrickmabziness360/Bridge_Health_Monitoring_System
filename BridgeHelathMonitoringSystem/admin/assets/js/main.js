(function () {
  /* ========= sidebar toggle ======== */
  const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
  const mainWrapper = document.querySelector(".main-wrapper");
  const menuToggleButton = document.querySelector("#menu-toggle");
  const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
  const overlay = document.querySelector(".overlay");

  menuToggleButton.addEventListener("click", () => {
    sidebarNavWrapper.classList.toggle("active");
    overlay.classList.add("active");
    mainWrapper.classList.toggle("active");

    if (document.body.clientWidth > 1200) {
      if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {
        menuToggleButtonIcon.classList.remove("lni-chevron-left");
        menuToggleButtonIcon.classList.add("lni-menu");
      } else {
        menuToggleButtonIcon.classList.remove("lni-menu");
        menuToggleButtonIcon.classList.add("lni-chevron-left");
      }
    } else {
      if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {
        menuToggleButtonIcon.classList.remove("lni-chevron-left");
        menuToggleButtonIcon.classList.add("lni-menu");
      }
    }
  });
  overlay.addEventListener("click", () => {
    sidebarNavWrapper.classList.remove("active");
    overlay.classList.remove("active");
    mainWrapper.classList.remove("active");
  });

  // ========== theme switcher ==========
  const optionButton = document.querySelector(".option-btn");
  const optionButtonClose = document.querySelector(".option-btn-close");
  const optionBox = document.querySelector(".option-box");
  const optionOverlay = document.querySelector(".option-overlay");

  optionButton.addEventListener("click", () => {
    optionBox.classList.add("show");
    optionOverlay.classList.add("show");
  });
  optionButtonClose.addEventListener("click", () => {
    optionBox.classList.remove("show");
    optionOverlay.classList.remove("show");
  });
  optionOverlay.addEventListener("click", () => {
    optionOverlay.classList.remove("show");
    optionBox.classList.remove("show");
  });

  // ========== layout change
  const leftSidebarButton = document.querySelector(".leftSidebarButton");
  const rightSidebarButton = document.querySelector(".rightSidebarButton");
  const dropdownMenuEnd = document.querySelectorAll(
    ".header-right .dropdown-menu"
  );

  rightSidebarButton.addEventListener("click", () => {
    document.body.classList.add("rightSidebar");
    rightSidebarButton.classList.add("active");
    leftSidebarButton.classList.remove("active");

    dropdownMenuEnd.forEach((el) => {
      el.classList.remove("dropdown-menu-end");
    });
  });
  leftSidebarButton.addEventListener("click", () => {
    document.body.classList.remove("rightSidebar");
    leftSidebarButton.classList.add("active");
    rightSidebarButton.classList.remove("active");

    dropdownMenuEnd.forEach((el) => {
      el.classList.add("dropdown-menu-end");
    });
  });

  // =========== theme change
  const lightThemeButton = document.querySelector(".lightThemeButton");
  const lightThemeButton2 = document.querySelector(".lightThemeButton2");
  const darkThemeButton = document.querySelector(".darkThemeButton");
  const darkThemeButton2 = document.querySelector(".darkThemeButton2");
  const logo = document.querySelector(".navbar-logo img");

  darkThemeButton.addEventListener("click", () => {
    document.body.classList.add("darkTheme");
    sidebarNavWrapper.classList.remove("style-2");
    darkThemeButton.classList.add("active");
    darkThemeButton2.classList.remove("active");
    lightThemeButton.classList.remove("active");
    lightThemeButton2.classList.remove("active");
    logo.src = "assets/images/logo/logo-white.svg";
  });

  darkThemeButton2.addEventListener("click", () => {
    document.body.classList.add("darkTheme");
    sidebarNavWrapper.classList.add("style-2");
    darkThemeButton2.classList.add("active");
    darkThemeButton.classList.remove("active");
    lightThemeButton.classList.remove("active");
    lightThemeButton2.classList.remove("active");
    logo.src = "assets/images/logo/logo-white.svg";
  });
  lightThemeButton.addEventListener("click", () => {
    document.body.classList.remove("darkTheme");
    sidebarNavWrapper.classList.remove("style-2");
    lightThemeButton.classList.add("active");
    lightThemeButton2.classList.remove("active");
    darkThemeButton.classList.remove("active");
    darkThemeButton2.classList.remove("active");
    logo.src = "assets/images/logo/logo.svg";
  });
  lightThemeButton2.addEventListener("click", () => {
    document.body.classList.remove("darkTheme");
    sidebarNavWrapper.classList.add("style-2");
    lightThemeButton2.classList.add("active");
    lightThemeButton.classList.remove("active");
    darkThemeButton.classList.remove("active");
    darkThemeButton2.classList.remove("active");
    logo.src = "assets/images/logo/logo.svg";
  });

  // Enabling bootstrap tooltips
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
})();
