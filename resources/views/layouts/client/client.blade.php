<!doctype html>
<html lang="zxx">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Home</title>

	<link rel="stylesheet" href="{{ asset('client_assets/assets/css/style-starter.css') }}">
	<link href="//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/as-alert-message.min.css') }}">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.0.14/css/all.min.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<!-- ..............Booking............... -->
	<link rel="stylesheet" href="https://npmcdn.com/flickity@2/dist/flickity.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/progress.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/ticket-booking.css') }}">

	<!-- ..............For progress-bar............... -->
	<link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/e-ticket.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/payment.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<style>
	.search-hny {
		font-size: 14px; /* Giảm kích thước chữ */
		padding: 8px 16px; /* Giảm padding để nút nhỏ hơn */
		line-height: 1.5; /* Điều chỉnh chiều cao dòng */
	}
	.search-hny .fa-search {
		font-size: 12px; /* Giảm kích thước biểu tượng tìm kiếm */
		margin-left: 8px; /* Giảm khoảng cách bên trái */
	}

	/* Custom styles for search and filter section */
	.search-filter-section {
		background: rgba(255, 255, 255, 0.1);
		border-radius: 25px;
		padding: 8px 15px;
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.2);
		margin: 0 15px;
		display: flex !important;
		align-items: center;
		flex: 1;
		max-width: 500px;
		min-width: 300px;
	}

	.search-filter-section form {
		width: 100%;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.search-input-wrapper {
		flex: 1;
		position: relative;
	}

	.search-input-wrapper input {
		background: rgba(255, 255, 255, 0.95);
		transition: all 0.3s ease;
		border: 1px solid rgba(255, 255, 255, 0.5);
		width: 100%;
		padding: 8px 12px 8px 35px;
		border-radius: 20px;
		font-size: 14px;
	}

	.search-input-wrapper input:focus {
		background: white;
		box-shadow: 0 0 10px rgba(255, 107, 107, 0.3);
		border-color: #ff6b6b;
		outline: none;
	}

	.filter-dropdown select {
		background: rgba(255, 255, 255, 0.95);
		transition: all 0.3s ease;
		border: 1px solid rgba(255, 255, 255, 0.5);
		border-radius: 20px;
		padding: 8px 12px;
		font-size: 14px;
		min-width: 120px;
	}

	.filter-dropdown select:focus {
		background: white;
		box-shadow: 0 0 10px rgba(255, 107, 107, 0.3);
		border-color: #ff6b6b;
		outline: none;
	}

	.search-btn {
		background: linear-gradient(45deg, #ff6b6b, #ffa500);
		color: white;
		border: none;
		border-radius: 20px;
		padding: 8px 15px;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.search-btn:hover {
		transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
	}

	.clear-btn {
		border: 1px solid #6c757d;
		color: #6c757d;
		background: rgba(255, 255, 255, 0.9);
		border-radius: 20px;
		padding: 8px 12px;
		text-decoration: none;
		transition: all 0.3s ease;
	}

	.clear-btn:hover {
		background: #6c757d;
		color: white;
		text-decoration: none;
	}

	/* Compact search section styles - Beautiful Design */
	.compact-search-section {
		display: flex !important;
		align-items: center !important;
		visibility: visible !important;
		opacity: 1 !important;
		position: relative !important;
		z-index: 999 !important;
	}

	.compact-search-section form {
		position: relative;
	}

	.compact-search-section input {
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
	}

	.compact-search-section input:focus {
		outline: none !important;
	}

	.compact-search-section input::placeholder {
		color: #888;
		font-style: italic;
	}

	/* Force show compact search on desktop */
	@media (min-width: 992px) {
		.compact-search-section {
			display: flex !important;
			visibility: visible !important;
		}
		
		.navbar-collapse {
			display: flex !important;
		}
		
		.navbar-collapse.collapse {
			display: flex !important;
		}
		
		.navbar-collapse.collapse:not(.show) {
			display: flex !important;
		}
	}

	/* Responsive styles */
	@media (max-width: 991px) {
		.search-filter-section {
			margin: 10px 0;
			min-width: 100%;
			max-width: 100%;
		}
		
		.search-filter-section form {
			flex-wrap: wrap;
			gap: 10px;
		}
		
		.search-input-wrapper {
			flex: 1 1 100%;
			margin-bottom: 10px;
		}
		
		.filter-dropdown {
			flex: 1 1 auto;
		}
		
		.filter-dropdown select {
			min-width: 150px;
		}

		.navbar-nav {
			margin-bottom: 15px;
		}

		/* Hide compact search on mobile, show only main search */
		.compact-search-section {
			display: none !important;
		}
	}

	@media (max-width: 768px) {
		.search-filter-section {
			padding: 10px;
			border-radius: 15px;
		}
		
		.search-filter-section form {
			flex-direction: column;
			align-items: stretch;
			gap: 15px;
		}
		
		.search-input-wrapper,
		.filter-dropdown {
			width: 100%;
		}
		
		.filter-dropdown select {
			min-width: 100%;
		}

		.search-btn,
		.clear-btn {
			width: 100%;
			padding: 10px;
			justify-content: center;
		}

		.navbar-toggler {
			margin-left: auto;
		}

		.compact-search-section {
			display: none !important;
		}
	}

	/* Desktop specific styles for compact search */
	@media (min-width: 992px) {
		.compact-search-section input {
			width: 180px;
		}
		
		.compact-search-section select {
			min-width: 120px;
		}
	}
</style>

	 <!-- JavaScript cho kiểm tra đăng nhập và hiển thị popup -->
    <script>
        function showLoginPrompt(event, redirectUrl) {
            event.preventDefault();
            Swal.fire({
                title: 'Yêu cầu đăng nhập',
                text: 'Bạn cần đăng nhập để đặt vé. Chuyển tới trang đăng nhập?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(redirectUrl);
                }
            });
        }
    </script>
<body>
	<!-- header -->
	@if(!request()->routeIs(['login', 'register', 'password.request', 'password.reset', 'password.confirm', 'verification.notice']))
	<header id="site-header" class="w3l-header fixed-top">
		<!--/nav-->
		<nav class="navbar navbar-expand-lg navbar-light fill px-lg-0 py-0 px-3">
			<div class="container">
				<h1><a class="navbar-brand" href="{{ route('client.home') }}"><span class="fa fa-play icon-log" aria-hidden="true"></span> MyShowz</a></h1>
				<!-- if logo is image enable this   
				<a class="navbar-brand" href="#index.html">
					<img src="image-path" alt="Your logo" title="Your logo" style="height:35px;" />
				</a> -->
				<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<!-- <span class="navbar-toggler-icon"></span> -->
					<span class="fa icon-expand fa-bars"></span>
					<span class="fa icon-close fa-times"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="{{ route('client.home') }}">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('client.movies') }}">Movies</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="about.html">About</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="Contact_Us.html">Contact</a>
						</li>
					</ul>



					<!--/search-right - Keep original search popup for backwards compatibility -->
					<div class="search-right" style="display: none;">
						<a href="#search" class="btn search-hny mr-lg-3 mt-lg-0 mt-4" title="search">Search <span class="fa fa-search ml-3" aria-hidden="true"></span></a>
						<!-- search popup -->
						<div id="search" class="pop-overlay">
							<div class="popup">
								<form action="#" method="post" class="search-box">
									<input type="search" placeholder="Search your Keyword" name="search" required="required" autofocus="">
									<button type="submit" class="btn"><span class="fa fa-search" aria-hidden="true"></span></button>
								</form>
								<div class="browse-items">
									<h3 class="hny-title two mt-md-5 mt-4">Browse all:</h3>
									<ul class="search-items">
										<li><a href="movies.html">Action</a></li>
										<li><a href="movies.html">Drama</a></li>
										<li><a href="movies.html">Family</a></li>
										<li><a href="movies.html">Thriller</a></li>
										<li><a href="movies.html">Commedy</a></li>
										<li><a href="movies.html">Romantic</a></li>
										<li><a href="movies.html">Tv-Series</a></li>
										<li><a href="movies.html">Horror</a></li>
									</ul>
								</div>
							</div>
							<a class="close" href="#close">×</a>
						</div>
						<!-- /search popup -->
					</div>
				</div>

				<!-- Compact Search Section - Beautiful Search Box -->
				<div class="compact-search-section d-none d-lg-flex" style="display: flex !important; align-items: center; margin-right: 20px; position: relative; z-index: 1000;">
					<form action="{{ Request::url() }}" method="GET" style="position: relative;">
						<!-- Preserve other query parameters -->
						@foreach(request()->except(['search', 'page']) as $key => $value)
							<input type="hidden" name="{{ $key }}" value="{{ $value }}">
						@endforeach
						
						<!-- Beautiful Search Input -->
						<div style="position: relative; display: flex; align-items: center;">
							<input type="text" 
								   name="search" 
								   placeholder="Tìm kiếm phim..." 
								   value="{{ request('search') }}"
								   style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85)); 
								          border: 2px solid rgba(255, 255, 255, 0.3); 
								          border-radius: 25px; 
								          padding: 12px 50px 12px 20px; 
								          font-size: 14px; 
								          width: 300px; 
								          height: 45px; 
								          box-sizing: border-box;
								          transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
								          backdrop-filter: blur(10px);
								          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
								          color: #333;
								          font-weight: 500;"
								   onfocus="this.style.background='linear-gradient(135deg, #ffffff, #f8f9fa)'; 
								           this.style.boxShadow='0 8px 25px rgba(255, 107, 107, 0.2), 0 0 0 3px rgba(255, 107, 107, 0.1)'; 
								           this.style.borderColor='#ff6b6b'; 
								           this.style.transform='translateY(-2px)';"
								   onblur="this.style.background='linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85))'; 
								          this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)'; 
								          this.style.borderColor='rgba(255, 255, 255, 0.3)'; 
								          this.style.transform='translateY(0)';">
							
							<!-- Search Icon with Animation -->
							<!-- Clear Button (only show when there's search) -->
							@if(request('search'))
								<a href="{{ Request::url() }}" 
								   style="position: absolute; 
								          right: 50px; 
								          top: 50%; 
								          transform: translateY(-50%); 
								          background: rgba(108, 117, 125, 0.2); 
								          color: #6c757d; 
								          border: none; 
								          border-radius: 50%; 
								          padding: 0; 
								          width: 24px; 
								          height: 24px; 
								          display: flex; 
								          align-items: center; 
								          justify-content: center; 
								          text-decoration: none; 
								          transition: all 0.3s ease;"
								   onmouseover="this.style.background='#6c757d'; this.style.color='white'; this.style.transform='translateY(-50%) scale(1.1)';"
								   onmouseout="this.style.background='rgba(108, 117, 125, 0.2)'; this.style.color='#6c757d'; this.style.transform='translateY(-50%) scale(1)';">
									<i class="fa fa-times" style="font-size: 12px;"></i>
								</a>
							@endif
						</div>
					</form>
				</div>

				<!-- User Account Section -->
				<div class="Login_SignUp ml-3" id="login" style="font-size: 2rem; display: inline-block; position: relative;">
					<button onclick="toggleUserDropdown()" class="user-container" style="background: none; border: none; cursor: pointer; color: inherit;">
						<i class="fa fa-user-circle-o" style="font-size: 30px;"></i>
					</button>
					

						<ul id="userDropdown" style="display: none; position: absolute; right: 0; top: 120%; background-color: white; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.1); list-style: none; padding: 0; margin: 0; min-width: 150px; z-index: 1000; border-radius: 8px;">
							@auth
								<li><a href="/profile" style="display: block; padding: 8px 12px; text-decoration: none; color: #333; font-size: 12px;">Tài khoản</a></li>
								@hasanyrole(['admin', 'staff'])
									<li><a href="{{ route('admin.dashboard') }}" style="display: block; padding: 8px 12px; text-decoration: none; color: #333; font-size: 12px;">Quản lý</a></li>
								@endhasanyrole
								<li>
									<form method="POST" action="{{ route('logout') }}" style="margin: 0;">
										@csrf
										<button type="submit" style="background: none; border: none; padding: 8px 12px; text-align: left; width: 100%; cursor: pointer; color: #333; font-size: 12px;">
											Đăng xuất
										</button>
									</form>
								</li>
							@else
								<li><a href="{{ route('login') }}" style="display: block; padding: 8px 12px; text-decoration: none; color: #333; font-size: 12px;">Đăng nhập</a></li>
								<li><a href="{{ route('register') }}" style="display: block; padding: 8px 12px; text-decoration: none; color: #333; font-size: 12px;">Đăng ký</a></li>
							@endauth
						</ul>
					</div>
				</div>
				<!-- toggle switch for light and dark theme -->
				<div class="mobile-position">
					<nav class="navigation" style="display: flex; align-items: center; justify-content: flex-end; gap: 30px;">
						<div class="theme-switch-wrapper">
							<label class="theme-switch" for="checkbox">
								<input type="checkbox" id="checkbox">
								<div class="mode-container">
									<i class="gg-sun"></i>
									<i class="gg-moon"></i>
								</div>
							</label>
						</div>
					</nav>
				</div>
			</div>
		</nav>
	</header>
	@endif
	<!-- main-slider -->
	@yield('content')
</body>

</html>

<script>
	function toggleUserDropdown() {
		const dropdown = document.getElementById('userDropdown');
		dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
	}

	// Ẩn dropdown khi click ra ngoài
	document.addEventListener('click', function (e) {
		const dropdown = document.getElementById('userDropdown');
		const button = document.querySelector('.user-container');
		if (!button.contains(e.target) && !dropdown.contains(e.target)) {
			dropdown.style.display = 'none';
		}
	});

	// Enhanced search functionality
	document.addEventListener('DOMContentLoaded', function() {
		// Force show navbar collapse and compact search on desktop
		function ensureNavbarVisible() {
			if (window.innerWidth >= 992) {
				const navbarCollapse = document.getElementById('navbarSupportedContent');
				const compactSearch = document.querySelector('.compact-search-section');
				
				if (navbarCollapse) {
					navbarCollapse.classList.add('show');
					navbarCollapse.style.display = 'flex';
				}
				
				if (compactSearch) {
					compactSearch.style.display = 'flex';
					compactSearch.style.visibility = 'visible';
					compactSearch.style.opacity = '1';
				}
			}
		}
		
		// Run immediately and on resize
		ensureNavbarVisible();
		window.addEventListener('resize', ensureNavbarVisible);
		
		// Search functionality for compact search
		const compactSearchForm = document.querySelector('.compact-search-section form');
		const compactSearchInput = compactSearchForm ? compactSearchForm.querySelector('input[name="search"]') : null;
		
		if (compactSearchInput) {
			// Submit on Enter key
			compactSearchInput.addEventListener('keypress', function(e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					compactSearchForm.submit();
				}
			});
			
			// Add search suggestions (optional enhancement)
			let searchTimeout;
			compactSearchInput.addEventListener('input', function() {
				clearTimeout(searchTimeout);
				const query = this.value.trim();
				
				if (query.length >= 2) {
					searchTimeout = setTimeout(() => {
						// You can implement live search suggestions here
						console.log('Searching for:', query);
					}, 300);
				}
			});
		}
	});
