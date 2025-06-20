<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 May 2025 02:25:50 GMT -->
<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>Dashboard | Larkon - Responsive Admin Dashboard Template</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

     <!-- Vendor css (Require in all Page) -->
     <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

     <!-- Theme Config js (Require in all Page) -->
     <script src="{{ asset('assets/js/config.js') }}"></script>

     <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>

    <style>
    /* Thêm lớp tùy chỉnh để đảm bảo main-nav có thể cuộn và không bị giới hạn chiều cao */
    .main-nav-scrollable {
        overflow-y: auto !important;
        max-height: none !important;
    }
</style>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <!-- ========== Topbar Start ========== -->
          <header class="topbar">
               <div class="container-fluid">
                    <div class="navbar-header">
                         <div class="d-flex align-items-center">
                              <!-- Menu Toggle Button -->
                              <div class="topbar-item">
                                   <button type="button" class="button-toggle-menu me-2">
                                        <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle"></iconify-icon>
                                   </button>
                              </div>

                              <!-- Menu Toggle Button -->
                              <div class="topbar-item">
                                   <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">Welcome!</h4>
                              </div>
                         </div>

                         <div class="d-flex align-items-center gap-1">

                              <!-- Theme Color (Light/Dark) -->
                              <div class="topbar-item">
                                   <button type="button" class="topbar-button" id="light-dark-mode">
                                        <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                                   </button>
                              </div>

                              <!-- Notification -->
                              <div class="dropdown topbar-item">
                                   <button type="button" class="topbar-button position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                                        <span class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">3<span class="visually-hidden">unread messages</span></span>
                                   </button>
                                   <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end" aria-labelledby="page-header-notifications-dropdown">
                                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                             <div class="row align-items-center">
                                                  <div class="col">
                                                       <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                                  </div>
                                                  <div class="col-auto">
                                                       <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                                            <small>Clear All</small>
                                                       </a>
                                                  </div>
                                             </div>
                                        </div>
                                        <div data-simplebar style="max-height: 280px;">
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom text-wrap">
                                                  <div class="d-flex">
                                                       <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-1" />
                                                       </div>
                                                       <div class="flex-grow-1">
                                                            <p class="mb-0"><span class="fw-medium">Josephine Thompson </span>commented on admin panel <span>" Wow 😍! this admin looks good and awesome design"</span></p>
                                                       </div>
                                                  </div>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <div class="d-flex">
                                                       <div class="flex-shrink-0">
                                                            <div class="avatar-sm me-2">
                                                                 <span class="avatar-title bg-soft-info text-info fs-20 rounded-circle">
                                                                      D
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="flex-grow-1">
                                                            <p class="mb-0 fw-semibold">Donoghue Susan</p>
                                                            <p class="mb-0 text-wrap">
                                                                 Hi, How are you? What about our next meeting
                                                            </p>
                                                       </div>
                                                  </div>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <div class="d-flex">
                                                       <div class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-3.jpg" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-3" />
                                                       </div>
                                                       <div class="flex-grow-1">
                                                            <p class="mb-0 fw-semibold">Jacob Gines</p>
                                                            <p class="mb-0 text-wrap">Answered to your comment on the cash flow forecast's graph 🔔.</p>
                                                       </div>
                                                  </div>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <div class="d-flex">
                                                       <div class="flex-shrink-0">
                                                            <div class="avatar-sm me-2">
                                                                 <span class="avatar-title bg-soft-warning text-warning fs-20 rounded-circle">
                                                                      <iconify-icon icon="iconamoon:comment-dots-duotone"></iconify-icon>
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="flex-grow-1">
                                                            <p class="mb-0 fw-semibold text-wrap">You have received <b>20</b> new messages in the
                                                                 conversation</p>
                                                       </div>
                                                  </div>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <div class="d-flex">
                                                       <div class="flex-shrink-0">
                                                            <img src="{{ asset('assets/images/users/avatar-5.jpg') }}" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-5" />
                                                       </div>
                                                       <div class="flex-grow-1">
                                                            <p class="mb-0 fw-semibold">Shawn Bunch</p>
                                                            <p class="mb-0 text-wrap">
                                                                 Commented on Admin
                                                            </p>
                                                       </div>
                                                  </div>
                                             </a>
                                        </div>
                                        <div class="text-center py-3">
                                             <a href="javascript:void(0);" class="btn btn-primary btn-sm">View All Notification <i class="bx bx-right-arrow-alt ms-1"></i></a>
                                        </div>
                                   </div>
                              </div>

                              <!-- Theme Setting -->
                              <div class="topbar-item d-none d-md-flex">
                                   <button type="button" class="topbar-button" id="theme-settings-btn" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                                        <iconify-icon icon="solar:settings-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                                   </button>
                              </div>

                              <!-- Activity -->
                              <div class="topbar-item d-none d-md-flex">
                                   <button type="button" class="topbar-button" id="theme-settings-btn" data-bs-toggle="offcanvas" data-bs-target="#theme-activity-offcanvas" aria-controls="theme-settings-offcanvas">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                                   </button>
                              </div>

                              <!-- User -->
                              <div class="dropdown topbar-item">
                                   <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="d-flex align-items-center">
                                             <img class="rounded-circle" width="32" src="assets/images/users/avatar-1.jpg" alt="avatar-3">
                                        </span>
                                   </a>
                                   <div class="dropdown-menu dropdown-menu-end">
                                        <!-- item-->
                                        <h6 class="dropdown-header">Welcome Gaston!</h6>
                                        <a class="dropdown-item" href="pages-profile.html">
                                             <i class="bx bx-user-circle text-muted fs-18 align-middle me-1"></i><span class="align-middle">Profile</span>
                                        </a>
                                        <a class="dropdown-item" href="apps-chat.html">
                                             <i class="bx bx-message-dots text-muted fs-18 align-middle me-1"></i><span class="align-middle">Messages</span>
                                        </a>

                                        <a class="dropdown-item" href="pages-pricing.html">
                                             <i class="bx bx-wallet text-muted fs-18 align-middle me-1"></i><span class="align-middle">Pricing</span>
                                        </a>
                                        <a class="dropdown-item" href="pages-faqs.html">
                                             <i class="bx bx-help-circle text-muted fs-18 align-middle me-1"></i><span class="align-middle">Help</span>
                                        </a>
                                        <a class="dropdown-item" href="auth-lock-screen.html">
                                             <i class="bx bx-lock text-muted fs-18 align-middle me-1"></i><span class="align-middle">Lock screen</span>
                                        </a>

                                        <div class="dropdown-divider my-1"></div>

                                        <a class="dropdown-item text-danger" href="auth-signin.html">
                                             <i class="bx bx-log-out fs-18 align-middle me-1"></i><span class="align-middle">Logout</span>
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </header>

          <!-- Activity Timeline -->
          <div>
               <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-activity-offcanvas" style="max-width: 450px; width: 100%;">
                    <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                         <h5 class="text-white m-0 fw-semibold">Activity Stream</h5>
                         <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body p-0">
                         <div data-simplebar class="h-100 p-4">
                              <div class="position-relative ms-2">
                                   <span class="position-absolute start-0  top-0 border border-dashed h-100"></span>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 avatar-sm translate-middle-x bg-danger d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><iconify-icon icon="iconamoon:folder-check-duotone"></iconify-icon></span>
                                             <div class="ms-2">
                                                  <h5 class="mb-1 text-dark fw-semibold fs-15 lh-base">Report-Fix / Update </h5>
                                                  <p class="d-flex align-items-center">Add 3 files to <span class=" d-flex align-items-center text-primary ms-1"><iconify-icon icon="iconamoon:file-light"></iconify-icon> Tasks</span></p>
                                                  <div class="bg-light bg-opacity-50 rounded-2 p-2">
                                                       <div class="row">
                                                            <div class="col-lg-6 border-end border-light">
                                                                 <div class="d-flex align-items-center gap-2">
                                                                      <i class="bx bxl-figma fs-20 text-red"></i>
                                                                      <a href="#!" class="text-dark fw-medium">Concept.fig</a>
                                                                 </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <div class="d-flex align-items-center gap-2">
                                                                      <i class="bx bxl-file-doc fs-20 text-success"></i>
                                                                      <a href="#!" class="text-dark fw-medium">larkon.docs</a>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  <h6 class="mt-2 text-muted">Monday , 4:24 PM</h6>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 avatar-sm translate-middle-x bg-success d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><iconify-icon icon="iconamoon:check-circle-1-duotone"></iconify-icon></span>
                                             <div class="ms-2">
                                                  <h5 class="mb-1 text-dark fw-semibold fs-15 lh-base">Project Status
                                                  </h5>
                                                  <p class="d-flex align-items-center mb-0">Marked<span class=" d-flex align-items-center text-primary mx-1"><iconify-icon icon="iconamoon:file-light"></iconify-icon> Design </span> as <span class="badge bg-success-subtle text-success px-2 py-1 ms-1"> Completed</span></p>
                                                  <div class="d-flex align-items-center gap-3 mt-1 bg-light bg-opacity-50 p-2 rounded-2">
                                                       <a href="#!" class="fw-medium text-dark">UI/UX Figma Design</a>
                                                       <div class="ms-auto">
                                                            <a href="#!" class="fw-medium text-primary fs-18" data-bs-toggle="tooltip" data-bs-title="Download" data-bs-placement="bottom"><iconify-icon icon="iconamoon:cloud-download-duotone"></iconify-icon></a>
                                                       </div>
                                                  </div>
                                                  <h6 class="mt-3 text-muted">Monday , 3:00 PM</h6>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 avatar-sm translate-middle-x bg-primary d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-16">UI</span>
                                             <div class="ms-2">
                                                  <h5 class="mb-1 text-dark fw-semibold fs-15">Larkon Application UI v2.0.0 <span class="badge bg-primary-subtle text-primary px-2 py-1 ms-1"> Latest</span>
                                                  </h5>
                                                  <p>Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p>
                                                  <div class="mt-2">
                                                       <a href="#!" class="btn btn-light btn-sm">Download Zip</a>
                                                  </div>
                                                  <h6 class="mt-3 text-muted">Monday , 2:10 PM</h6>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 translate-middle-x bg-success bg-gradient d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><img src="assets/images/users/avatar-7.jpg" alt="avatar-5" class="avatar-sm rounded-circle"></span>
                                             <div class="ms-2">
                                                  <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Alex Smith Attached Photos
                                                  </h5>
                                                  <div class="row g-2 mt-2">
                                                       <div class="col-lg-4">
                                                            <a href="#!">
                                                                 <img src="assets/images/small/img-6.jpg" alt="" class="img-fluid rounded">
                                                            </a>
                                                       </div>
                                                       <div class="col-lg-4">
                                                            <a href="#!">
                                                                 <img src="assets/images/small/img-3.jpg" alt="" class="img-fluid rounded">
                                                            </a>
                                                       </div>
                                                       <div class="col-lg-4">
                                                            <a href="#!">
                                                                 <img src="assets/images/small/img-4.jpg" alt="" class="img-fluid rounded">
                                                            </a>
                                                       </div>
                                                  </div>
                                                  <h6 class="mt-3 text-muted">Monday 1:00 PM</h6>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 translate-middle-x bg-success bg-gradient d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><img src="{{ asset('assets/images/users/avatar-6.jpg') }}" alt="avatar-5" class="avatar-sm rounded-circle"></span>
                                             <div class="ms-2">
                                                  <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Rebecca J. added a new team member
                                                  </h5>
                                                  <p class="d-flex align-items-center gap-1"><iconify-icon icon="iconamoon:check-circle-1-duotone" class="text-success"></iconify-icon> Added a new member to Front Dashboard</p>
                                                  <h6 class="mt-3 text-muted">Monday 10:00 AM</h6>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="position-relative ps-4">
                                        <div class="mb-4">
                                             <span class="position-absolute start-0 avatar-sm translate-middle-x bg-warning d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><iconify-icon icon="iconamoon:certificate-badge-duotone"></iconify-icon></span>
                                             <div class="ms-2">
                                                  <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Achievements
                                                  </h5>
                                                  <p class="d-flex align-items-center gap-1 mt-1">Earned a <iconify-icon icon="iconamoon:certificate-badge-duotone" class="text-danger fs-20"></iconify-icon>" Best Product Award"</p>
                                                  <h6 class="mt-3 text-muted">Monday 9:30 AM</h6>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <a href="#!" class="btn btn-outline-dark w-100">View All</a>
                         </div>
                    </div>
               </div>
          </div>

          <!-- Right Sidebar (Theme Settings) -->
          <div>
               <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
                    <div class="d-flex align-items-center bg-primary p-3 offcanvas-header overflow-y-auto">
                         <h5 class="text-white m-0">Theme Settings</h5>
                         <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body p-0">
                         <div data-simplebar class="h-100">
                              <div class="p-3 settings-bar">

                                   <div>
                                        <h5 class="mb-3 font-16 fw-semibold">Color Scheme</h5>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light">
                                             <label class="form-check-label" for="layout-color-light">Light</label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark">
                                             <label class="form-check-label" for="layout-color-dark">Dark</label>
                                        </div>
                                   </div>

                                   <div>
                                        <h5 class="my-3 font-16 fw-semibold">Topbar Color</h5>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-light" value="light">
                                             <label class="form-check-label" for="topbar-color-light">Light</label>
                                        </div>
                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-dark" value="dark">
                                             <label class="form-check-label" for="topbar-color-dark">Dark</label>
                                        </div>
                                   </div>


                                   <div>
                                        <h5 class="my-3 font-16 fw-semibold">Menu Color</h5>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-color" id="leftbar-color-light" value="light">
                                             <label class="form-check-label" for="leftbar-color-light">
                                                  Light
                                             </label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-color" id="leftbar-color-dark" value="dark">
                                             <label class="form-check-label" for="leftbar-color-dark">
                                                  Dark
                                             </label>
                                        </div>
                                   </div>

                                   <div>
                                        <h5 class="my-3 font-16 fw-semibold">Sidebar Size</h5>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-size" id="leftbar-size-default" value="default">
                                             <label class="form-check-label" for="leftbar-size-default">
                                                  Default
                                             </label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-size" id="leftbar-size-small" value="condensed">
                                             <label class="form-check-label" for="leftbar-size-small">
                                                  Condensed
                                             </label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-size" id="leftbar-hidden" value="hidden">
                                             <label class="form-check-label" for="leftbar-hidden">
                                                  Hidden
                                             </label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-size" id="leftbar-size-small-hover-active" value="sm-hover-active">
                                             <label class="form-check-label" for="leftbar-size-small-hover-active">
                                                  Small Hover Active
                                             </label>
                                        </div>

                                        <div class="form-check mb-2">
                                             <input class="form-check-input" type="radio" name="data-menu-size" id="leftbar-size-small-hover" value="sm-hover">
                                             <label class="form-check-label" for="leftbar-size-small-hover">
                                                  Small Hover
                                             </label>
                                        </div>
                                   </div>

                              </div>
                         </div>
                    </div>
                    <div class="offcanvas-footer border-top p-3 text-center">
                         <div class="row">
                              <div class="col">
                                   <button type="button" class="btn btn-danger w-100" id="reset-layout">Reset</button>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <!-- ========== Topbar End ========== -->

          <!-- ========== App Menu Start ========== -->
            <div class="main-nav">
                <!-- Sidebar Logo -->
                <div class="logo-box">
                    <a href="" class="logo-dark">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo nhỏ">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="logo tối">
                    </a>
                    <a href="" class="logo-light">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo nhỏ">
                        <img src="{{ asset('assets/images/logo-light.png') }}" class="logo-lg" alt="logo sáng">
                    </a>
                </div>

                <!-- Menu Toggle Button -->
                <button type="button" class="button-sm-hover" aria-label="Hiển thị toàn bộ Sidebar">
                    <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
                </button>

                <div class="scrollbar" data-simplebar>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title">Quản lý chung</li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Bảng điều khiển</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarMovies" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMovies">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:clapperboard-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Phim</span>
                            </a>
                            <div class="collapse" id="sidebarMovies">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.movies.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.movies.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarGenres" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGenres">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:tag-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Thể loại phim</span>
                            </a>
                            <div class="collapse" id="sidebarGenres">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.genres.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.genres.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarAgeLimits" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAgeLimits">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Độ tuổi</span>
                            </a>
                            <div class="collapse" id="sidebarAgeLimits">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.age_limits.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.age_limits.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarCountries" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCountries">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:flag-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Quốc gia</span>
                            </a>
                            <div class="collapse" id="sidebarCountries">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.countries.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.countries.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarCities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCities">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:city-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Thành phố</span>
                            </a>
                            <div class="collapse" id="sidebarCities">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.cities.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.cities.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarCinemas" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCinemas">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Rạp chiếu</span>
                            </a>
                            <div class="collapse" id="sidebarCinemas">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.cinemas.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.cinemas.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarRooms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarRooms">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:projector-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Phòng chiếu</span>
                            </a>
                            <div class="collapse" id="sidebarRooms">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.rooms.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.rooms.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarBookings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBookings">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:ticket-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Đặt vé</span>
                            </a>
                            <div class="collapse" id="sidebarBookings">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.bookings.index') }}">Danh sách</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarPayments" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPayments">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:card-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Thanh toán</span>
                            </a>
                            <div class="collapse" id="sidebarPayments">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.payments.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.payments.show', 1) }}">Chi tiết</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.payments.editStatus', 1) }}">Cập nhật trạng thái</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarSeatTypes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSeatTypes">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:sofa-2-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Loại ghế</span>
                            </a>
                            <div class="collapse" id="sidebarSeatTypes">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('seat-type.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('seat-type.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarSeats" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSeats">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:armchair-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Ghế</span>
                            </a>
                            <div class="collapse" id="sidebarSeats">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.seats.index') }}">Danh sách</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarTickets" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTickets">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:ticket-sale-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Vé</span>
                            </a>
                            <div class="collapse" id="sidebarTickets">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('tickets.index') }}">Danh sách</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Cài đặt</span>
                            </a>
                        </li>

                        <li class="menu-title mt-3">Quản lý người dùng</li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Hồ sơ</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarRoles" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarRoles">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:user-check-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Vai trò</span>
                            </a>
                            <div class="collapse" id="sidebarRoles">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:key-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Quyền hạn</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCustomers">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Khách hàng</span>
                            </a>
                            <div class="collapse" id="sidebarCustomers">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Danh sách</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-title mt-3">Khác</li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarPromotions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPromotions">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:sale-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Khuyến mãi</span>
                            </a>
                            <div class="collapse" id="sidebarPromotions">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('promotions.index') }}">Danh sách</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('promotions.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Đánh giá</span>
                            </a>
                        </li>

                        <li class="menu-title mt-3">Ứng dụng khác</li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:chat-round-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Trò chuyện</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Email</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Lịch</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Việc cần làm</span>
                            </a>
                        </li>

                        <li class="menu-title mt-3">Hỗ trợ</li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:help-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Trung tâm hỗ trợ</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:question-circle-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Câu hỏi thường gặp</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Chính sách bảo mật</span>
                            </a>
                        </li>

                        <li class="menu-title mt-3">Tùy chỉnh</li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:files-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Trang</span>
                            </a>
                            <div class="collapse" id="sidebarPages">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Chào mừng</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Sắp ra mắt</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Dòng thời gian</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Bảng giá</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Bảo trì</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Lỗi 404</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuthentication">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:lock-keyhole-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text">Xác thực</span>
                            </a>
                            <div class="collapse" id="sidebarAuthentication">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Đăng nhập</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Đăng ký</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Đặt lại mật khẩu</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="">Khóa màn hình</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
          <!-- ========== App Menu End ========== -->

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               @yield('content')

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

     <!-- Vendor Javascript (Require in all Page) -->
     <script src="assets/js/vendor.js"></script>

     <!-- App Javascript (Require in all Page) -->
     <script src="assets/js/app.js"></script>

     <!-- Vector Map Js -->
     <script src="assets/vendor/jsvectormap/js/jsvectormap.min.js"></script>
     <script src="assets/vendor/jsvectormap/maps/world-merc.js"></script>
     <script src="assets/vendor/jsvectormap/maps/world.js"></script>

     <!-- Dashboard Js -->
     <script src="assets/js/pages/dashboard.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 May 2025 02:26:35 GMT -->
</html>