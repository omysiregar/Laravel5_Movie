@extends('layout.main')
@section('title', 'Deskripsi Movie')
@section('content')
    <style>
        :root {
            --poster-url: '';
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: var(--poster-url);
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--poster-url);
            background-size: cover;
            background-position: center;
            filter: blur(10px);
            z-index: -2;
        }

        body::after {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: -1;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            padding: 40px;
            color: white;
            width: 100%;
            max-width: 1200px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .poster {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
        }

        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .movie-title {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .genre {
            font-size: 1.2rem;
            font-style: italic;
            color: #ccc;
            margin-bottom: 20px;
        }

        .description {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 25px;
            border-radius: 12px;
            font-size: 1.1rem;
        }

        .description p {
            margin: 6px 0;
        }

        .ratings span {
            display: inline-block;
            margin-right: 10px;
        }

        .favorite-btn {
            margin-top: 20px;
            font-size: 1.5rem;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
        }

        .favorite-btn.active i {
            color: red;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                align-items: center;
            }

            .movie-title {
                text-align: center;
            }

            .favorite-btn {
                text-align: center;
            }
        }
    </style>
    <input type="hidden" name="id" id="id" value="{{ $imdb_id }}">

    <div class="content-wrapper">
        <div class="row">
            <div>
                <img src="" alt="Poster" class="poster" id="poster-img">
            </div>
            <div class="info-section">
                <div>
                    <div class="movie-title align-items-center">
                        <span id="movie-title">Loading...</span>
                        {{-- <button class="favorite-btn" onclick="toggleFavorite(this, 'tt10872600')">
                            <i class="bi bi-heart"></i>
                        </button> --}}
                    </div>
                    <div class="genre" id="movie-genre"></div>
                </div>
                <div class="description" id="movie-description">
                    Loading description...
                </div>
            </div>
        </div>
    </div>

    {{-- jQuery CDN & Bootstrap Icons --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <script>
        let id_M = $('[name="id"]').val();

        fetch(`https://www.omdbapi.com/?i=${id_M}&apikey=80abae31`)
            .then(res => res.json())
            .then(data => {
                document.documentElement.style.setProperty('--poster-url', `url('${data.Poster}')`);
                document.getElementById("poster-img").src = data.Poster;
                document.getElementById("movie-genre").innerText = data.Genre;

                // Tambahkan tombol favorite di judul
                const titleWithFav = `
            <span>${data.Title}</span>
            <button class="favorite-btn" onclick="toggleFavorite(this, '${data.imdbID}')">
                <i class="bi bi-heart"></i>
            </button>
        `;
                document.getElementById("movie-title").innerHTML = titleWithFav;

                const ratings = data.Ratings.map(r => `<span>${r.Source}: ${r.Value}</span>`).join(" • ");
                const desc = `
            <p><strong><i>${data.Genre}</i></strong></p>
            <p><strong>Plot:</strong> ${data.Plot}</p>
            <p><strong>Actors:</strong> ${data.Actors}</p>
            <p><strong>Director:</strong> ${data.Director}</p>
            <p><strong>Language:</strong> ${data.Language}</p>
            <p><strong>Released:</strong> ${data.Released}</p>
            <p><strong>Duration:</strong> ${data.Runtime}</p>
            <div class="ratings mt-2">${ratings}</div>
            <div class="meta">IMDb: ${data.imdbRating} • Metascore: ${data.Metascore} • Box Office: ${data.BoxOffice}</div>
        `;
                document.getElementById("movie-description").innerHTML = desc;
            })
            .catch(() => {
                document.getElementById("movie-description").innerHTML =
                    "<p class='text-danger'>Failed to load movie data.</p>";
            });

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
    </script>
@endsection
