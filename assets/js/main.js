// Site-wide JS. Right now this just handles the scroll-driven photo
// crossfade on the landing page hero. Loaded on every page via footer.php,
// so we bail out early if the elements aren't on the page instead of
// throwing errors on every other screen.

document.addEventListener("scroll", () => {
  const section = document.querySelector(".photo-section");
  const overlay = document.querySelector(".overlay-photo");
  const base = document.querySelector(".base-photo");

  if (!section || !overlay || !base) {
    return;
  }

  const sectionTop = section.offsetTop;
  const scrollY = window.scrollY;
  const scrolled = scrollY - sectionTop;

  // Animation completes after 500px of scrolling
  const maxScroll = 300;

  let progress = scrolled / maxScroll;
  progress = Math.min(Math.max(progress, 0), 1);

  const scale = 1 + progress * 0.5;

  overlay.style.transform = `scale(${scale})`;
  overlay.style.opacity = progress;
  base.style.opacity = 1 - progress;
});
