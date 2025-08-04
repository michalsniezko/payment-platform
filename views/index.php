<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="with=device-width, user-scalable=no, initial_scale=1.0, maximum_scale=1.0, minimum_scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
Home Page
<hr/>
<div>
    <?php if (!empty($invoice)): ?>
        <h1>Uploaded invoice:</h1>
        Invoice ID: <?= htmlspecialchars($invoice['id'], ENT_NOQUOTES) ?> <br/>
        Invoice Amount: <?= htmlspecialchars($invoice['amount'], ENT_NOQUOTES) ?> <br/>
        User: <?= htmlspecialchars($invoice['full_name'], ENT_NOQUOTES) ?> <br/>
    <?php endif ?>
</div>
<div>
    <a href="/invoices">Invoices</a>
    <a href="/users/create">Create user</a>
</div>
</body>

</html>
