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

		@media (max-width: 991.98px) {
  .navbar-collapse {
    display: none;
  }

  .navbar-collapse.show {
    display: block;
    animation: fadeInDown 0.3s ease;
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
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
	<header id="site-header" class="w3l-header fixed-top">
		<!--/nav-->
		<nav class="navbar navbar-expand-lg navbar-light fill px-lg-0 py-0 px-3">
			<div class="container">
				<h1><a class="navbar-brand" href="{{ route('client.home') }}"><span class="fa fa-play icon-log" aria-hidden="true"></span> MyShowz</a></h1>
				<!-- if logo is image enable this   
				<a class="navbar-brand" href="#index.html">
					<img src="image-path" alt="Your logo" title="Your logo" style="height:35px;" />
				</a> -->
				<button id="menuToggleBtn" class="navbar-toggler collapsed" type="button"
	aria-controls="navbarSupportedContent"
	aria-expanded="false"
	aria-label="Toggle navigation">
	<span class="fa icon-expand fa-bars"></span>
	<span class="fa icon-close fa-times"></span>
</button>


				<div class="navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item active">
							<a class="nav-link" href="index.html">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="movies.html">Movies</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="about.html">About</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="Contact_Us.html">Contact</a>
						</li>
					</ul>

					<!--/search-right-->
					<div class="search-right">
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
				<!-- toggle switch for light and dark theme -->
				<div class="mobile-position">
					<nav class="navigation" style="display: flex; align-items: center; justify-content: flex-end; gap: 30px;">
						<div class="user-navigation" style="position: relative;">
							<button onclick="toggleUserDropdown()" class="user-container" style="background: none; border: none; cursor: pointer;">
								<i class="fa fa-user-circle-o" style="font-size: 30px;"></i>
							</button>

							<ul id="userDropdown" style=" display: none; position: absolute; right: 0; top: 120%; background-color: white; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.1); list-style: none; padding: 0;
								margin: 0;
								min-width: 150px;
								z-index: 1000;">

								@auth
								<li><a href="/profile" style="display: block; padding: 10px; text-decoration: none;">Tài khoản</a></li>
								@hasanyrole(['admin', 'staff'])
								<li><a href="{{ route('admin.movies.index') }}" style="display: block; padding: 10px; text-decoration: none;">Quản lý</a></li>
								@endhasanyrole
								<li>
									<form method="POST" action="{{ route('logout') }}" style="margin: 0;">
										@csrf
										<button type="submit" style="background: none; border: none; padding: 10px; text-align: left; width: 100%; cursor: pointer;">
											Đăng xuất
										</button>
									</form>
								</li>
							@else
								<li><a href="{{ route('login') }}" style="display: block; padding: 10px; text-decoration: none;">Đăng nhập</a></li>
								<li><a href="{{ route('register') }}" style="display: block; padding: 10px; text-decoration: none;">Đăng ký</a></li>
							@endauth
							</ul>
						</div>
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

	// Toggle menu mobile
	const toggleBtn = document.getElementById('menuToggleBtn');
	const nav = document.getElementById('navbarSupportedContent');

	toggleBtn.addEventListener('click', function () {
		nav.classList.toggle('show');

		// Nếu đang mở menu => chặn cuộn
		if (nav.classList.contains('show')) {
			document.body.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = ''; // hoặc 'auto'
		}
	});

	// Reset khi resize về màn hình PC
	window.addEventListener('resize', () => {
		if (window.innerWidth >= 992) {
			document.body.style.overflow = ''; // Cho phép cuộn lại
			nav.classList.remove('show');     // Đảm bảo menu đóng
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