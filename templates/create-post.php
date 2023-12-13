<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<main class="container">
    <div class="bg-light p-5 rounded">
        <?php foreach(errors() as $error): ?>
            <?php echo $error . "<br>"; ?>
        <?php endforeach ?>
        <?php if(session()->hasFlash('success')):?>
            <?php dd(session()->getFlash('success')); ?>
        <?php endif; ?>
        <?php if(session()->has('success')): ?>
            <p>Sucesso</p>
        <?php endif; ?>
        <form action="/posts" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input name="title" required type="text" class="form-control" id="title" aria-describedby="titleHelp">
                <div id="titleHelp" class="form-text">Titulo</div>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Body</label>
                <textarea required name="body" id="" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>