(function () {
	'use strict';

	// Confirm dialogs for destructive actions (revoke/reactivate/delete).
	document.querySelectorAll('form[data-confirm]').forEach(function (form) {
		form.addEventListener('submit', function (event) {
			if (!window.confirm(form.getAttribute('data-confirm'))) {
				event.preventDefault();
			}
		});
	});

	// Prevent accidental double-submits on regular forms.
	document.querySelectorAll('form:not([data-confirm])').forEach(function (form) {
		form.addEventListener('submit', function () {
			var btn = form.querySelector('button[type="submit"]');
			if (btn && !btn.disabled) {
				window.setTimeout(function () { btn.disabled = true; }, 0);
			}
		});
	});

	// Off-canvas sidebar for small screens.
	var shell = document.querySelector('.app-shell');
	var toggle = document.getElementById('sidebarToggle');
	var backdrop = document.getElementById('appBackdrop');

	function closeSidebar() {
		if (!shell) return;
		shell.classList.remove('sidebar-open');
		if (toggle) toggle.setAttribute('aria-expanded', 'false');
	}

	if (shell && toggle) {
		toggle.addEventListener('click', function () {
			var open = shell.classList.toggle('sidebar-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
	if (backdrop) {
		backdrop.addEventListener('click', closeSidebar);
	}
})();
