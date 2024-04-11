document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("customPasswordInput");
  const showPasswordCheckbox = document.getElementById("showPasswordCheckbox");

  showPasswordCheckbox.addEventListener("change", function () {
    if (showPasswordCheckbox.checked) {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  });

  function createBubble() {
    const bubble = document.createElement("div");
    bubble.classList.add("bubble");

    const size = Math.random() * 50 + 20;
    bubble.style.width = size + "px";
    bubble.style.height = size + "px";

    const yPos = window.innerHeight;
    bubble.style.bottom = "0";

    const xPos = Math.random() * window.innerWidth;

    bubble.style.left = xPos + "px";

    document.body.appendChild(bubble);

    bubble.animate(
      [
        { bottom: "0", opacity: 1 },
        { bottom: yPos + "px", opacity: 0 },
      ],
      {
        duration: 4000,
        easing: "ease-in-out",
      }
    );

    setTimeout(() => {
      bubble.remove();
    }, 4000);
  }

  setInterval(createBubble, 1000);
});
