@extends('layout.main')
@section('title', 'Beranda')
@section('content')
    <style>
        .movie-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(255, 0, 0, 0.4);
        }

        .movie-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
        }

        .movie-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
            color: white;
            padding: 15px;
        }

        .movie-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .movie-year {
            font-size: 0.9rem;
            color: #bbb;
        }

        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            padding: 8px;
            color: white;
            font-size: 20px;
            z-index: 1;
            transition: background-color 0.3s ease;
        }

        .favorite-btn.active {
            background-color: red;
            color: white;
        }

        .favorite-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        #loading {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
    <div class="container py-4">
        <h2 class="text-center mb-4">Movie Search</h2>
        <input type="text" class="form-control mb-4" id="search" placeholder="Search movies... (min 3 chars)">
        <div class="row" id="movies"></div>
        <div id="loading" style="display: none;">Mmmuat data . . . </div>
    </div>

    <script>
        let page = 1;
        let query = "";
        let isLoading = false;
        let hasMore = true;

        let favoriteIds = [];

        function fetchUserFavorites() {
            return $.get("/favorites", function(data) {
                favoriteIds = data; // array of imdbID
            });
        }

        function fetchMovies(isNewSearch = false) {
            if (isLoading || !query || !hasMore) return;
            isLoading = true;
            $('#loading').show();

            $.get(`https://www.omdbapi.com/?apikey=80abae31&s=${encodeURIComponent(query)}&page=${page}`, function(data) {
                if (data.Response === "True") {
                    const movieHtml = data.Search.map(movie => {
                        const isFav = favoriteIds.includes(movie.imdbID);
                        return `
                            <div class="col-sm-6 col-md-4 col-lg-3" style="padding: 50px;">
                                <div class="movie-card">
                                    <button class="favorite-btn ${isFav ? 'active' : ''}" onclick="toggleFavorite(this, '${movie.imdbID}')">
                                        <i class="bi ${isFav ? 'bi-heart-fill' : 'bi-heart'}"></i>
                                    </button>
                                    <img src="${movie.Poster !== "N/A" ? movie.Poster : 'https://via.placeholder.com/300x450'}" class="movie-img" alt="${movie.Title}">
                                    <div class="movie-info" onclick="goToDetail('${movie.imdbID}')">
                                        <div class="movie-title">${movie.Title}</div>
                                        <div class="movie-year">${movie.Year}</div>
                                    </div>
                                </div>
                            </div>
                    `;
                    }).join("");

                    if (isNewSearch) {
                        $("#movies").html(movieHtml);
                        page = 2;
                        hasMore = true;
                    } else {
                        $("#movies").append(movieHtml);
                        page++;
                    }
                } else {
                    if (isNewSearch) $("#movies").html('<p class="text-center">No results found.</p>');
                    hasMore = false;
                }

                isLoading = false;
                $('#loading').hide();
            });
        }

        $("#search").on("input", function() {
            query = $(this).val().trim();
            page = 1;
            hasMore = true;
            if (query.length > 2) {
                fetchMovies(true);
            } else {
                $("#movies").html('');
            }
        });
        $(window).on("scroll", function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
                fetchMovies();
            }

        });

        // Default pencarian ketika di reload
        $(document).ready(function() {
            query = "naruto";
            $("#search").val(query);
            fetchUserFavorites().then(() => {
                fetchMovies(true);
            });
        });

        //Event untuk movies favorite
        function toggleFavorite(btn, imdbID) {
            const icon = btn.querySelector("i");
            const isFav = btn.classList.contains("active");

            if (isFav) {
                // Unfavorite
                $.post("/unfavorite", {
                    imdb_id: imdbID,
                    _token: "{{ csrf_token() }}"
                }, function() {
                    btn.classList.remove("active");
                    icon.classList.remove("bi-heart-fill");
                    icon.classList.add("bi-heart");
                });
            } else {
                // Favorite
                $.post("/favorite", {
                    imdb_id: imdbID,
                    _token: "{{ csrf_token() }}"
                }, function() {
                    btn.classList.add("active");
                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill");
                });
            }
        }

        // DETAIL Movie
        function goToDetail(imdbID) {
            window.location.href = `/movie/${imdbID}`; // sesuaikan dengan route detail kamu
        }
    </script>

@endsection
