<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flickr Photo Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            width: 250px;
            margin-right: 10px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4285F4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button[type="submit"]:hover {
            background-color: #3367D6;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            max-width: 1000px;
            width: 100%;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .grid-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .grid-item img:hover {
            transform: scale(1.05);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #4285F4;
            color: white;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .pagination a:hover {
            background-color: #3367D6;
        }
    </style>
</head>
<body>

<h2>Flickr Photo Search</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search photos" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="page" value="1">
    <button type="submit">Search</button>
</form>

<div class="grid-container">
    <?php
    $apiKey = "71b1966355ddca4fec1345cac864a220"; 
    $perPage = 16;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $url = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key={$apiKey}&format=json&nojsoncallback=1&per_page={$perPage}&page={$page}&text={$search}";


    $response = file_get_contents($url);

    $data = json_decode($response);
    
    foreach ($data->photos->photo as $photo) {
        $farm = $photo->farm;
        $server = $photo->server;
        $id = $photo->id;
        $secret = $photo->secret;
        $title = $photo->title;
    
        $imageFullUrl = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}_b.jpg";
    
        $imageUrl = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}_q.jpg";
        
        echo "<div class='grid-item'>
    
        <a href='{$imageFullUrl}' target='_blank'>

        <img src='{$imageUrl}' alt='{$title}'></a>
        
        </div>";
    }
    ?>
</div>

<div class="pagination">
    <?php
    $totalPages = $data->photos->pages;
    $prevPage = $page > 1 ? $page - 1 : 1;
    $nextPage = $page < $totalPages ? $page + 1 : $totalPages;

    if ($page > 1) {
        echo "<a href='?search=$search&page=$prevPage'>Previous</a>";
    }

    if ($page < $totalPages) {
        echo "<a href='?search=$search&page=$nextPage'>Next</a>";
    }
    ?>
</div>

</body>
</html>
