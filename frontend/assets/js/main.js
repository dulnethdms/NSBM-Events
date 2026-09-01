/**
 * NSBM EventHub - Client-Side JavaScript Application Logic
 * Interactive UI behaviors, Theme Switcher, Live Client Search/Filter, and AJAX seat registration.
 */



//photo frame


document.addEventListener("scroll", () => {
  const section = document.querySelector(".photo-section");
  const overlay = document.querySelector(".overlay-photo");
  const base = document.querySelector(".base-photo");

  const sectionTop = section.offsetTop;
  const sectionHeight = section.offsetHeight;
  const windowHeight = window.innerHeight;

  const scrollY = window.scrollY;
  const scrolled = scrollY - sectionTop;

  const maxScroll = sectionHeight - windowHeight;
  let progress = scrolled / maxScroll;
  progress = Math.min(Math.max(progress, 0), 1);

  // zoom overlay image
  const scale = 1 + progress * 1; // zoom from 1 → 1.5
  
  overlay.style.opacity = progress; // fade in overlay

  // fade out base image
  base.style.opacity = 1 - progress;
});








