// Site-wide JS. Right now this just handles the scroll-driven photo
// crossfade on the landing page hero. Loaded on every page via footer.php,
// so we bail out early if the elements aren't on the page instead of
// throwing errors on every other screen.

document.addEventListener("scroll", () => {
  const section = document.querySelector(".photo-section");
  const overlay = document.querySelector(".overlay-photo");
  const base = document.querySelector(".base-photo");

  if (!section || !overlay || !base) {
    return; // not on the homepage, nothing to do here
  }

  const sectionTop = section.offsetTop;
  const sectionHeight = section.offsetHeight;
  const windowHeight = window.innerHeight;

  const scrollY = window.scrollY;
  const scrolled = scrollY - sectionTop;

  const maxScroll = sectionHeight - windowHeight;
  let progress = scrolled / maxScroll;
  progress = Math.min(Math.max(progress, 0), 1);

  // zoom the overlay in as we scroll (1 -> 1.5), fade it over the base image
  const scale = 1 + progress * 0.5;
  overlay.style.transform = `scale(${scale})`;
  overlay.style.opacity = progress;
  base.style.opacity = 1 - progress;
});
