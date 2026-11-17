/* ==========================================================================
   Student Hub — shared page behaviour
   Navigation, scroll state, and the accessible lab tabs.
   ========================================================================== */

(() => {
  "use strict";

  const header = document.querySelector("[data-site-header]");
  const navToggle = document.getElementById("nav-toggle");
  const siteNav = document.getElementById("site-nav");
  const navLinks = [...document.querySelectorAll(".nav-link")];
  const sections = [...document.querySelectorAll("main section[id]")];

  /* Sticky header + active section link ---------------------------------- */

  let scrollTicking = false;

  const updatePageState = () => {
    if (header) {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
    }

    if (sections.length === 0) {
      return;
    }

    const marker = window.scrollY + Math.min(180, window.innerHeight * 0.25);
    let current = sections[0].id;

    sections.forEach((section) => {
      if (section.offsetTop <= marker) {
        current = section.id;
      }
    });

    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 4) {
      current = sections[sections.length - 1].id;
    }

    navLinks.forEach((link) => {
      const isCurrent = link.dataset.nav === current;
      link.classList.toggle("is-active", isCurrent);

      if (isCurrent) {
        link.setAttribute("aria-current", "page");
      } else {
        link.removeAttribute("aria-current");
      }
    });
  };

  const requestPageUpdate = () => {
    if (scrollTicking) {
      return;
    }

    scrollTicking = true;
    window.requestAnimationFrame(() => {
      updatePageState();
      scrollTicking = false;
    });
  };

  updatePageState();
  window.addEventListener("scroll", requestPageUpdate, { passive: true });
  window.addEventListener("resize", requestPageUpdate);

  /* Mobile navigation ----------------------------------------------------- */

  const closeNav = () => {
    if (!navToggle || !siteNav) {
      return;
    }

    navToggle.setAttribute("aria-expanded", "false");
    siteNav.classList.remove("is-open");
  };

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", () => {
      const isOpen = navToggle.getAttribute("aria-expanded") === "true";
      navToggle.setAttribute("aria-expanded", String(!isOpen));
      siteNav.classList.toggle("is-open", !isOpen);
    });

    navLinks.forEach((link) => link.addEventListener("click", closeNav));

    document.addEventListener("click", (event) => {
      if (!siteNav.classList.contains("is-open")) {
        return;
      }

      if (!siteNav.contains(event.target) && !navToggle.contains(event.target)) {
        closeNav();
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeNav();
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 900) {
        closeNav();
      }
    });
  }

  /* Lab tabs -------------------------------------------------------------- */

  const tabs = [...document.querySelectorAll("[data-lab-tab]")];
  const panels = [...document.querySelectorAll(".lab-panel")];

  if (tabs.length > 0 && panels.length > 0) {
    const activateTab = (tab, shouldFocus = false) => {
      const name = tab.dataset.labTab;
      const target = document.getElementById(`tab-${name}`);

      if (!target) {
        return;
      }

      tabs.forEach((item) => {
        const isSelected = item === tab;
        item.classList.toggle("is-active", isSelected);
        item.setAttribute("aria-selected", String(isSelected));
        item.tabIndex = isSelected ? 0 : -1;
      });

      panels.forEach((panel) => {
        panel.hidden = panel !== target;
      });

      if (shouldFocus) {
        tab.focus();
      }
    };

    tabs.forEach((tab, index) => {
      tab.tabIndex = index === 0 ? 0 : -1;

      tab.addEventListener("click", () => activateTab(tab));

      tab.addEventListener("keydown", (event) => {
        const currentIndex = tabs.indexOf(tab);
        let nextIndex = currentIndex;

        if (event.key === "ArrowRight") {
          nextIndex = (currentIndex + 1) % tabs.length;
        } else if (event.key === "ArrowLeft") {
          nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        } else if (event.key === "Home") {
          nextIndex = 0;
        } else if (event.key === "End") {
          nextIndex = tabs.length - 1;
        } else {
          return;
        }

        event.preventDefault();
        activateTab(tabs[nextIndex], true);
      });
    });

    activateTab(tabs.find((tab) => tab.classList.contains("is-active")) || tabs[0]);
  }

  /* Small footer detail --------------------------------------------------- */

  const year = document.getElementById("current-year");

  if (year) {
    year.textContent = String(new Date().getFullYear());
  }
})();
