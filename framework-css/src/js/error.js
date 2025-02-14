document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".error").forEach(function (elem) {
    setTimeout(() => {
      elem.classList.add("show");
    }, 10);

    setTimeout(() => {
      elem.classList.remove("show");
      elem.classList.add("hide");
      setTimeout(() => elem.remove(), 500);
    }, 5000);
  });

  document.querySelectorAll(".error__close").forEach(function (closeBtn) {
    closeBtn.addEventListener("click", () => {
      let errorElem = closeBtn.closest('.error');
      errorElem.classList.remove("show");
      errorElem.classList.add("hide");

      setTimeout(() => errorElem.remove(), 500);
    });
  });
});
