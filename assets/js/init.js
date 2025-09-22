document.addEventListener("DOMContentLoaded", () => {
  let wWidth = window.innerWidth;
  let wHeight = window.innerHeight;
  const header = document.querySelector(".wraper-header");
  const headerHeight = document.querySelector(".wraper-header").offsetHeight;

  gsap.registerPlugin(ScrollTrigger, ScrollSmoother);

  let smoother;
  // Function to check if the device supports touch events
  function isTouchDevice() {
    return (
      "ontouchstart" in window ||
      navigator.maxTouchPoints > 0 ||
      navigator.msMaxTouchPoints > 0
    );
  }

  if (isTouchDevice()) {
    // create the smooth scroller FIRST!
    smoother = ScrollSmoother.create({
      wrapper: "#main",
      content: "#content",
      effects: true,
      smooth: 1,
      ignoreMobileResize: true,
      smoothTouch: false,
    });
  } else {
    // create the smooth scroller FIRST!
    smoother = ScrollSmoother.create({
      wrapper: "#main",
      content: "#content",
      smooth: 1.2,
      normalizeScroll: true,
      ignoreMobileResize: true,
      effects: true,
      preventDefault: true,
      smoothTouch: false,
    });
  }

  // Header background change on scroll
  const siteHeader = document.getElementById("siteHeader");
  ScrollTrigger.create({
    start: 100,
    end: 99999,
    toggleClass: { targets: siteHeader, className: "scrolled" },
  });

  // Footer Accordion Functionality
  const accordionToggles = document.querySelectorAll(
    ".footer-menu-accordion-toggle"
  );
  if (accordionToggles) {
    accordionToggles.forEach((toggle) => {
      toggle.addEventListener("click", function () {
        const accordionId =
          this.closest("[data-accordion]").getAttribute("data-accordion");
        const content = document.querySelector(
          `[data-accordion-content="${accordionId}"]`
        );
        const isActive = content.classList.contains("active");

        // Toggle active state
        if (isActive) {
          content.classList.remove("active");
          this.classList.remove("active");
        } else {
          content.classList.add("active");
          this.classList.add("active");
        }
      });
    });

    // Initialize accordion state on mobile
    function initAccordion() {
      const isMobile = window.innerWidth <= 991;
      const accordionContents = document.querySelectorAll(
        ".footer-menu-accordion-content"
      );

      accordionContents.forEach((content) => {
        if (isMobile) {
          content.classList.remove("active");
        } else {
          content.classList.add("active");
        }
      });
    }

    // Initialize on load
    initAccordion();

    // Reinitialize on resize
    window.addEventListener("resize", initAccordion);
  }

  // Select the scroll-up button
  const scrollUpBtn = document.getElementById("scrollUpBtn");

  // Show the button when the page is scrolled down and hide it when at the top
  window.addEventListener("scroll", () => {
    if (window.scrollY > 200) {
      // Show button after scrolling 200px
      scrollUpBtn.style.visibility = "visible"; // Make button visible
    } else {
      scrollUpBtn.style.visibility = "hidden"; // Hide button at the top
    }
  });

  // Add click event listener to the button to scroll to the top
  scrollUpBtn.addEventListener("click", () => {
    // Use ScrollSmoother's scrollTo method to scroll smoothly to the top
    smoother.scrollTo(0, {
      duration: 1, // Duration of the scroll animation
      ease: "power2.out", // Easing function for smoothness
    });
  });

  // -------------------- header js ---------------------

  // Insert span after the specified element
  const menuItems = document.querySelectorAll(
    ".mobile-header-menu .menu li.menu-item-has-children > a"
  );
  menuItems.forEach(function (item) {
    const span = document.createElement("span");
    span.classList.add("clickD");
    item.insertAdjacentElement("afterend", span);
  });

  // Add event listener for click on clickD
  document
    .querySelectorAll(".mobile-header-menu .menu li .clickD")
    .forEach(function (clickD) {
      clickD.addEventListener("click", function (e) {
        e.preventDefault();
        const $this = clickD;

        if (
          $this.nextElementSibling &&
          $this.nextElementSibling.classList.contains("show")
        ) {
          $this.nextElementSibling.classList.remove("show");
          $this.classList.remove("toggled");
        } else {
          const parent = $this.closest("li");
          const subMenus = parent
            .closest(".mobile-header-menu")
            .querySelectorAll(".sub-menu");
          subMenus.forEach(function (subMenu) {
            subMenu.classList.remove("show");
          });
          const toggledItems = parent
            .closest(".mobile-header-menu")
            .querySelectorAll(".toggled");
          toggledItems.forEach(function (toggled) {
            toggled.classList.remove("toggled");
          });
          $this.nextElementSibling.classList.toggle("show");
          $this.classList.toggle("toggled");
        }
      });
    });

  // Event listener for window resize
  window.addEventListener("resize", function () {
    document.querySelector("html").addEventListener("click", function () {
      document
        .querySelectorAll(".mobile-header-menu .menu li .clickD")
        .forEach(function (clickD) {
          clickD.classList.remove("toggled");
        });
      document.querySelectorAll(".toggled").forEach(function (toggled) {
        toggled.classList.remove("toggled");
      });
      document.querySelectorAll(".sub-menu").forEach(function (subMenu) {
        subMenu.classList.remove("show");
      });
    });

    document.addEventListener("click", function () {
      document
        .querySelectorAll(".mobile-header-menu .menu li .clickD")
        .forEach(function (clickD) {
          clickD.classList.remove("toggled");
        });
      document.querySelectorAll(".toggled").forEach(function (toggled) {
        toggled.classList.remove("toggled");
      });
      document.querySelectorAll(".sub-menu").forEach(function (subMenu) {
        subMenu.classList.remove("show");
      });
    });

    document
      .querySelector(".mobile-header-menu .menu")
      .addEventListener("click", function (e) {
        e.stopPropagation();
      });
  });

  // Toggle mobile menu
  document
    .querySelectorAll(".toggle-mobile-menu")
    .forEach(function (toggleButton) {
      toggleButton.addEventListener("click", function () {
        const wrapper = document.querySelector(".wraper-mobile-header");
        const backdrop = document.querySelector(".mobile-header-backdrop");

        // Toggle menu visibility
        const isOpen = wrapper.classList.contains("open-mMenu");

        if (isOpen) {
          wrapper.classList.remove("open-mMenu");
          document.body.classList.remove("lockScroll");
          if (backdrop) backdrop.remove();
        } else {
          wrapper.classList.add("open-mMenu");
          document.body.classList.add("lockScroll");

          // Create and append backdrop if it doesn't exist
          if (!backdrop) {
            const newBackdrop = document.createElement("div");
            newBackdrop.classList.add("mobile-header-backdrop");
            document.body.appendChild(newBackdrop);

            // Add event listener for the backdrop to close the menu
            newBackdrop.addEventListener("click", function () {
              wrapper.classList.remove("open-mMenu");
              document.body.classList.remove("lockScroll");
              newBackdrop.remove();
            });
          }
        }
      });
    });

  // -------------------- header js ---------------------

  // --------------- comn-form js ------------

  if (document.querySelectorAll(".form-floating").length) {
    document.querySelectorAll(".form-floating").forEach(function (item) {
      const inputEle = item.querySelector(".form-control");
      if (inputEle) {
        // Check if the input already has a value
        if (inputEle.value !== "") {
          item.classList.add("input_focused");
        }

        inputEle.addEventListener("focusin", function () {
          item.classList.add("input_focused");
        });

        inputEle.addEventListener("blur", function () {
          if (inputEle.value === "") {
            item.classList.remove("input_focused");
          }
        });
      }
    });
  }
  // --------------- comn-form js ------------

  // ============== ///// start common-gsap animation ////// =====================

  if (document.querySelectorAll(".fade-in").length > 0) {
    // guards (optional): skip animations for reduced-motion users
    if (window.matchMedia("(prefers-reduced-motion: no-preference)").matches) {
      gsap.set(".fade-in", {
        y: 50,
        opacity: 0,
        willChange: "transform, opacity",
      });

      ScrollTrigger.batch(".fade-in", {
        start: "top 85%",
        once: false, // set true if you don't want it to replay on scroll up
        onEnter: (targets, triggers) => {
          gsap.to(targets, {
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out",
            stagger: { each: 0.08, from: "start" },
            overwrite: "auto",
          });
        },
        onLeaveBack: (targets) => {
          // match your toggleActions "reverse" behavior
          gsap.to(targets, {
            y: 50,
            opacity: 0,
            duration: 0.6,
            ease: "power3.inOut",
            overwrite: "auto",
          });
        },
      });

      // optional: refresh on images/fonts load to avoid misaligned triggers
      window.addEventListener("load", () => ScrollTrigger.refresh());
    }
  }
  // ------------- end fade-in ------------

  // SplitText
  document.querySelectorAll(".split_text").forEach((splitText) => {
    let split_text = new SplitType(splitText, {
      types: "words",
      tagName: "span",
    });
  });

  // document.querySelectorAll(".fade_word_ani").forEach(function (element) {
  //   const fe = element.querySelectorAll(".word");
  //   if (fe.length > 0) {
  //     gsap
  //       .timeline({
  //         scrollTrigger: {
  //           trigger: element,
  //           start: "top 70%",
  //           end: "top 20%",
  //           scrub: true,
  //         },
  //       })
  //       .fromTo(
  //         fe,
  //         {
  //           opacity: 0.2,
  //         },
  //         {
  //           opacity: 1,
  //           duration: 0.2,
  //           ease: "power1.out",
  //           stagger: {
  //             each: 0.4,
  //           },
  //         }
  //       );
  //   }
  // });

  document.querySelectorAll(".fade_word_ani").forEach((el) => {
    const words = el.querySelectorAll(".word");
    if (!words.length) return;

    gsap
      .timeline({
        scrollTrigger: {
          trigger: el,
          start: "top 90%",
          toggleActions: "play none none reverse",
          //markers: true
        },
      })
      .fromTo(
        words,
        { opacity: 0.2 },
        {
          opacity: 1,
          duration: 0.04, // super quick fade
          ease: "power1.out",
          stagger: { each: 0.02 }, // very little gap
        }
      );
  });

  // Function to create scroll-based triggers for animations
  function createScrollTrigger(triggerElement, timeline, animationSettings) {
    // Default animation settings (can be overridden)
    const defaultSettings = {
      start: "top 85%", // Trigger start position
      onEnter: () => timeline.play(),
      onLeaveBack: () => {
        timeline.progress(0);
        timeline.pause();
      },
    };

    // Combine user settings with defaults
    const settings = { ...defaultSettings, ...animationSettings };

    // Create the ScrollTrigger with provided or default settings
    ScrollTrigger.create({
      trigger: triggerElement,
      start: settings.start,
      // Uncomment below for debugging purposes
      // markers: true,
      onEnter: settings.onEnter,
      onLeaveBack: settings.onLeaveBack,
    });
  }

  // Function to create a timeline for sliding words from the right
  function createSlideFromRightAnimation(element) {
    let tl = gsap.timeline({ paused: true });
    const words = element.querySelectorAll(".word"); // Select child elements

    tl.from(words, {
      opacity: 0,
      x: "1em",
      duration: 0.8,
      ease: "power2.out",
      stagger: { amount: 0.3 },
    });

    // Call createScrollTrigger with the element and timeline
    createScrollTrigger(element, tl, {
      start: "top 87%", // Customize for this specific animation
    });
  }

  // Example usage:
  // Find all elements with the class 'words_slide_from_right'
  document.querySelectorAll(".words_slide_from_right").forEach((element) => {
    createSlideFromRightAnimation(element);
  });

  // ============== ////////// end common-gsap animation //////// =====================

  // -------------- Replace <img> with fetched inline <svg> -----------------
  document.querySelectorAll("img.svg_icon").forEach((img) => {
    const src = img.getAttribute("src");

    fetch(src)
      .then((res) => res.text())
      .then((svgText) => {
        const parser = new DOMParser();
        const svgDoc = parser.parseFromString(svgText, "image/svg+xml");
        const svg = svgDoc.querySelector("svg");

        if (svg) {
          // Copy attributes/classes from <img> to <svg>
          if (img.id) svg.id = img.id;
          if (img.className) svg.classList.add(...img.classList);
          svg.setAttribute("role", "img");

          // Replace <img> with <svg>
          img.replaceWith(svg);
        }
      })
      .catch((err) => console.error("Error loading SVG:", err));
  });
  // -------------- Replace <img> with fetched inline <svg> -----------------

  function truncateText(el, lines) {
    const lineHeight = parseFloat(getComputedStyle(el).lineHeight);
    const maxHeight = lineHeight * lines + 1;

    let text = el.textContent;

    function adjustText() {
      // Check if the scroll height is greater than the max height
      if (el.scrollHeight > maxHeight) {
        text = text.replace(/\W*\s(\S)*$/, "..."); // Adjust text removal to handle white space better
        el.textContent = text;
        setTimeout(adjustText, 0); // Re-run the function to check again after DOM reflow
      }
    }

    adjustText(); // Start the truncation process
  }

  if (navigator.userAgent.toLowerCase().includes("firefox")) {
    const elements = document.querySelectorAll(".multi-2-line-ellipsis");
    elements.forEach((el) => truncateText(el, 2));
  }

  if (navigator.userAgent.toLowerCase().includes("firefox")) {
    const elements = document.querySelectorAll(".multi-3-line-ellipsis");
    elements.forEach((el) => truncateText(el, 3));
  }

  // ====================== start home-page js =================================
  // const heroBannerSlider_el = document.querySelector(".hero_banner_slider");

  // if (heroBannerSlider_el) {

  //   const heroBannerSlider = new Splide(".hero_banner_slider", {
  //     type: "loop",
  //     perPage: 1,
  //     //easing: "cubic-bezier(0.22,1,0.36,1)",
  //     rewind: true,
  //     pagination: true,
  //     arrows: false,
  //     drag: true,
  //     loop: true,
  //     autoplay: true,
  //     interval: 4000,
  //     pauseOnHover: false,
  //     pauseOnFocus: false,
  //     speed: 1500,
  //   });

  //   const heroBannerDscContentSlider = new Splide(".hero_banner_dsc_content_slider", {
  //     type: "loop",
  //     perPage: 1,
  //     //easing: "cubic-bezier(0.22,1,0.36,1)",
  //     rewind: true,
  //     pagination: true,
  //     arrows: false,
  //     drag: true,
  //     loop: true,
  //     autoplay: true,
  //     interval: 4000,
  //     pauseOnHover: false,
  //     pauseOnFocus: false,
  //     speed: 1500,
  //   });

  //   heroBannerSlider.mount();

  //   heroBannerSlider.on("move", function (index) {
  //     const activeSlide = heroBannerSlider.Components.Slides.getAt(index).slide; // Accessing the DOM element

  //     const animf_Right = activeSlide.querySelectorAll(".animf_right");
  //     const animf_right_image = activeSlide.querySelector(".animf_right_image");

  //     // image Animation with Stagger
  //     gsap.fromTo(
  //       animf_right_image,
  //       {
  //         opacity: 0,
  //         x: 50,
  //         y: -100,
  //       },
  //       {
  //         opacity: 1,
  //         x: 0,
  //         y: 0,
  //         duration: 1.4,
  //         stagger: 0.3, // Stagger each heading by 0.3 seconds
  //         ease: "power3.out",
  //       }
  //     );
  //     // Text Element Animation with Stagger
  //     gsap.fromTo(
  //       animf_Right,
  //       {
  //         opacity: 0,
  //         x: 70,
  //         transformOrigin: "center",
  //       },
  //       {
  //         opacity: 1,
  //         x: 0,
  //         duration: 1.3,
  //         stagger: 0.2, // Stagger each list item by 0.2 seconds
  //         ease: "power3.out",
  //       }
  //     );
  //   });
  // }

  const heroBannerSlider_el = document.querySelector(".hero_banner_slider");

  if (heroBannerSlider_el) {
    const heroBannerSlider = new Splide(".hero_banner_slider", {
      type: "fade",
      perPage: 1,
      pagination: false, // no dots on primary
      arrows: false,
      drag: true,
      rewind: true,
      //loop: true,
      autoplay: true,
      interval: 4000,
      pauseOnHover: false,
      pauseOnFocus: false,
      speed: 1500,
    });

    const heroBannerDscContentSlider = new Splide(
      ".hero_banner_dsc_content_slider",
      {
        type: "fade",
        perPage: 1,
        pagination: true, // dots visible here
        arrows: false,
        drag: true,
        autoplay: false, // driven by primary
        speed: 500,
        //loop: true,
      }
    );

    heroBannerDscContentSlider.mount();
    heroBannerSlider.sync(heroBannerDscContentSlider).mount();

    // GSAP animation helper
    const runAnim = (splide) => {
      const active = splide.Components.Slides.getAt(splide.index)?.slide;
      if (!active) return;

      let animf_Right = active.querySelectorAll(".animf_right");

      if (animf_Right) {
        gsap.fromTo(
          animf_Right,
          { opacity: 0, x: 70, transformOrigin: "center" },
          { opacity: 1, x: 0, duration: 1.3, stagger: 0.2, ease: "power3.out" }
        );
      }
    };

    // GSAP animation helper
    const runAnim2 = (splide) => {
      const active = splide.Components.Slides.getAt(splide.index)?.slide;
      if (!active) return;
      let animf_right_image = active.querySelector(".animf_right_image");

      if (animf_right_image) {
        gsap.fromTo(
          animf_right_image,
          { opacity: 0, x: 50, y: -100 },
          {
            opacity: 1,
            x: 0,
            y: 0,
            duration: 1.4,
            stagger: 0.3,
            ease: "power3.out",
          }
        );
      }
    };

    // Animate after mount and after each move (post-transition)
    heroBannerSlider.on("mounted move", () => runAnim2(heroBannerSlider));
    heroBannerDscContentSlider.on("mounted move", () =>
      runAnim(heroBannerDscContentSlider)
    );
  }

  const homeAboutImg_el = document.querySelector(
    ".home_about_overlay_image_holder img"
  );

  if (homeAboutImg_el) {
    gsap
      .timeline({
        scrollTrigger: {
          trigger: homeAboutImg_el,
          start: "clamp(top 70%)",
          end: "clamp(bottom 20%)",
          scrub: 1,
          //markers:true,
        },
      })
      .from(
        homeAboutImg_el,
        {
          y: "-50%",
        }
        // {
        //   opacity: 1,
        //   duration: 0.2,
        //   ease: "power1.out",
        //   stagger: {
        //     each: 0.4,
        //   },
        // }
      );
  }

  // Counter Up
  const counters = document.querySelectorAll(".counter_ani");

  const format = new Intl.NumberFormat(); // or pass a locale: new Intl.NumberFormat('en-IN')
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const counter = entry.target;

        // Prevent multiple animations
        if (counter.dataset.animating === "true") return;
        counter.dataset.animating = "true";

        // Parse data-target robustly: remove commas/anything that's not digit, dot, or minus
        const raw = (
          counter.dataset.target ||
          counter.getAttribute("data-target") ||
          "0"
        )
          .toString()
          .replace(/[^\d.-]/g, "");
        const target = Number(raw);
        if (!Number.isFinite(target)) {
          counter.textContent = "0";
          counter.dataset.animating = "false";
          return;
        }

        // Duration-based step for smoother animation (~1s)
        const duration = 1000;
        const start = performance.now();
        const from = 0;

        const run = (t) => {
          const elapsed = t - start;
          const progress = Math.min(elapsed / duration, 1);
          const value = Math.round(from + (target - from) * progress);

          counter.textContent = format.format(value);

          if (progress < 1) {
            requestAnimationFrame(run);
          } else {
            counter.dataset.animating = "false"; // allow re-run
          }
        };

        counter.textContent = format.format(0); // reset each time
        requestAnimationFrame(run);
      });
    },
    { threshold: 0.6 }
  );

  counters.forEach((counter) => {
    counter.dataset.animating = "false";
    observer.observe(counter);
  });

  (() => {
    const GAP_PX = 19; // keep in sync with $gap value in SCSS
    const container = document.getElementById("ec_tiles");

    if (container) {
      // Build 3 cols/row on desktop, 2 cols/row on mobile
      function rebuildRows() {
        const tiles = Array.from(container.querySelectorAll(".ec_tile"));

        // unwrap existing rows
        Array.from(container.querySelectorAll(".ec_row")).forEach((row) => {
          while (row.firstChild) container.appendChild(row.firstChild);
          row.remove();
        });

        const cols = matchMedia("(max-width: 720px)").matches ? 2 : 3;

        for (let i = 0; i < tiles.length; i += cols) {
          const row = document.createElement("div");
          row.className = "ec_row";
          for (let j = i; j < i + cols && j < tiles.length; j++)
            row.appendChild(tiles[j]);
          container.appendChild(row);
        }
      }

      function bindInteractions() {
        const isTouch = matchMedia("(pointer: coarse)").matches;

        // Clear focus state in a row
        function clearRow(row) {
          row.classList.remove("row-active");
          row
            .querySelectorAll(".ec_tile.is-focus")
            .forEach((t) => t.classList.remove("is-focus"));
        }

        // TOUCH: tap to toggle expanded (focus) per row
        if (isTouch) {
          container.addEventListener("click", (e) => {
            const tile = e.target.closest(".ec_tile");
            if (!tile) return;
            const row = tile.closest(".ec_row");
            const isFocused = tile.classList.contains("is-focus");
            clearRow(row);
            if (!isFocused) {
              row.classList.add("row-active");
              tile.classList.add("is-focus");
            }
          });

          // tap outside -> collapse all
          document.addEventListener("click", (e) => {
            if (!e.target.closest("#ec_tiles")) {
              Array.from(container.querySelectorAll(".ec_row")).forEach(
                clearRow
              );
            }
          });
        }

        // DESKTOP: hover to preview expansion smoothly (no click)
        if (!isTouch) {
          container.addEventListener(
            "mouseenter",
            (e) => {
              const tile = e.target.closest(".ec_tile");
              if (!tile) return;
              const row = tile.closest(".ec_row");
              row.classList.add("row-active");
              tile.classList.add("is-focus");
            },
            true
          );

          container.addEventListener(
            "mouseleave",
            (e) => {
              const tile = e.target.closest(".ec_tile");
              const row =
                (tile
                  ? tile.closest(".ec_row")
                  : e.target.closest(".ec_row")) || null;
              if (row) clearRow(row);
            },
            true
          );

          container.addEventListener("mouseover", (e) => {
            const tile = e.target.closest(".ec_tile");
            if (!tile) return;
            const row = tile.closest(".ec_row");
            // move focus within the same row
            if (!row.classList.contains("row-active"))
              row.classList.add("row-active");
            row
              .querySelectorAll(".ec_tile.is-focus")
              .forEach((t) => t.classList.remove("is-focus"));
            tile.classList.add("is-focus");
          });
        }

        // Keyboard accessibility (both modes)
        container.addEventListener("keydown", (e) => {
          const tile = e.target.closest(".ec_tile");
          if (!tile) return;
          const row = tile.closest(".ec_row");

          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            // emulate touch toggle
            const was = tile.classList.contains("is-focus");
            clearRow(row);
            if (!was) {
              row.classList.add("row-active");
              tile.classList.add("is-focus");
            }
          }
          if (e.key === "Escape") clearRow(row);
        });
      }

      // Debounced on resize
      let raf = 0;
      function onResize() {
        if (raf) cancelAnimationFrame(raf);
        raf = requestAnimationFrame(() => {
          rebuildRows();
        });
      }

      // init
      rebuildRows();
      bindInteractions();
      addEventListener("resize", onResize);
    }
  })();

  if (document.querySelector(".certificates_slider")) {
    let certificates_slider = new Splide(".certificates_slider", {
      type: "slide",
      pagination: true,
      arrows: false,
      drag: true,
      speed: 1200,
      perPage: 2,
      perMove: 2,
      gap: 20,
      mediaQuery: "min",
      breakpoints: {
        576: {
          perPage: 3,
          perMove: 3,
        },
        // 768: {
        //   perPage: 3,
        //   perMove: 3,
        // },
        768: {
          perPage: 4,
          perMove: 4,
          gap: 25,
        },
        1200: {
          gap: 45,
          perPage: 5,
          perMove: 5,
        },
        1400: {
          gap: 60,
          perPage: 5,
          perMove: 5,
        },
        1800: {
          perPage: 6,
          perMove: 6,
        },
      },
    });

    certificates_slider.mount();
  }

  // ----------- start logo_slider ------------------

  if (typeof Splide === "undefined") {
    console.error("Splide.js is not loaded!");
    return;
  } else {
    window.splide.ExtensionsAutoScroll = {
      AutoScroll: window.splide.Extensions.AutoScroll,
    }; // AutoScroll Extension
  }

  let logoSlider;
  let logoSlider_elms = document.querySelectorAll(".logo_slider");
  if (logoSlider_elms) {
    for (var i = 0; i < logoSlider_elms.length; i++) {
      logoSlider = new Splide(logoSlider_elms[i], {
        autoWidth: true,
        perMove: 2,
        gap: "30px",
        mediaQuery: "min",
        speed: 1400,
        arrows: false,
        pagination: false,
        drag: true,
        type: "loop",
        clones: 50,
        autoScroll: {
          speed: 1,
        },
        breakpoints: {
          576: {
            gap: "40px",
          },
          768: {
            gap: "45px",
          },
          1200: {},
        },
      });

      logoSlider.mount(window.splide.ExtensionsAutoScroll);
    }
  }
  // ----------- end logo_slider ------------------
  if (document.querySelector("#home_product_slider")) {
    new Splide("#home_product_slider", {
      //type: "loop",
      perPage: 1, // Adjust based on your layout
      gap: "30px", // Adjust spacing
      autoplay: false, // Optional: enable autoplay
      mediaQuery: "min",
      pagination: true,
      arrows: false,
      drag: true,
      classes: {
        pagination:
          "splide__pagination comn_splide__pagination bt_minus_30 column-gap-1",
      },
      breakpoints: {
        576: {
          perPage: 2,
        },
      },
    }).mount();
  }

  // ====================== end home-page js =================================

  // ============== start news-listing ================================


  const sortByCareer = document.getElementById("sortByCareer");
  if (sortByCareer) {
    const choices = new Choices(sortByCareer, {
      searchEnabled: false,
      itemSelectText: "",
      shouldSort: false,
      placeholder: true,
      //placeholderValue: "Recent",
    });
  }

  // ------------- feature-slider -----------------

  if (document.querySelectorAll(".featured_news_image_slider").length > 0) {
    // Initialize Image Slider
    const imageSlider = new Splide(".featured_news_image_slider", {
      type: "slide",
      rewind: true,
      perPage: 1,
      perMove: 1,
      arrows: false,
      pagination: false,
      autoplay: true,
      interval: 5000,
      pauseOnHover: true,
      pauseOnFocus: true,
      resetProgress: false,
      height: "auto",
      cover: true,
      focus: "center",
      gap: "0px",
      speed: 1200,
    });

    // Initialize Content Slider
    const contentSlider = new Splide(".featured_news_content_slider", {
      type: "slide",
      rewind: true,
      perPage: 1,
      perMove: 1,
      arrows: false,
      pagination: false,
      autoplay: true,
      interval: 5000,
      pauseOnHover: true,
      pauseOnFocus: true,
      resetProgress: false,
      height: "auto",
      cover: true,
      focus: "center",
      gap: "20px",
      speed: 1200,
    });

    // Sync the sliders
    imageSlider.sync(contentSlider);

    // Custom navigation buttons for featured news
    const prevBtn = document.querySelector(
      ".featured_news_navigation .prev_btn"
    );
    const nextBtn = document.querySelector(
      ".featured_news_navigation .next_btn"
    );

    // Function to update button states
    function updateNavigationButtons() {
      // Only apply disable functionality if rewind is false
      if (imageSlider.options.rewind) {
        return;
      }

      const currentIndex = imageSlider.index;
      const totalSlides = imageSlider.length;

      // Update prev button
      if (prevBtn) {
        if (currentIndex === 0) {
          prevBtn.classList.add("disabled");
          prevBtn.disabled = true;
        } else {
          prevBtn.classList.remove("disabled");
          prevBtn.disabled = false;
        }
      }

      // Update next button
      if (nextBtn) {
        if (currentIndex === totalSlides - 1) {
          nextBtn.classList.add("disabled");
          nextBtn.disabled = true;
        } else {
          nextBtn.classList.remove("disabled");
          nextBtn.disabled = false;
        }
      }
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        // Only check disabled state if rewind is false
        if (imageSlider.options.rewind || !prevBtn.disabled) {
          imageSlider.go("<");
        }
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        // Only check disabled state if rewind is false
        if (imageSlider.options.rewind || !nextBtn.disabled) {
          imageSlider.go(">");
        }
      });
    }

    // Custom pagination functionality
    const paginationDots = document.querySelectorAll(".pagination_dot");
    paginationDots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        imageSlider.go(index);
      });
    });

    // Update pagination dots and navigation buttons when slide changes
    imageSlider.on("move", (newIndex) => {
      paginationDots.forEach((dot, index) => {
        dot.classList.toggle("active", index === newIndex);
      });
      updateNavigationButtons();
    });

    // Mount both sliders
    imageSlider.mount();
    contentSlider.mount();

    // Initialize navigation button states
    updateNavigationButtons();
  }
  // ============== /// end news-listing  /// ================================

  // ============== //// start news-details //// ================================
  // console.log(`"top ${headerHeight}px"`)
  //   ScrollTrigger.create({
  //   trigger: ".sticky_thing",
  //   start: `"top ${headerHeight + 20}px"`,        // how long it stays pinned
  //   pin: true,
  //   pinSpacing: false,     // like sticky (no extra space)
  // });

  // Sticky Share Widget with proper pinning
  const stickyShareWidget = document.getElementById("stickyShareWidget");
  const contentSection = document.querySelector(".news_details_content");
  //const stickyShareWidgetHeight = document.getElementById("stickyShareWidget").clientHeight;

  let shareWidgetTrigger;

  function setupStickyShareWidget() {
    // Kill only this widget’s trigger if it already exists
    if (shareWidgetTrigger) {
      shareWidgetTrigger.kill();
      shareWidgetTrigger = null;
    }

    if (window.innerWidth >= 1200 && stickyShareWidget && contentSection) {
      const stickyShareWidgetHeight = stickyShareWidget.clientHeight;

      shareWidgetTrigger = ScrollTrigger.create({
        trigger: contentSection,
        start: `top ${headerHeight + 20}px`,
        end: `bottom ${stickyShareWidgetHeight + headerHeight + 50}px`,
        pin: stickyShareWidget,
        pinSpacing: false,
        //markers: true,
      });
    }
  }

  setupStickyShareWidget();
  window.addEventListener("resize", setupStickyShareWidget);

  // Related News Slider
  const relatedNewsSlider_el = document.getElementById("relatedNewsSlider");

  if (relatedNewsSlider_el) {
    let totalSlides =
      relatedNewsSlider_el.querySelectorAll(".splide__slide").length;

    const relatedNewsSlider = new Splide(relatedNewsSlider_el, {
      type: "slide",
      perPage: 1,
      perMove: 1,
      gap: "20px",
      pagination: totalSlides > 1,
      drag: totalSlides > 1,
      arrows: false,
      speed: 1200,
      mediaQuery: "min",
      classes: {
        pagination: "splide__pagination comn_splide__pagination bt_minus_30",
      },

      breakpoints: {
        576: {
          perPage: 1,
        },
        768: {
          pagination: totalSlides > 2,
          drag: totalSlides > 2,
          perPage: 2,
        },
        992: {
          pagination: totalSlides > 2,
          drag: totalSlides > 2,
          perPage: 2,
        },
        1200: {
          pagination: totalSlides > 3,
          drag: totalSlides > 3,
          perPage: 3,
        },
      },
    });
    relatedNewsSlider.mount();
  }

  // Resource  blogs Slider
  const blogListSlider_el = document.getElementById("bloglistSlider");

  if (blogListSlider_el) {
    let totalSlides =
      blogListSlider_el.querySelectorAll(".splide__slide").length;

    const blogListSlider = new Splide(blogListSlider_el, {
      type: "fade",
      perPage: 1,
      perMove: 1,
      gap: "20px",
      pagination: totalSlides > 1,
      drag: totalSlides > 1,
      arrows: false,
      speed: 1200,
      mediaQuery: "min",
      rewind: true,
      autoplay: true,
      classes: {
        pagination: "splide__pagination comn_splide__pagination bt_minus_30",
      },
    });
    blogListSlider.mount();
  }

  const defaultContentImgSlider_el = document.querySelectorAll(
    ".default_content_img_slider"
  );

  if (defaultContentImgSlider_el) {
    defaultContentImgSlider_el.forEach(function (sliderEl) {
      let totalSlides = sliderEl.querySelectorAll(".splide__slide").length;

      let splideInstance = new Splide(sliderEl, {
        perPage: 1,
        mediaQuery: "min",
        gap: "24px",
        pagination: false,
        arrows: totalSlides > 1,
        classes: {
          arrows: "splide__arrows comn_splide__arrows_white",
        },
      });

      splideInstance.mount();
    });
  }
  // ============== //// end news-details ///// ================================
});

// Blog listing video play fancybox
document.addEventListener("DOMContentLoaded", function () {
  if (
    window.Fancybox &&
    document.querySelectorAll("#blogListWrap [data-fancybox='blogListVideo']")
      .length > 0
  ) {
    Fancybox.bind("#blogListWrap [data-fancybox='blogListVideo']", {
      toolbar: false,
      nav: false,
    });
  }
});
