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
    
    <!-- Font Awesome CSS với priority cao hơn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* Font Awesome Icon Fixes - Ưu tiên cao nhất */
        .fas, .far, .fab, .fal, .fad {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            display: inline-block !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
        }

        .far {
            font-weight: 400 !important;
        }

        /* Specific icon fixes */
        .fa-search::before {
            content: "\f002" !important;
        }

        .fa-play::before {
            content: "\f04b" !important;
        }

        .fa-bars::before {
            content: "\f0c9" !important;
        }

        .fa-times::before {
            content: "\f00d" !important;
        }

        .fa-user-circle::before {
            content: "\f2bd" !important;
        }

        .fa-film::before {
            content: "\f008" !important;
        }

        .fa-clock::before {
            content: "\f017" !important;
        }

        .fa-spinner::before {
            content: "\f110" !important;
        }

        .fa-exclamation-triangle::before {
            content: "\f071" !important;
        }

        /* Override any conflicting styles */
        .icon-log, .icon-expand, .icon-close {
            display: inline-block !important;
            visibility: visible !important;
        }

        /* Search Button in Navigation */
        .search-btn-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .search-btn-nav:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .search-btn-nav i {
            font-size: 16px !important;
            color: white !important;
        }

        /* Modern Search Styles */
        .modern-search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }

        .search-form {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50px;
            padding: 4px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .search-form:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .search-input-container {
            position: relative;
            background: white;
            border-radius: 46px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 16px 20px;
            font-size: 16px;
            background: transparent;
            color: #333;
        }

        .search-input::placeholder {
            color: #999;
            font-weight: 400;
        }

        .search-genre-select {
            border: none;
            outline: none;
            background: transparent;
            padding: 16px 15px;
            font-size: 14px;
            color: #666;
            border-left: 1px solid #eee;
            cursor: pointer;
            min-width: 120px;
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 16px 20px;
            border-radius: 0 46px 46px 0;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 60px;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: scale(1.05);
        }

        .search-btn i {
            font-size: 18px !important;
            color: white !important;
        }

        /* Search Results Dropdown */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 10px;
            display: none;
        }

        .search-results.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-result-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-result-item:hover {
            background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
            transform: translateX(5px);
        }

        .search-result-item:last-child {
            border-bottom: none;
            border-radius: 0 0 20px 20px;
        }

        .search-result-item:first-child {
            border-radius: 20px 20px 0 0;
        }

        .search-result-poster {
            width: 60px;
            height: 90px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .search-result-item:hover .search-result-poster {
            transform: scale(1.05);
        }

        .search-result-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.3;
        }

        .search-result-meta {
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .search-result-genre {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        /* Header trong search results */
        .search-results-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border-radius: 20px 20px 0 0;
            font-size: 14px;
        }

        /* Status badges */
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .status-showing {
            background: #28a745;
            color: white;
        }

        .status-upcoming {
            background: #ffc107;
            color: #333;
        }

        /* Search popup styles */
        #search {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            backdrop-filter: blur(5px);
        }

        #search.show {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .popup {
            background: white;
            border-radius: 30px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 30px;
            color: #999;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .close:hover {
            color: #667eea;
            transform: scale(1.1);
            text-decoration: none;
        }

        .browse-items {
            margin-top: 30px;
        }

        .hny-title {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 700;
        }

        .search-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            list-style: none;
            padding: 0;
        }

        .search-items li a {
            display: block;
            padding: 12px 16px;
            background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
            border-radius: 15px;
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .search-items li a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }

        /* Navigation fixes */
        .mobile-position {
            display: flex;
            align-items: center;
        }

        .navigation {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
        }

        /* Navbar icon fixes */
        .navbar .fas {
            font-size: 16px !important;
            color: inherit !important;
        }

        .navbar-brand .fa-play {
            font-size: 18px !important;
            color: #667eea !important;
            margin-right: 8px;
        }

        .navbar-toggler .fas {
            font-size: 18px !important;
        }

        /* User dropdown icon */
        .user-container .fa-user-circle {
            font-size: 30px !important;
            color: #667eea !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-search-container {
                max-width: 100%;
                margin: 0 15px;
            }

            .search-input {
                padding: 14px 16px;
                font-size: 14px;
            }

            .search-genre-select {
                padding: 14px 12px;
                font-size: 13px;
                min-width: 100px;
            }

            .search-btn {
                padding: 14px 16px;
                min-width: 50px;
            }

            .search-btn i {
                font-size: 16px !important;
            }

            .search-btn-nav {
                width: 35px;
                height: 35px;
                margin-right: 10px;
            }

            .search-btn-nav i {
                font-size: 14px !important;
            }
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://npmcdn.com/flickity@2/dist/flickity.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/progress.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/ticket-booking.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/e-ticket.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('client_assets/assets/css/payment.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</head>

