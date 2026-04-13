<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #003973, #E5E5BE);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
        }

        .access-container {
            text-align: center;
            max-width: 700px;
            padding: 20px;
            background-color: rgba(0,0,0,0.5);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .access-container img {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin-bottom: 20px;
        }

        .access-container h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 1px 2px 6px rgba(0,0,0,0.6);
        }

        .access-container h1 span {
            color: #ff4c4c;
        }

        .access-container h2 {
            margin-top: 10px;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .btn-custom {
            background-color: #4547ed;
            border: none;
            font-weight: bold;
            padding: 10px 25px;
            font-size: 1rem;
            border-radius: 50px;
            margin-top: 10px;
            color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }

        @media (max-width: 576px) {
            .access-container h1 {
                font-size: 2rem;
            }

            .access-container h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="access-container">
        <img src="images/error_images/restricted.jpg" alt="Access Restricted">
        <h1>ACCESS <span>RESTRICTED</span>!</h1>
        <h2>SORRY</h2>
        <h2>YOU DO NOT HAVE ACCESS TO THIS PAGE</h2>
        <a href="home" class="btn btn-custom">Go to Home</a>
    </div>
</body>
</html>
