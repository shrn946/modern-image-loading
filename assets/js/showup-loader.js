(function ($) {
  "use strict";

  var initialized = new WeakSet();
  var timelines = new WeakMap();
  var resizeObservers = new WeakMap();

  function toPositiveNumber(value, fallback, minimum) {
    var parsed = Number(value);
    return Number.isFinite(parsed) ? Math.max(minimum, parsed) : fallback;
  }

  function parseConfig(wrapper) {
    try {
      return JSON.parse(wrapper.getAttribute("data-esl-config") || "{}");
    } catch (error) {
      return {};
    }
  }

  function getExpandedSize(wrapper) {
    var rect = wrapper.getBoundingClientRect();
    var overscan = window.matchMedia &&
      window.matchMedia("(max-width: 767px)").matches ? 16 : 8;

    return {
      width: Math.max(1, Math.ceil(rect.width) + overscan),
      height: Math.max(1, Math.ceil(rect.height) + overscan)
    };
  }

  function setExpandedState(wrapper, box, growingImage) {
    var size = getExpandedSize(wrapper);

    window.gsap.set(box, {
      width: Math.ceil(size.width * 1.1),
      height: size.height
    });
    window.gsap.set(growingImage, {
      width: size.width,
      height: size.height
    });
  }

  function observeCompletedSize(wrapper, box, growingImage) {
    if (!window.ResizeObserver || resizeObservers.has(wrapper)) {
      return;
    }

    var observer = new ResizeObserver(function () {
      if (
        window.gsap &&
        (wrapper.classList.contains("is--complete") ||
          wrapper.classList.contains("is-animation-disabled"))
      ) {
        setExpandedState(wrapper, box, growingImage);
      }
    });

    observer.observe(wrapper);
    resizeObservers.set(wrapper, observer);
  }

  function showCompletedState(wrapper) {
    var box = wrapper.querySelectorAll(".esl-showup-loader__box");
    var growingImage = wrapper.querySelectorAll(".esl-showup__growing-image");
    var extras = wrapper.querySelectorAll(".esl-showup__cover-image-extra");
    var replayButton = wrapper.querySelector(".esl-showup-replay");

    wrapper.classList.remove("is--hidden", "is--loading");
    wrapper.classList.add("is-animation-disabled", "is--complete");

    if (window.gsap) {
      setExpandedState(wrapper, box, growingImage);
      window.gsap.set(extras, { opacity: 0 });
      observeCompletedSize(wrapper, box, growingImage);
    }

    if (replayButton) {
      replayButton.hidden = true;
    }
  }

  function waitForImages(wrapper) {
    var images = Array.prototype.slice.call(wrapper.querySelectorAll("img"));
    var pending = images.filter(function (image) {
      return !image.complete;
    });

    if (!pending.length) {
      return Promise.resolve();
    }

    return Promise.race([
      Promise.all(pending.map(function (image) {
        return new Promise(function (resolve) {
          image.addEventListener("load", resolve, { once: true });
          image.addEventListener("error", resolve, { once: true });
        });
      })),
      new Promise(function (resolve) {
        window.setTimeout(resolve, 2500);
      })
    ]);
  }

  function createTimeline(wrapper, config) {
    if (!wrapper.isConnected) {
      return;
    }

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
    var duration = toPositiveNumber(config.duration, 2, 0.1);
    var stagger = toPositiveNumber(config.stagger, 0.08, 0);
    var imageDuration = toPositiveNumber(config.imageDuration, 6, 0.1);
    var timeline = window.gsap.timeline({
      defaults: { ease: "expo.inOut" },
      paused: true,
      onStart: function () {
        wrapper.classList.remove("is--hidden", "is--complete", "is-animation-disabled");
        wrapper.classList.add("is--loading");
        window.gsap.set(box, { height: "1em" });
        window.gsap.set(growingImage, { height: "100%" });
        window.gsap.set(coverImageExtras, { opacity: 1 });
        if (replayButton) {
          replayButton.classList.remove("is-visible");
        }
      },
      onComplete: function () {
        wrapper.classList.remove("is--loading");
        wrapper.classList.add("is--complete");
        setExpandedState(wrapper, box, growingImage);
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
      "<"
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
        width: function () {
          return getExpandedSize(wrapper).width;
        },
        height: function () {
          return getExpandedSize(wrapper).height;
        },
        duration: imageDuration,
        ease: "expo.inOut"
      },
      ">+=0.2"
    );

    timeline.to(
      box,
      {
        width: function () {
          return Math.ceil(getExpandedSize(wrapper).width * 1.1);
        },
        height: function () {
          return getExpandedSize(wrapper).height;
        },
        duration: imageDuration
      },
      "<"
    );

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
          wrapper.classList.remove("is--complete");
          currentTimeline.restart(true);
        }
      });
    }

    observeCompletedSize(wrapper, box, growingImage);
    timeline.play(0);
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

    waitForImages(wrapper).then(function () {
      createTimeline(wrapper, config);
    }).catch(function () {
      showCompletedState(wrapper);
    });
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