</script>
<!-- responsive tabs -->
<script type="text/javascript" src="{{ asset('client_assets/assets/js/as-alert-message.min.js') }}"></script>
<!-- <script src="client_assets/assets/js/jquery-1.9.1.min.js"></script> -->
<!-- **Ghi chú**: Xóa jQuery 1.9.1 để tránh xung đột với jQuery 3.3.1. Chỉ giữ một phiên bản jQuery. -->
<script src="{{ asset('client_assets/assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('client_assets/assets/js/easyResponsiveTabs.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function () {
		//Horizontal Tab
		$('#parentHorizontalTab').easyResponsiveTabs({
			type: 'default', //Types: default, vertical, accordion
			width: 'auto', //auto or any width like 600px
			fit: true, // 100% fit in a container
			tabidentify: 'hor_1', // The tab groups identifier
			activate: function (event) { // Callback function if tab is switched
				var $tab = $(this);
				var $info = $('#nested-tabInfo');
				var $name = $('span', $info);
				$name.text($tab.text());
				$info.show();
			}
		});
	});
</script>
<!--/theme-change-->
<!-- <script src="{{ asset('client_assets/assets/js/theme-change.js') }}"></script> -->
<!-- **Ghi chú**: Tạm thời comment `theme-change.js` do lỗi `Identifier 'toggleSwitch' has already been declared`. Cần sửa file này trước khi sử dụng lại. -->
<script src="{{ asset('client_assets/assets/js/owl.carousel.js') }}"></script>
<!-- script for banner slider-->
<script>
	$(document).ready(function () {
		$('.owl-one').owlCarousel({
			stagePadding: 280,
			loop: true,
			margin: 20,
			nav: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 5000,
			autoplaySpeed: 1000,
			autoplayHoverPause: false,
			responsive: {
				0: {
					items: 1,
					stagePadding: 40,
					nav: false
				},
				480: {
					items: 1,
					stagePadding: 60,
					nav: true
				},
				667: {
					items: 1,
					stagePadding: 80,
					nav: true
				},
				1000: {
					items: 1,
					nav: true
				}
			}
		})
	})
