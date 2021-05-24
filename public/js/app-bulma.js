jQuery(document).ready(function ($) {
	
	var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
	if ($navbarBurgers.length > 0) {
		$navbarBurgers.forEach(function ($el) {
			$el.addEventListener('click', function () {
				var target = $el.dataset.target;
				var $target = document.getElementById(target);
				$el.classList.toggle('is-active');
				$target.classList.toggle('is-active');
				document.getElementById('appcontent').classList.toggle('is-hidden');
			});
		});
	}

	$(document).on('click', '.modal-button', function () {
		var target = $(this).data('target');
		$('html').addClass('is-clipped');
		$(target).addClass('is-active');
	});
	
	$(document).on('click', '.modal .modal-background, .modal-close', function () {
		closeModalHelper();
	});
	
	$(document).on('click', '.modal-card-head .delete, .modal-card-foot .is-cancel', function () {
		closeModalHelper();
	});

	$(document).on('click', 'a.target-link', function (e) {
		e.preventDefault();
		var item = $(this);
		var target_url = item.attr('href').split('#')[1];
		location.hash = target_url;
	});
});

function closeModalHelper(){
	$('html').removeClass('is-clipped');
	$('.modal').removeClass('is-active');
}

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
	}
});