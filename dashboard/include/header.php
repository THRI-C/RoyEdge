<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | RoyEdge Enterprise</title>

    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="../asset/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/remixicon.css">
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .profile-image-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-image-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            margin-bottom: 15px;
            box-shadow: 3px 5px 10px #e1e1e1;
        }

        .default-avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #ddd;
            margin: 0 auto 15px;
            font-size: 48px;
            color: #6c757d;
            box-shadow: 3px 5px 10px #e1e1e1;
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            color: #495057;
            border-bottom: 2px solid #007cba;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .message.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .required {
            color: #dc3545;
        }
    </style>
    <script src="https://kit.fontawesome.com/4534c556c8.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="dashboard-container position-relative">