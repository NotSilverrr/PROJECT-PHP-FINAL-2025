document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".side-bar__toggle").forEach(function (elem) {
		elem.addEventListener("click", () => {
            const nav = elem.closest('.side-bar');
            nav.classList.toggle('side-bar--collapsed');
        });
	});
});