</script>
<script>
	$(document).ready(function () {
		$('.owl-three').owlCarousel({
			loop: true,
			margin: 20,
			nav: false,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 5000,
			autoplaySpeed: 1000,
			autoplayHoverPause: false,
			responsive: {
				0: {
					items: 2,
					nav: false
				},
				480: {
					items: 2,
					nav: true
				},
				667: {
					items: 3,
					nav: true
				},
				1000: {
					items: 5,
					nav: true
				}
			}
		})
	})
</script>
<script>
	$(document).ready(function () {
		$('.owl-mid').owlCarousel({
			loop: true,
			margin: 0,
			nav: false,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 5000,
			autoplaySpeed: 1000,
			autoplayHoverPause: false,
			responsive: {
				0: {
					items: 1,
					nav: false
				},
				480: {
					items: 1,
					nav: false
				},
				667: {
					items: 1,
					nav: true
				},
				1000: {
					items: 1,
					nav: true
				}
			}
		})
	})
</script>
<!-- script for owlcarousel -->
<script src="{{ asset('client_assets/assets/js/jquery.magnific-popup.min.js') }}"></script>
<script>
	$(document).ready(function () {
		$('.popup-with-zoom-anim').magnificPopup({
			type: 'iframe',
			// <!-- **Ghi chú**: Đổi từ `type: 'inline'` sang `type: 'iframe'` để hỗ trợ hiển thị iframe Vimeo trong slider. -->
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
		});

		$('.popup-with-move-anim').magnificPopup({
			type: 'iframe',
			// <!-- **Ghi chú**: Đổi từ `type: 'inline'` sang `type: 'iframe'` để đảm bảo nhất quán với các popup khác. -->
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-slide-bottom'
		});
	});
