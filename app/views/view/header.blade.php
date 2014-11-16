<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bonus</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-dark.css">
    <link rel="stylesheet" href="/assets/css/base.css">
</head>
<body>
@if (isset($errorMessage) && $errorMessage !== '')
<div class="alert alert-danger message" role="alert" data-auto-close="5000">{{{$errorMessage}}}</div>
@endif
@if (isset($successMessage) && $successMessage !== '')
<div class="alert alert-success message" role="alert" data-auto-close="5000">{{{$successMessage}}}</div>
@endif

<div class="alert alert-danger message display_none" role="alert"></div>
<div class="alert alert-success message display_none" role="alert"></div>
