(function ($) {
  "use strict";

  var initialized = new WeakSet();
  var timelines = new WeakMap();

  function parseConfig(wrapper) {
    try {
      return JSON.parse(wrapper.getAttribute("data-esl-config") || "{}");
    } catch (error) {
      return {};
    }
  }

  function showCompletedState(wrapper) {
    var box = wrapper.querySelectorAll(".esl-showup-loader__box");
    var growingImage = wrapper.querySelectorAll(".esl-showup__growing-image");
    var extras = wrapper.querySelectorAll(".esl-showup__cover-image-extra");
    var replayButton = wrapper.querySelector(".esl-showup-replay");

    wrapper.classList.remove("is--hidden", "is--loading");
    wrapper.classList.add("is-animation-disabled");

    if (window.gsap) {
      window.gsap.set(box, { width: "110vw" });
      window.gsap.set(growingImage, { width: "100vw", height: "100dvh" });
      window.gsap.set(extras, { opacity: 0 });
    }

    if (replayButton) {
      replayButton.hidden = true;
    }
  }

  function initShowupLoader(wrapper) {
    if (!wrapper || initialized.has(wrapper)) {
      return;
    }

    initialized.add(wrapper);

    var config = parseConfig(wrapper);
    var reducedMotion = window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (config.enabled === false || reducedMotion || !window.gsap) {
      showCompletedState(wrapper);
      return;
    }

    var loadingLetters = wrapper.querySelectorAll(".esl-showup__letter");
    var box = wrapper.querySelectorAll(".esl-showup-loader__box");
    var growingImage = wrapper.querySelectorAll(".esl-showup__growing-image");
    var headingStart = wrapper.querySelectorAll(".esl-showup__h1-start");
    var headingEnd = wrapper.querySelectorAll(".esl-showup__h1-end");
    var coverImageExtras = wrapper.querySelectorAll(".esl-showup__cover-image-extra");
    var finalLetters = wrapper.querySelectorAll(".esl-showup__letter-white");
    var replayButton = wrapper.querySelector(".esl-showup-replay");
    var duration = Number(config.duration) || 2;
    var stagger = Math.max(0, Number(config.stagger) || 0);
    var imageDuration = Number(config.imageDuration) || 6;

    var timeline = window.gsap.timeline({
      defaults: { ease: "expo.inOut" },
      onStart: function () {
        wrapper.classList.remove("is--hidden");
        wrapper.classList.add("is--loading");
        if (replayButton) {
          replayButton.classList.remove("is-visible");
        }
      },
      onComplete: function () {
        wrapper.classList.remove("is--loading");
        if (replayButton) {
          replayButton.classList.add("is-visible");
        }
      }
    });

    timelines.set(wrapper, timeline);

    timeline.from(loadingLetters, {
      yPercent: 100,
      stagger: stagger,
      duration: duration
    });

    timeline.fromTo(
      box,
      { width: "0em" },
      { width: "1em", duration: duration },
      "< " + duration
    );

    timeline.fromTo(
      growingImage,
      { width: "0%" },
      { width: "100%", duration: duration },
      "<"
    );

    timeline.fromTo(
      headingStart,
      { x: "0em" },
      { x: "-0.05em", duration: duration },
      "<"
    );

    timeline.fromTo(
      headingEnd,
      { x: "0em" },
      { x: "0.05em", duration: duration },
      "<"
    );

    if (coverImageExtras.length) {
      window.gsap.set(coverImageExtras, { opacity: 1 });

      coverImageExtras.forEach(function (image, index) {
        timeline.to(
          image,
          {
            opacity: 0,
            duration: 0.35,
            ease: "power1.inOut"
          },
          index === 0 ? ">+=0.8" : ">+=0.65"
        );
      });
    }

    timeline.to(
      growingImage,
      {
        width: "100vw",
        height: "100dvh",
        duration: imageDuration,
        ease: "expo.inOut"
      },
      ">+=0.2"
    );

    timeline.to(box, { width: "110vw", duration: imageDuration }, "<");

    timeline.from(
      finalLetters,
      {
        yPercent: 100,
        duration: duration,
        ease: "expo.out",
        stagger: stagger
      },
      ">"
    );

    if (replayButton) {
      replayButton.addEventListener("click", function () {
        var currentTimeline = timelines.get(wrapper);
        if (currentTimeline) {
          currentTimeline.restart();
        }
      });
    }
  }

  function initWithin(scope) {
    var root = scope && scope.nodeType ? scope : document;

    if (root.matches && root.matches(".esl-showup-header")) {
      initShowupLoader(root);
    }

    root.querySelectorAll(".esl-showup-header").forEach(initShowupLoader);
  }

  $(window).on("elementor/frontend/init", function () {
    window.elementorFrontend.hooks.addAction(
      "frontend/element_ready/showup-loader.default",
      function ($scope) {
        initWithin($scope[0]);
      }
    );
  });

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initWithin(document);
    });
  } else {
    initWithin(document);
  }
})(jQuery);
