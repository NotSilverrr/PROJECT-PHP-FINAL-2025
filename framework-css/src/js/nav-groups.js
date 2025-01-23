document.addEventListener("DOMContentLoaded", (event) => {
	document.querySelectorAll(".nav-groups__toggle").forEach(function (elem) {
		elem.addEventListener("click", (evt) => {
            const nav = elem.closest('.nav-groups');
            nav.classList.toggle('nav-groups--collapsed');
        });
	});
});
