/**
 * @file
 * plexii behaviors.
 */
(function (Drupal) {
  "use strict";

  Drupal.behaviors.plexii = {
    attach(context, settings) {
      // ── Mobile menu toggle ──────────────────────────────────
      var toggle = document.querySelector(".site-header .menu-toggle");
      var menu = document.getElementById("main-menu");
      var overlay = document.querySelector(".menu-overlay");
      var body = document.body;

      function openMenu() {
        toggle.setAttribute("aria-expanded", "true");
        menu.classList.add("is-open");
        if (overlay) overlay.classList.add("is-active");
        body.style.overflow = "hidden";
      }

      function closeMenu() {
        toggle.setAttribute("aria-expanded", "false");
        menu.classList.remove("is-open");
        if (overlay) overlay.classList.remove("is-active");
        body.style.overflow = "";
      }

      if (toggle && menu) {
        toggle.addEventListener("click", function () {
          var isOpen = toggle.getAttribute("aria-expanded") === "true";
          isOpen ? closeMenu() : openMenu();
        });
      }

      if (overlay) {
        overlay.addEventListener("click", closeMenu);
      }

      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && menu && menu.classList.contains("is-open")) {
          closeMenu();
        }
      });

      // ── Sticky header ──────────────────────────────────────
      var stickyWrapper = document.querySelector(".header-sticky-wrapper");
      var headerTop = document.querySelector(".header-top");

      if (stickyWrapper && headerTop) {
        var spacer = document.createElement("div");
        spacer.className = "sticky-spacer";
        stickyWrapper.parentNode.insertBefore(
          spacer,
          stickyWrapper.nextSibling,
        );

        var ticking = false;

        function updateSticky() {
          var topBottom = headerTop.getBoundingClientRect().bottom;
          if (topBottom <= 0) {
            if (!stickyWrapper.classList.contains("is-stuck")) {
              spacer.style.height = stickyWrapper.offsetHeight + "px";
              spacer.style.display = "block";
              stickyWrapper.classList.add("is-stuck");
            }
          } else {
            if (stickyWrapper.classList.contains("is-stuck")) {
              spacer.style.display = "none";
              stickyWrapper.classList.remove("is-stuck");
            }
          }
          ticking = false;
        }

        window.addEventListener(
          "scroll",
          function () {
            if (!ticking) {
              requestAnimationFrame(updateSticky);
              ticking = true;
            }
          },
          { passive: true },
        );

        updateSticky();
      }

      // ── Commodity ticker: clone for seamless loop ──────────
      var tickerContent = document.querySelector(".ticker-content");
      if (tickerContent) {
        var clone = tickerContent.cloneNode(true);
        clone.setAttribute("aria-hidden", "true");
        tickerContent.parentNode.appendChild(clone);
      }

      // ── News carousel ───────────────────────────────────────
      var carouselViewport = document.getElementById("news-carousel");
      var carouselTrack =
        carouselViewport &&
        carouselViewport.querySelector(".news-carousel-track");

      if (carouselViewport && carouselTrack) {
        var baseCards = Array.from(
          carouselTrack.querySelectorAll(".news-card"),
        );
        var totalBase = baseCards.length;
        var currentIndex = 0;
        var isTransitioning = false;
        var carouselTimer = null;

        function getVisible() {
          var w = window.innerWidth;
          if (w <= 600) return 1;
          if (w <= 992) return 2;
          return 3;
        }

        // Distance to advance per step (card width + gap) read from live DOM
        function getStepWidth() {
          var first = baseCards[0];
          var second = baseCards[1];
          if (!first || !second) return first ? first.offsetWidth : 0;
          return (
            second.getBoundingClientRect().left -
            first.getBoundingClientRect().left
          );
        }

        function buildClones() {
          carouselTrack
            .querySelectorAll(".news-card--clone")
            .forEach(function (c) {
              c.remove();
            });
          var visible = getVisible();
          for (var i = 0; i < visible; i++) {
            var cl = baseCards[i % totalBase].cloneNode(true);
            cl.classList.add("news-card--clone");
            cl.setAttribute("aria-hidden", "true");
            carouselTrack.appendChild(cl);
          }
        }

        function moveTo(index, animate) {
          var step = getStepWidth();
          var offset = index * step;
          carouselTrack.style.transition = animate
            ? "transform 0.5s ease"
            : "none";
          carouselTrack.style.transform = "translateX(-" + offset + "px)";
        }

        function advance() {
          if (isTransitioning) return;
          isTransitioning = true;
          currentIndex++;
          moveTo(currentIndex, true);

          setTimeout(function () {
            if (currentIndex >= totalBase) {
              currentIndex = 0;
              moveTo(0, false);
            }
            isTransitioning = false;
          }, 520);
        }

        function startTimer() {
          carouselTimer = setInterval(advance, 3500);
        }
        function clearTimer() {
          clearInterval(carouselTimer);
          carouselTimer = null;
        }

        buildClones();
        moveTo(0, false);
        startTimer();

        carouselViewport.addEventListener("mouseenter", clearTimer);
        carouselViewport.addEventListener("mouseleave", startTimer);

        var resizeDelay;
        window.addEventListener("resize", function () {
          clearTimeout(resizeDelay);
          resizeDelay = setTimeout(function () {
            buildClones();
            if (currentIndex > totalBase - getVisible()) currentIndex = 0;
            moveTo(currentIndex, false);
          }, 200);
        });
      }

      // ── Multimedia tabs ─────────────────────────────────────
      var mmTabs = document.querySelectorAll(".multimedia-tabs .mm-tab");
      mmTabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
          mmTabs.forEach(function (t) {
            t.classList.remove("mm-tab--active");
            t.setAttribute("aria-selected", "false");
            var panel = document.getElementById(
              t.getAttribute("aria-controls"),
            );
            if (panel) panel.hidden = true;
          });
          tab.classList.add("mm-tab--active");
          tab.setAttribute("aria-selected", "true");
          var activePanel = document.getElementById(
            tab.getAttribute("aria-controls"),
          );
          if (activePanel) activePanel.hidden = false;
        });
      });
    },
  };
})(Drupal);