<body>
    <header id="site-header" class="w3l-header fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light fill px-lg-0 py-0 px-3">
            <div class="container">
                <h1><a class="navbar-brand" href="{{ route('client.home') }}"><i class="fas fa-play icon-log" aria-hidden="true"></i> MyShowz</a></h1>
                
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas icon-expand fa-bars"></i>
                    <i class="fas icon-close fa-times"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('client.home') }}">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.movies') }}">Phim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">Giới thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Liên hệ</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Fixed Navigation with Search and User -->
                <div class="mobile-position">
                    <nav class="navigation">
                        <!-- Search Button -->
                        <button onclick="openSearchPopup()" class="search-btn-nav" title="Tìm kiếm phim">
                            <i class="fas fa-search"></i>
                        </button>

                        <!-- User Navigation -->
                        <div class="user-navigation" style="position: relative;">
                            <button onclick="toggleUserDropdown()" class="user-container" style="background: none; border: none; cursor: pointer;">
                                <i class="far fa-user-circle" style="font-size: 30px;"></i>
                            </button>

                            <ul id="userDropdown" style="display: none; position: absolute; right: 0; top: 120%; background-color: white; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.1); list-style: none; padding: 0; margin: 0; min-width: 150px; z-index: 1000;">
                                @auth
                                <li><a href="/profile" style="display: block; padding: 10px; text-decoration: none; color: #333;">Tài khoản</a></li>
                                <li><a href="/my-bookings" style="display: block; padding: 10px; text-decoration: none; color: #333;">Lịch sử đơn hàng</a></li>
                                @hasanyrole(['admin', 'staff'])
                                <li><a href="{{ route('admin.dashboard') }}" style="display: block; padding: 10px; text-decoration: none; color: #333;">Quản lý</a></li>
                                @endhasanyrole
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; padding: 10px; text-align: left; width: 100%; cursor: pointer; color: #333;">Đăng xuất</button>
                                    </form>
                                </li>
                                @else
                                <li><a href="{{ route('login') }}" style="display: block; padding: 10px; text-decoration: none; color: #333;">Đăng nhập</a></li>
                                <li><a href="{{ route('register') }}" style="display: block; padding: 10px; text-decoration: none; color: #333;">Đăng ký</a></li>
                                @endauth
                            </ul>
                        </div>

                        <!-- Theme Switch -->
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

    <!-- Modern Search Popup -->
    <div id="search" class="pop-overlay">
        <div class="popup">
            <a class="close" href="#close">×</a>
            
            <div class="modern-search-container">
                <h3 class="hny-title text-center mb-4">Tìm kiếm phim</h3>
                
                <form class="search-form" id="movieSearchForm">
                    <div class="search-input-container">
                        <input type="text" 
                               class="search-input" 
                               id="movieSearchInput"
                               placeholder="Nhập tên phim bạn muốn tìm..."
                               autocomplete="off">
                        
                        <select class="search-genre-select" id="genreSelect">
                            <option value="">Tất cả thể loại</option>
                        </select>
                        
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <div class="search-results" id="searchResults"></div>
            </div>

            <div class="browse-items">
                <h3 class="hny-title two mt-md-5 mt-4">Duyệt theo thể loại:</h3>
                <ul class="search-items" id="genresList"></ul>
            </div>
        </div>
    </div>
    
    @yield('content')

    <!-- Modern Search JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('movieSearchInput');
            const genreSelect = document.getElementById('genreSelect');
            const searchResults = document.getElementById('searchResults');
            const searchForm = document.getElementById('movieSearchForm');
            const genresList = document.getElementById('genresList');
            
            let searchTimeout;
            let allGenres = [];

            // Load genres khi trang được tải
            loadGenres();

            // Event listeners
            if (searchInput) searchInput.addEventListener('input', handleSearchInput);
            if (genreSelect) genreSelect.addEventListener('change', handleGenreChange);
            if (searchForm) searchForm.addEventListener('submit', handleSearchSubmit);

            // Load danh sách thể loại
            function loadGenres() {
                fetch('/api/genres')
                    .then(response => {
                    console.log('Genres API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Genres loaded:', data);
                    allGenres = data;
                    populateGenreSelect();
                    populateGenresList();
                })
                .catch(error => {
                    console.error('Error loading genres:', error);
                    // Fallback genres
                    allGenres = [
                        {id: 1, name: 'Hành động'},
                        {id: 2, name: 'Tình cảm'},
                        {id: 3, name: 'Hài hước'},
                        {id: 4, name: 'Kinh dị'},
                        {id: 5, name: 'Khoa học viễn tưởng'},
                        {id: 6, name: 'Phiêu lưu'},
                    ];
                    populateGenreSelect();
                    populateGenresList();
                });
            }

            // Load phim đang chiếu khi mở popup
            function loadShowingMovies() {
                showSearchLoading();
                
                fetch('/api/showing-movies')
                    .then(response => {
                    console.log('Showing movies API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Showing movies loaded:', data);
                    displayShowingMovies(data.movies || []);
                })
                .catch(error => {
                    console.error('Error loading showing movies:', error);
                    showSearchError('Không thể tải danh sách phim đang chiếu');
                });
            }

            // Hiển thị phim đang chiếu
            function displayShowingMovies(movies) {
                if (!searchResults) return;
                
                if (movies.length === 0) {
                    searchResults.innerHTML = `
                        <div class="search-no-results">
                            <i class="fas fa-film"></i>
                            <div>Không có phim nào đang chiếu</div>
                            <small>Vui lòng quay lại sau</small>
                        </div>
                    `;
                } else {
                    const resultsHTML = `
                        <div style="padding: 15px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; border-radius: 20px 20px 0 0;">
                            <i class="fas fa-fire"></i> Phim đang chiếu hot (${movies.length} phim)
                        </div>
                        ${movies.map(movie => `
                            <div class="search-result-item" onclick="goToMovie(${movie.id})">
                                <img src="${getMovieImage(movie.image)}" 
                                     alt="${movie.name}" 
                                     class="search-result-poster"
                                     onerror="this.src='{{ asset('client_assets/assets/images/movie-placeholder.png') }}'">
                                <div class="search-result-info">
                                    <div class="search-result-title">${movie.name}</div>
                                    <div class="search-result-meta">
                                        <span><i class="fas fa-clock"></i> ${movie.duration_minutes || 'N/A'} phút</span>
                                        <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                                            <i class="fas fa-play"></i> Đang chiếu
                                        </span>
                                        ${movie.genres ? movie.genres.map(genre => 
                                            `<span class="search-result-genre">${genre.name}</span>`
                                        ).join('') : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    `;
                    
                    searchResults.innerHTML = resultsHTML;
                }
                
                searchResults.classList.add('show');
            }

            // Điền vào dropdown thể loại
            function populateGenreSelect() {
                if (!genreSelect) return;
                genreSelect.innerHTML = '<option value="">Tất cả thể loại</option>';
                allGenres.forEach(genre => {
                    const option = document.createElement('option');
                    option.value = genre.id;
                    option.textContent = genre.name;
                    genreSelect.appendChild(option);
                });
            }

            // Điền vào danh sách thể loại duyệt
            function populateGenresList() {
                if (!genresList) return;
                genresList.innerHTML = '';
                allGenres.forEach(genre => {
                    const li = document.createElement('li');
                    li.innerHTML = `<a href="{{ route('client.movies') }}?genre=${genre.id}">${genre.name}</a>`;
                    genresList.appendChild(li);
                });
            }

            // Xử lý input tìm kiếm - Giảm độ dài tối thiểu xuống 1 ký tự
            function handleSearchInput(e) {
                const query = e.target.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 1) {
                    // Nếu không có query, hiển thị phim đang chiếu
                    loadShowingMovies();
                    return;
                }

                // Giảm thời gian delay xuống 200ms để phản hồi nhanh hơn
                searchTimeout = setTimeout(() => {
                    performSearch(query, genreSelect ? genreSelect.value : '');
                }, 200);
            }

            // Xử lý thay đổi thể loại
            function handleGenreChange(e) {
                const query = searchInput ? searchInput.value.trim() : '';
                const genreId = e.target.value;
                
                if (query.length >= 1 || genreId) {
                    performSearch(query, genreId);
                } else {
                    // Nếu không có query và genre, hiển thị phim đang chiếu
                    loadShowingMovies();
                }
            }

            // Xử lý submit form
            function handleSearchSubmit(e) {
                e.preventDefault();
                const query = searchInput ? searchInput.value.trim() : '';
                const genreId = genreSelect ? genreSelect.value : '';
                
                if (query || genreId) {
                    // Chuyển hướng đến trang movies với query parameters
                    const params = new URLSearchParams();
                    if (query) params.append('search', query);
                    if (genreId) params.append('genre', genreId);
                    
                    window.location.href = `{{ route('client.movies') }}?${params.toString()}`;
                }
            }

            // Thực hiện tìm kiếm với error handling tốt hơn
            function performSearch(query, genreId) {
                console.log('Performing search:', { query, genreId });
                showSearchLoading();

                const params = new URLSearchParams();
                if (query) params.append('search', query);
                if (genreId) params.append('genre', genreId);

                const searchUrl = `/api/search-movies?${params.toString()}`;
                console.log('Search URL:', searchUrl);

                fetch(searchUrl)
                    .then(response => {
                    console.log('Search API response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Search results received:', data);
                    if (data.error) {
                        throw new Error(data.message || 'Lỗi API');
                    }
                    displaySearchResults(data.movies || [], query, genreId);
                })
                .catch(error => {
                    console.error('Search error details:', error);
                    showSearchError(`Lỗi tìm kiếm: ${error.message}`);
                });
            }

            // Hiển thị loading
            function showSearchLoading() {
                if (!searchResults) return;
                searchResults.innerHTML = `
                    <div class="search-loading">
                        <i class="fas fa-spinner"></i>
                        <div>Đang tìm kiếm...</div>
                    </div>
                `;
                searchResults.classList.add('show');
            }

            // Hiển thị kết quả tìm kiếm với fallback tốt hơn
            function displaySearchResults(movies, query, genreId) {
                if (!searchResults) return;
                
                console.log('Displaying search results:', movies);
                
                if (!movies || movies.length === 0) {
                    searchResults.innerHTML = `
                        <div class="search-no-results">
                            <i class="fas fa-film"></i>
                            <div>Không tìm thấy phim nào với từ khóa "${query}"</div>
                            <small>Thử tìm kiếm với từ khóa khác hoặc kiểm tra chính tả</small>
                        </div>
                    `;
                } else {
                    // Tạo header cho kết quả tìm kiếm
                    let headerText = 'Kết quả tìm kiếm';
                    if (query && genreId) {
                        const genreName = allGenres.find(g => g.id == genreId)?.name || 'thể loại';
                        headerText = `Tìm "${query}" trong ${genreName}`;
                    } else if (query) {
                        headerText = `Tìm kiếm: "${query}"`;
                    } else if (genreId) {
                        const genreName = allGenres.find(g => g.id == genreId)?.name || 'thể loại';
                        headerText = `Phim ${genreName}`;
                    }

                    const resultsHTML = `
                        <div style="padding: 15px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; border-radius: 20px 20px 0 0;">
                            <i class="fas fa-search"></i> ${headerText} (${movies.length} kết quả)
                        </div>
                        ${movies.map((movie, index) => `
                            <div class="search-result-item" onclick="goToMovie(${movie.id})" data-movie-id="${movie.id}">
                                <img src="${getMovieImage(movie.image)}" 
                                     alt="${movie.name || 'Movie poster'}" 
                                     class="search-result-poster"
                                     onerror="this.src='{{ asset('client_assets/assets/images/movie-placeholder.png') }}'">
                                <div class="search-result-info">
                                    <div class="search-result-title">${movie.name || 'Tên phim không có'}</div>
                                    <div class="search-result-meta">
                                        <span><i class="fas fa-clock"></i> ${movie.duration_minutes || 'N/A'} phút</span>
                                        ${movie.status === 'showing' ? 
                                            '<span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;"><i class="fas fa-play"></i> Đang chiếu</span>' :
                                            movie.status === 'upcoming' ?
                                            '<span style="background: #ffc107; color: #333; padding: 2px 8px; border-radius: 12px; font-size: 11px;"><i class="fas fa-calendar"></i> Sắp chiếu</span>' :
                                            ''
                                        }
                                        ${movie.genres && movie.genres.length > 0 ? movie.genres.map(genre => 
                                            `<span class="search-result-genre">${genre.name}</span>`
                                        ).join('') : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    `;
                    
                    searchResults.innerHTML = resultsHTML;
                }
                
                searchResults.classList.add('show');
            }

            // Hiển thị lỗi tìm kiếm với thông tin chi tiết
            function showSearchError(errorMessage = 'Đã có lỗi xảy ra khi tìm kiếm') {
                if (!searchResults) return;
                searchResults.innerHTML = `
                    <div class="search-no-results">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>${errorMessage}</div>
                        <small>Vui lòng thử lại sau hoặc liên hệ hỗ trợ</small>
                    </div>
                `;
                searchResults.classList.add('show');
            }

            // Helper function để xử lý URL hình ảnh
            function getMovieImage(imageUrl) {
                if (!imageUrl) {
                    return '{{ asset('client_assets/assets/images/movie-placeholder.png') }}';
                }
                
                // Nếu là URL đầy đủ (bắt đầu bằng http/https)
                if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
                    return imageUrl;
                }
                
                // Nếu là đường dẫn local, thêm storage prefix
                if (imageUrl.startsWith('/')) {
                    return imageUrl;
                }
                
                // Nếu là tên file, thêm storage path
                return '/storage/' + imageUrl;
            }

            // Ẩn kết quả tìm kiếm
            function hideSearchResults() {
                if (!searchResults) return;
                searchResults.classList.remove('show');
            }

            // Chuyển đến trang chi tiết phim
            window.goToMovie = function(movieId) {
                console.log('Going to movie:', movieId);
                window.location.href = `/movies/${movieId}`;
            };

            // Xử lý click outside để ẩn kết quả
            document.addEventListener('click', function(e) {
                if (searchResults && !searchResults.contains(e.target) && 
                    searchInput && !searchInput.contains(e.target) && 
                    genreSelect && !genreSelect.contains(e.target)) {
                    hideSearchResults();
                }
            });

            // Khi mở popup search, hiển thị phim đang chiếu
            window.openSearchPopup = function() {
                const searchPopup = document.getElementById('search');
                if (searchPopup) {
                    searchPopup.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    
                    // Hiển thị phim đang chiếu ngay khi mở popup
                    loadShowingMovies();
                    
                    // Focus vào input tìm kiếm
                    setTimeout(() => {
                        const movieSearchInput = document.getElementById('movieSearchInput');
                        if (movieSearchInput) {
                            movieSearchInput.focus();
                            movieSearchInput.value = ''; // Clear input
                        }
                    }, 100);
                }
            };
        });

        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            }
        }

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('userDropdown');
            const button = document.querySelector('.user-container');
            if (button && dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Xử lý mở/đóng search popup
        document.addEventListener('DOMContentLoaded', function() {
            const searchPopup = document.getElementById('search');
            const closeBtn = document.querySelector('#search .close');

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (searchPopup) {
                        searchPopup.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Đóng popup khi click outside
            if (searchPopup) {
                searchPopup.addEventListener('click', function(e) {
                    if (e.target === searchPopup) {
                        searchPopup.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Đóng popup khi nhấn ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && searchPopup && searchPopup.classList.contains('show')) {
                    searchPopup.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

    <!-- Original scripts -->
    <script type="text/javascript" src="{{ asset('client_assets/assets/js/as-alert-message.min.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/easyResponsiveTabs.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/owl.carousel.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/bootstrap.min.js') }}"></script>
    <script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
    <script src="{{ asset('client_assets/assets/js/theme-change.js') }}"></script>
    <script src="{{ asset('client_assets/assets/js/ticket-booking.js') }}"></script>

    <!-- Original jQuery scripts -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#parentHorizontalTab').easyResponsiveTabs({
                type: 'default',
                width: 'auto',
                fit: true,
                tabidentify: 'hor_1',
                activate: function (event) {
                    var $tab = $(this);
                    var $info = $('#nested-tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });

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
                    0: { items: 1, stagePadding: 40, nav: false },
                    480: { items: 1, stagePadding: 60, nav: true },
                    667: { items: 1, stagePadding: 80, nav: true },
                    1000: { items: 1, nav: true }
                }
            });

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
                    0: { items: 2, nav: false },
                    480: { items: 2, nav: true },
                    667: { items: 3, nav: true },
                    1000: { items: 5, nav: true }
                }
            });

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
                    0: { items: 1, nav: false },
                    480: { items: 1, nav: false },
                    667: { items: 1, nav: true },
                    1000: { items: 1, nav: true }
                }
            });

            $('.popup-with-zoom-anim').magnificPopup({
                type: 'iframe',
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
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-slide-bottom'
            });

            $('.navbar-toggler').click(function () {
                $('body').toggleClass('noscroll');
            });
        });

        $(window).on("scroll", function () {
            var scroll = $(window).scrollTop();
            if (scroll >= 80) {
                $("#site-header").addClass("nav-fixed");
            } else {
                $("#site-header").removeClass("nav-fixed");
            }
        });

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

        let prevId = "1";
        window.onload = function () {
            try {
                document.getElementById("screen-next-btn").disabled = true;
            } catch (e) {
                console.log("Element 'screen-next-btn' not found");
            }
        }

        function timeFunction() {
            try {
                document.getElementById("screen-next-btn").disabled = false;
            } catch (e) {
                console.log("Element 'screen-next-btn' not found");
            }
        }

        function myFunction(id) {
            try {
                document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
                document.getElementById(id).style.background = "#df0e62";
                prevId = id;
            } catch (e) {
                console.log("Error in myFunction: ", e);
            }
        }
    </script>
</body>
</html>