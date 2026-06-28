<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: auth/login.php');
    exit();
}

require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joke Generator - Sistem Informasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .joke-container {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .joke-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .joke-text {
            font-size: 24px;
            font-weight: 600;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .joke-type {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-generate {
            background: white;
            color: #667eea;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-generate:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .loading {
            display: none;
        }

        .joke-history {
            max-height: 500px;
            overflow-y: auto;
        }

        .history-item {
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .history-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-mortarboard-fill"></i>
                <h3>SIM Kampus</h3>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="mahasiswa/index.php" class="nav-item">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Mahasiswa</span>
                </a>
                <a href="mahasiswa/tambah.php" class="nav-item">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Mahasiswa</span>
                </a>
                <a href="joke-generator.php" class="nav-item active">
                    <i class="bi bi-emoji-laughing"></i>
                    <span>Joke Generator</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="auth/logout.php" class="nav-item">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-content">
                    <h2>Joke Generator</h2>
                    <div class="navbar-user">
                        <i class="bi bi-person-circle"></i>
                        <span><?php echo ucfirst($_SESSION['username']); ?></span>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card-table">
                                <div class="table-header mb-4">
                                    <h5><i class="bi bi-emoji-laughing"></i> Random Joke Generator</h5>
                                </div>

                                <div class="error-message" id="errorMessage">
                                    <i class="bi bi-exclamation-circle"></i> <span id="errorText"></span>
                                </div>

                                <div class="joke-container">
                                    <div class="joke-card">
                                        <div class="loading" id="loadingSpinner">
                                            <div class="spinner-border text-white" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-3">Loading joke...</p>
                                        </div>

                                        <div id="jokeContent" style="display: none;">
                                            <div class="joke-type" id="jokeType"></div>
                                            <div class="joke-text" id="jokeText"></div>
                                            <button class="btn btn-generate" onclick="generateJoke()">
                                                <i class="bi bi-arrow-repeat"></i> Get Another Joke
                                            </button>
                                        </div>

                                        <div id="initialContent">
                                            <div class="joke-text" style="font-size: 18px; margin-bottom: 30px;">
                                                <i class="bi bi-emoji-laughing" style="font-size: 60px; display: block; margin-bottom: 20px;"></i>
                                                Click the button to get a random joke!
                                            </div>
                                            <button class="btn btn-generate" onclick="generateJoke()">
                                                <i class="bi bi-play-fill"></i> Get Joke
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar History -->
                        <div class="col-lg-4">
                            <div class="card-table">
                                <div class="table-header mb-3">
                                    <h5><i class="bi bi-clock-history"></i> History</h5>
                                    <button class="btn btn-sm btn-secondary" onclick="clearHistory()">
                                        <i class="bi bi-trash"></i> Clear
                                    </button>
                                </div>

                                <div class="joke-history" id="jokeHistory">
                                    <p class="text-muted text-center py-4">No jokes generated yet</p>
                                </div>
                            </div>

                            <div class="card-table mt-3">
                                <div class="table-header mb-3">
                                    <h5><i class="bi bi-gear"></i> Options</h5>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Joke Type</label>
                                    <select class="form-control" id="jokeTypeSelect" onchange="generateJoke()">
                                        <option value="random">Random</option>
                                        <option value="single">Single Line</option>
                                        <option value="programming">Programming</option>
                                    </select>
                                </div>

                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Info!</strong> This joke generator uses the Official Joke API. Select your preferred joke type and click "Get Joke" to generate a new joke.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let jokeHistory = JSON.parse(localStorage.getItem('jokeHistory')) || [];

        async function generateJoke() {
            const jokeType = document.getElementById('jokeTypeSelect').value;
            const loadingSpinner = document.getElementById('loadingSpinner');
            const jokeContent = document.getElementById('jokeContent');
            const initialContent = document.getElementById('initialContent');
            const errorMessage = document.getElementById('errorMessage');

            loadingSpinner.style.display = 'block';
            jokeContent.style.display = 'none';
            initialContent.style.display = 'none';
            errorMessage.style.display = 'none';

            try {
                let url = 'https://v2.jokeapi.dev/joke/';

                if (jokeType === 'random') {
                    url += 'Any';
                } else if (jokeType === 'single') {
                    url += 'Any?type=single';
                } else if (jokeType === 'programming') {
                    url += 'Programming';
                }

                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch joke');
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error('Could not retrieve a joke');
                }

                let jokeText = '';
                let jokeCategoryType = data.category || 'General';

                if (data.type === 'single') {
                    jokeText = data.joke;
                    jokeCategoryType = 'Single Line';
                } else if (data.type === 'twopart') {
                    jokeText = `${data.setup} <br><br> ${data.delivery}`;
                    jokeCategoryType = 'Two Part';
                }

                // Display joke
                document.getElementById('jokeType').textContent = jokeCategoryType;
                document.getElementById('jokeText').innerHTML = jokeText;

                loadingSpinner.style.display = 'none';
                jokeContent.style.display = 'block';
                initialContent.style.display = 'none';

                // Add to history
                addToHistory({
                    text: data.type === 'single' ? data.joke : `${data.setup} - ${data.delivery}`,
                    type: jokeCategoryType,
                    timestamp: new Date().toLocaleString()
                });

            } catch (error) {
                loadingSpinner.style.display = 'none';
                errorMessage.style.display = 'block';
                document.getElementById('errorText').textContent = error.message;
                console.error('Error fetching joke:', error);
            }
        }

        function addToHistory(joke) {
            jokeHistory.unshift(joke);
            if (jokeHistory.length > 10) {
                jokeHistory.pop();
            }
            localStorage.setItem('jokeHistory', JSON.stringify(jokeHistory));
            updateHistoryDisplay();
        }

        function updateHistoryDisplay() {
            const historyDiv = document.getElementById('jokeHistory');

            if (jokeHistory.length === 0) {
                historyDiv.innerHTML = '<p class="text-muted text-center py-4">No jokes generated yet</p>';
                return;
            }

            historyDiv.innerHTML = jokeHistory.map((joke, index) => `
                <div class="history-item">
                    <small class="d-block mb-2">${joke.timestamp}</small>
                    <div>${joke.text.substring(0, 100)}${joke.text.length > 100 ? '...' : ''}</div>
                    <small class="d-block mt-2 opacity-75">${joke.type}</small>
                </div>
            `).join('');
        }

        function clearHistory() {
            if (confirm('Are you sure you want to clear all history?')) {
                jokeHistory = [];
                localStorage.removeItem('jokeHistory');
                updateHistoryDisplay();
            }
        }

        // Initialize
        updateHistoryDisplay();
    </script>
</body>
</html>