</script>
<!-- disable body scroll which navbar is in active -->
<script>
	$(function () {
		$('.navbar-toggler').click(function () {
			$('body').toggleClass('noscroll');
		})
	});
</script>
<!-- disable body scroll which navbar is in active -->

<!--/MENU-JS-->
<script>
	$(window).on("scroll", function () {
		var scroll = $(window).scrollTop();

		if (scroll >= 80) {
			$("#site-header").addClass("nav-fixed");
		} else {
			$("#site-header").removeClass("nav-fixed");
		}
	});

	//Main navigation Active Class Add Remove
	$(".navbar-toggler").on("click", function () {
		$("header").toggleClass("active");
	});
	$(document).on("ready", function () {
		if ($(window).width() > 991) {
			$("header").removeClass("active");
		}
		$(window).on("resize", function () {
			if ($(window).width() > 991) {
				$("header").removeClass("active");
			}
		});
	});
</script>

<script src="{{ asset('client_assets/assets/js/bootstrap.min.js') }}"></script>
<!-- <script src="{{ asset('client_assets/assets/js/sign-in.js') }}"></script> -->
<!-- **Ghi chú**: Tạm thời comment `sign-in.js` do lỗi `Cannot read properties of null` tại dòng 87. Cần sửa file này trước khi sử dụng lại. -->

