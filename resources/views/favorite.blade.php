@extends('layout.main')

@section('title', 'Favorite Movies')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Favorite Movies</h2>
        <div class="row" id="favorite-movies">
            <div class="text-muted">Loading...</div>
        </div>
    </div>

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
    </style>

    <script>
        const imdbIds = @json($favorites);

        $('#favorite-movies').empty(); // Hapus "Loading..."

        imdbIds.forEach(function(id) {
            fetch(`https://www.omdbapi.com/?i=${id}&apikey=80abae31`)
                .then(res => res.json())
                .then(movie => {
                    const isFav = true; // karena ini list favorit, semua udah favorit
                    const card = `
                    <div class="col-sm-6 col-md-4 col-lg-3" style="padding: 50px;">
                        <div class="movie-card">
                            <button class="favorite-btn ${isFav ? 'active' : ''}" onclick="toggleFavorite(this, '${movie.imdbID}')">
                                <i class="bi ${isFav ? 'bi-heart-fill' : 'bi-heart'}"></i>
                            </button>
                            <img src="${movie.Poster !== 'N/A' ? movie.Poster : 'https://via.placeholder.com/300x450'}" class="movie-img" alt="${movie.Title}">
                            <div class="movie-info" onclick="goToDetail('${movie.imdbID}')">
                                <div class="movie-title">${movie.Title}</div>
                                <div class="movie-year">${movie.Year}</div>
                            </div>
                        </div>
                    </div>
                `;
                    $('#favorite-movies').append(card);
                })
                .catch(err => {
                    console.error('Error:', err);
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
