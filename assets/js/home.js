document.addEventListener("DOMContentLoaded", function () {
  requestAnimationFrame(function () {
    document.body.classList.add("is-loaded");
  });

  var lamp = document.getElementById("hero-lamp");
  var heroCard = document.getElementById("hero");
  var switchInput = document.getElementById("lamp-switch-input");
  var switchLabel = document.getElementById("lamp-switch-label");
  var modeTitle = document.getElementById("hero-mode-title");
  var variantButtons = document.querySelectorAll(".hero-variant");
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (lamp && switchInput && switchLabel) {
    var selectedProduct = "pinksaltlamp";
    var productOrder = ["pinksaltlamp", "whitesaltlamp", "saltcandle"];
    var isAnimating = false;

    var productLabels = {
      pinksaltlamp: "Pink Salt Lamp",
      whitesaltlamp: "White Salt Lamp",
      saltcandle: "Salt Candle"
    };

    var productImages = {
      pinksaltlamp: { off: "/thesaltship/assets/images/hero/pinksaltlampoff.webp", on: "/thesaltship/assets/images/hero/pinksaltlampon.webp" },
      whitesaltlamp: { off: "/thesaltship/assets/images/hero/whitesaltlampoff.webp", on: "/thesaltship/assets/images/hero/whitesaltlampon.webp" },
      saltcandle: { off: "/thesaltship/assets/images/hero/saltcandleoff.webp", on: "/thesaltship/assets/images/hero/saltcandleon.webp" }
    };

    function renderState() {
      var isOn = switchInput.checked;
      var selected = productImages[selectedProduct] || productImages.pinksaltlamp;
      lamp.src = isOn ? selected.on : selected.off;
      lamp.alt = (productLabels[selectedProduct] || "Salt product") + (isOn ? " in on state" : " in off state");
      lamp.classList.toggle("is-on", isOn);
      if (heroCard) {
        heroCard.classList.toggle("is-lit", isOn);
        heroCard.setAttribute("data-product", selectedProduct);
      }
      switchLabel.textContent = isOn ? "ON" : "OFF";
      if (modeTitle) {
        modeTitle.textContent = (productLabels[selectedProduct] || "Salt Product") + (isOn ? " - Glow Preview" : " - Standard View");
      }
    }

    function animateStateChange() {
      if (isAnimating) return;
      isAnimating = true;
      lamp.classList.add("is-fading");
      setTimeout(function () {
        renderState();
        lamp.classList.remove("is-fading");
        isAnimating = false;
      }, 170);
    }

    function setSelectedProduct(nextProduct) {
      if (!nextProduct || nextProduct === selectedProduct || isAnimating) return;
      selectedProduct = nextProduct;
      variantButtons.forEach(function (item) {
        item.classList.toggle("is-active", item.getAttribute("data-product") === selectedProduct);
      });
      animateStateChange();
    }

    renderState();

    switchInput.addEventListener("change", animateStateChange);

    variantButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        setSelectedProduct(button.getAttribute("data-product"));
      });

      button.addEventListener("keydown", function (event) {
        var currentIndex = productOrder.indexOf(selectedProduct);
        if (currentIndex === -1) return;

        var nextIndex = currentIndex;
        if (event.key === "ArrowRight") {
          nextIndex = (currentIndex + 1) % productOrder.length;
        } else if (event.key === "ArrowLeft") {
          nextIndex = (currentIndex - 1 + productOrder.length) % productOrder.length;
        } else {
          return;
        }

        event.preventDefault();
        setSelectedProduct(productOrder[nextIndex]);
      });
    });
  }

  if (heroCard && !reduceMotion) {
    var ticking = false;

    function clamp(value, min, max) {
      return Math.min(Math.max(value, min), max);
    }

    function updateParallax() {
      var rect = heroCard.getBoundingClientRect();
      var viewport = window.innerHeight || document.documentElement.clientHeight;
      var progress = (viewport - rect.top) / (viewport + rect.height);
      var centered = clamp(progress, 0, 1) - 0.5;

      heroCard.style.setProperty("--parallax-title", centered * -26 + "px");
      heroCard.style.setProperty("--parallax-text", centered * -16 + "px");
      heroCard.style.setProperty("--parallax-cta", centered * -10 + "px");
      heroCard.style.setProperty("--parallax-panel", centered * -34 + "px");

      ticking = false;
    }

    function requestTick() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(updateParallax);
    }

    updateParallax();
    window.addEventListener("scroll", requestTick, { passive: true });
    window.addEventListener("resize", requestTick);
  }
});