<script>
	let prevId = "1";

	window.onload = function () {
		try {
			document.getElementById("screen-next-btn").disabled = true;
		} catch (e) {
			console.log("Element 'screen-next-btn' not found");
	}
		// <!-- **Ghi chú**: Thêm try-catch để xử lý lỗi `Cannot set properties of null` khi `screen-next-btn` không tồn tại trong DOM. -->
	}

	function timeFunction() {
		try {
			document.getElementById("screen-next-btn").disabled = false;
		} catch (e) {
			console.log("Element 'screen-next-btn' not found");
		}
		// <!-- **Ghi chú**: Thêm try-catch để xử lý lỗi tương tự cho hàm `timeFunction`. -->
	}

	function myFunction(id) {
		try {
			document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
			document.getElementById(id).style.background = "#df0e62";
			prevId = id;
		} catch (e) {
			console.log("Error in myFunction: ", e);
		}
		// <!-- **Ghi chú**: Thêm try-catch để xử lý lỗi nếu các phần tử DOM không tồn tại. -->
	}
</script>

<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
<!-- **Ghi chú**: Xóa script CDN Flickity vì không cần thiết cho slider và tránh tải tài nguyên thừa. -->
<!-- <script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'></script> -->
<!-- **Ghi chú**: Xóa script CDN Bootstrap vì đã có `bootstrap.min.js` cục bộ. -->
<!-- <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script> -->
<!-- **Ghi chú**: Xóa jQuery 3.2.1 từ CDN để tránh xung đột với jQuery 3.3.1 cục bộ. -->
<script src="{{ asset('client_assets/assets/js/theme-change.js') }}"></script>
<script src="{{ asset('client_assets/assets/js/ticket-booking.js') }}"></script>