@extends('layouts.client.client')
@section('content')
    <!--/breadcrumbs -->
    <div class="w3l-breadcrumbs">
        <nav id="breadcrumbs" class="breadcrumbs">
            <div class="container page-wrapper">
                <a href="index.html">Home</a> » <span class="breadcrumb_last" aria-current="page">movies</span>
            </div>
        </nav>
    </div>
    <!--/movies -->
    <!--grids-sec1-->
    <section class="w3l-grids">
        <div class="grids-main py-4">
            <div class="container py-lg-4">
                <div class="headerhny-title">
                    <h3 class="hny-title">{{ isset($query) ? 'Search Results for "' . $query . '"' : 'Popular Movies' }}</h3>
                </div>

                @if(isset($movies) && $movies->isNotEmpty())
                    <div class="owl-four owl-carousel owl-theme">
                        @foreach($movies as $movie)
                            <div class="item vhny-grid">
                                <div class="box16">
                                    <a href="{{ route('movie.detail', $movie->id) }}"> <!-- Giả sử có route chi tiết phim -->
                                        <figure>
                                            <img class="img-fluid" src="{{ asset('assets/images/' . $movie->image) }}" alt="{{ $movie->title }}">
                                        </figure>
                                        <div class="box-content">
                                            <h3 class="title">{{ $movie->title }}</h3>
                                            <h4>
                                                <span class="post"><span class="fa fa-clock-o"></span> {{ $movie->duration }}</span>
                                                <span class="post fa fa-heart text-right"></span>
                                            </h4>
                                        </div>
                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No movies found.</p>
                @endif
            </div>
        </div>
</section>

