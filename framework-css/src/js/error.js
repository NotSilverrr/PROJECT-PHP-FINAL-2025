document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".error__close").forEach(function (elem) {
    elem.addEventListener("click", () => {
      console.log(elem);
      elem.closest('.error').remove();

    })
  })
})