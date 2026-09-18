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

  const maxScroll = 300;

  let progress = scrolled / maxScroll;
  progress = Math.min(Math.max(progress, 0), 1);

  const scale = 1 + progress * 0.5;

  overlay.style.transform = `scale(${scale})`;
  overlay.style.opacity = progress;
  base.style.opacity = 1 - progress;
});