@if(!isset($query))
    <!--grids-sec1-->
    <section class="w3l-grids">
        <div class="grids-main py-5">
            <div class="container py-lg-4">
                <div class="headerhny-title">
                    <div class="w3l-title-grids">
                        <div class="headerhny-left">
                            <h3 class="hny-title">Latest Movies</h3>
                        </div>
                        <div class="headerhny-right text-lg-right">
                            <h4><a class="show-title" href="movies.html">Show all</a></h4>
                        </div>
                    </div>
                </div>
                <div class="w3l-populohny-grids">
                    <div class="item vhny-grid">
                        <div class="box16 mb-0">
                            <figure>
                                <img class="img-fluid" src="assets/images/commando3.png" alt="">
                            </figure>
                            <a href=".Commando3" data-toggle="modal">
                                <div class="box-content">
                                    <h3 class="title">Commando-3</h3>
                                    <h4> <span class="post"><span class="fa fa-clock-o"> </span> 1 Hr 40min

                                        </span>

                                        <span class="post fa fa-heart text-right"></span>
                                    </h4>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade Commando3" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" id="mymodalcontent">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLongTitle">DETAILS</h4>
                                            <button type="button" class="closebtn" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="dynamic-content">
                                            <img src="assets/images/commando3.png" class="img-fluid modalimg" alt="" />
                                            <p>
                                            <h3>Release Date&nbsp;:29 November 2019</h3>
                                            <h3>Venue&nbsp;:Cg Road </h3>
                                            </p>
                                            <h4>About Movie</h4>
                                            <p>
                                                Commando 3 is a 2019 Indian Hindi-language action thriller film directed
                                                by Aditya Datt and produced by Vipul Amrutlal Shah, Reliance
                                                Entertainment.The film is the sequel of Commando: A One Man Army
                                                (2013) and Commando 2: The Black Money Trail (2017). The third
                                                installment of Commando film series, the film features Vidyut Jammwal,
                                                Adah Sharma, and Angira Dhar in lead roles, with Gulshan Devaiah
                                                portraying the antagonist.Jammwal reprises his role as
                                                the commando Karan, who goes undercover with encounter specialist
                                                Bhavana Reddy for an anti-terrorist mission in London.
                                            </p>
                                            <h4>Star Cast</h4>
                                            <p>
                                                Vidyut Jammwal as Commando Karanveer Singh Dogra (Karan)<br />
                                                Adah Sharma as Inspector Bhavna Reddy<br />
                                                Angira Dhar as British Intelligence Agent Mallika Sood<br />
                                                Gulshan Devaiah as Buraq Ansari<br />
                                            </p>
                                        </div>
                                        <div class="bookbtn">
                                            <button type="button" class="btn btn-success"
                                                onclick="#">Book</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->
                        </div>
                    </div>
                </div>
                <!-- ***********************************Adults Section ************************************** -->
                <div class="w3l-title-grids">
                    <div class="headerhny-left">
                        <h3 class="hny-title">Adults</h3>
                    </div>
                    <div class="headerhny-right text-lg-right">
                        <h4><a class="show-title" href="movies.html">Show all</a></h4>
                    </div>
                </div>
                <div class="w3l-populohny-grids">
                    <div class="item vhny-grid">
                        <div class="box16 mb-0">
                            <figure>
                                <img class="img-fluid" src="assets/images/m1.jpg" alt="">
                            </figure>
                            <a href=".Rocketman" data-toggle="modal">
                                <div class="box-content">
                                    <h3 class="title">Rocketman</h3>
                                    <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 1min

                                        </span>

                                        <span class="post fa fa-heart text-right"></span>
                                    </h4>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade Rocketman" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" id="mymodalcontent">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLongTitle">DETAILS</h4>
                                            <button type="button" class="closebtn" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="dynamic-content">
                                            <img src="assets/images/m1.jpg" class="img-fluid modalimg" alt="" />
                                            <p>
                                            <h3>Release Date&nbsp;:31 May 2019 </h3>
                                            <h3>Venue&nbsp;:Cg Road </h3>
                                            </p>
                                            <h4>About Movie</h4>
                                            <p>
                                                Rocketman is a 2019 biographical musical film based on the life of
                                                British musician Elton John. Directed by Dexter Fletcher and written by
                                                Lee Hall, it stars Taron Egerton as Elton John, with Jamie Bell as
                                                Bernie Taupin, Richard Madden as John Reid, and Bryce Dallas Howard as
                                                Sheila Eileen, John's mother. The film follows John in his early days in
                                                England as a prodigy at the Royal Academy of Music through his musical
                                                partnership with Taupin, and is titled after John's 1972 song "Rocket
                                                Man".
                                            </p>
                                            <h4>Star Cast</h4>
                                            <p>
                                                Taron Egerton as Elton John<br />
                                                Jamie Bell as Bernie Taupin<br />
                                                Richard Madden as John Reid<br />
                                                Bryce Dallas Howard as Sheila Dwight<br />
                                                Gemma Jones as Ivy, Elton's grandmother
                                            </p>
                                        </div>
                                        <div class="bookbtn">
                                            <!-- window.open('ticket-booking.html','_blank'); -->
                                            <button type="button" class="btn btn-success"
                                                onclick="location.href='ticket-booking.html';">Book</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->

                        </div>
                    </div>
                </div>
                <!-- ***********************************kids Section ************************************** -->
                <div class="w3l-title-grids">
                    <div class="headerhny-left">
                        <h3 class="hny-title">Kids</h3>
                    </div>
                    <div class="headerhny-right text-lg-right">
                        <h4><a class="show-title" href="movies.html">Show all</a></h4>
                    </div>
                </div>
                <div class="w3l-populohny-grids">
                    <div class="item vhny-grid">
                        <div class="box16 mb-0">
                            <figure>
                                <img class="img-fluid" src="assets/images/tzp.png" alt="">
                            </figure>
                            <a href=".tzp" data-toggle="modal">
                                <div class="box-content">
                                    <h3 class="title">Taare Zameen Par</h3>
                                    <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 44min

                                        </span>

                                        <span class="post fa fa-heart text-right"></span>
                                    </h4>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade tzp" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" id="mymodalcontent">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLongTitle">DETAILS</h4>
                                            <button type="button" class="closebtn" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="dynamic-content">
                                            <img src="assets/images/tzp.png" class="img-fluid modalimg" alt="" />
                                            <p>
                                            <h3>Release Date&nbsp;:21 December 2007 </h3>
                                            <h3>Venue&nbsp;:Cg Road </h3>
                                            </p>
                                            <h4>About Movie</h4>
                                            <p>
                                                Taare Zameen Par (titled Little Stars on Earth internationally) is a
                                                2007 Indian Hindi-language drama film produced and directed by Aamir
                                                Khan. The film explores the life and imagination of Ishaan, an
                                                8-year-old dyslexic child. Although he excels in art, his poor academic
                                                performance leads his parents to send him to a boarding school. Ishaan's
                                                new art teacher suspects that he is dyslexic and helps him to overcome
                                                his reading disorder. Darsheel Safary stars as 8-year-old Ishaan, and
                                                Aamir Khan plays his art teacher.
                                            </p>
                                            <h4>Star Cast</h4>
                                            <p>
                                                Darsheel Safary as Ishaan 'Inu' Awasthi<br />
                                                Aamir Khan as Ram Shankar Nikumbh<br />
                                                Tanay Chheda as Rajan Damodran<br />
                                                Tisca Chopra as Maya Awasthi<br />
                                                Vipin Sharma as Nandkishore Awasthi
                                            </p>
                                        </div>
                                        <div class="bookbtn">
                                            <button type="button" class="btn btn-success"
                                                onclick="location.href='ticket-booking.html';">Book</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->

                        </div>
                    </div>
                </div>
                <!-- ***********************************Gujarati Section ************************************** -->
                <div class="w3l-title-grids">
                    <div class="headerhny-left">
                        <h3 class="hny-title">Gujarati</h3>
                    </div>
                    <div class="headerhny-right text-lg-right">
                        <h4><a class="show-title" href="movies.html">Show all</a></h4>
                    </div>
                </div>
                <div class="w3l-populohny-grids">
                    <div class="item vhny-grid">
                        <div class="box16 mb-0">
                            <figure>
                                <img class="img-fluid" src="assets/images/gk.png" alt="">
                            </figure>
                            <a href=".gk" data-toggle="modal">
                                <div class="box-content">
                                    <h3 class="title">Golkeri</h3>
                                    <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 8min

                                        </span>

                                        <span class="post fa fa-heart text-right"></span>
                                    </h4>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade gk" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" id="mymodalcontent">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLongTitle">DETAILS</h4>
                                            <button type="button" class="closebtn" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="dynamic-content">
                                            <img src="assets/images/gk.png" class="img-fluid modalimg" alt="" />
                                            <p>
                                            <h3>Release Date &nbsp;: 28 February 2020</h3>
                                            <h3>Venue&nbsp;:Cg Road </h3>
                                            </p>
                                            <h4>About Movie</h4>
                                            <p>
                                                Golkeri (Mango pickle) is a Gujarati comedy drama film directed by Viral
                                                Shah and produced by Manasi Parekh and Parthiv Gohil under banner of
                                                Soul Sutra Studio. It is written by Viral Shah and Amatya. The film
                                                starring Malhar Thakar and Manasi Parekh is a remake of 2017 Marathi
                                                film Muramba
                                            </p>
                                            <h4>Star Cast</h4>
                                            <p>
                                                Malhar Thakar as Sahil Mohanbhai Sutariya<br />
                                                Manasi Parekh as Harshita<br />
                                                Sachin Khedekar as Mohanbhai Sutariya<br />
                                                Vandana Pathak as Jyotnsa Sutariya<br />
                                                Dharmesh Vyas
                                            </p>
                                        </div>
                                        <div class="bookbtn">
                                            <button type="button" class="btn btn-success"
                                                onclick="location.href='ticket-booking.html';">Book</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->

                        </div>
                    </div>
                </div>
            </div>
            <div class="button-center text-center mt-3">
                <a href="#" class="btn view-button">View all <span class="fa fa-angle-double-right ml-2"
                        aria-hidden="true"></span></a>
            </div>
        </div>
    </section>

    <section class="w3l-albums py-5" id="projects">
        <div class="container py-lg-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <!--Horizontal Tab-->
                    <div id="parentHorizontalTab">
                        <ul class="resp-tabs-list hor_1">
                            <li>Recent Movies</li>
                            <li>Popular Movies</li>
                            <li>Trend Movies</li>
                            <div class="clear"></div>
                        </ul>
                        <div class="resp-tabs-container hor_1">
                            <div class="albums-content">
                                <div class="row">
                                    <!--/set1-->
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-0">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="movies.html">

                                                    <img src="assets/images/m6.jpg" class="img-fluid" alt="author image">
                                                    <div class="overlay-icon">

                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>English</p>
                                                <a class="author-book-title" href="movies.html">Long Shot</a>
                                                <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 4min

                                                    </span>

                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="albums-content">
                                <div class="row">
                                    <!--/set1-->
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-0">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="movies.html"><img src="assets/images/m1.jpg" class="img-fluid"
                                                        alt="author image">
                                                    <div class="overlay-icon">

                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>English</p>
                                                <a class="author-book-title" href="movies.html">Rocketman</a>
                                                <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 4min

                                                    </span>

                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="albums-content">
                                <div class="row">
                                    <!--/set3-->
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-0">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="movies.html"><img src="assets/images/m7.jpg" class="img-fluid"
                                                        alt="author image">
                                                    <div class="overlay-icon">

                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>English</p>
                                                <a class="author-book-title" href="movies.html">Frozen 2</a>
                                                <h4> <span class="post"><span class="fa fa-clock-o"> </span> 2 Hr 4min

                                                    </span>

                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--grids-sec2-->
    @endif
    @include('client.footer.footer')
@endsection