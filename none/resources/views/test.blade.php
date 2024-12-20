<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a>script</a>
    <script src="https://unpkg.com/centrifuge@5.0.1/dist/centrifuge.js"></script>
    <script>
        const centrifuge = new Centrifuge("ws://" + window.location.host + "/connection/websocket");
          
        centrifuge.on('connect', function(ctx) {
            console.log('Connected to Centrifugo', ctx);
        });

        centrifuge.on('disconnect', function(ctx) {
            console.log('Disconnected from Centrifugo', ctx);
        });
        centrifuge.connect();

        const sub = centrifuge.newSubscription("channel");

        sub.on('publication', function(ctx) {
            container.innerHTML = ctx.data.value;
            document.title = ctx.data.value;
        }).on('subscribing', function(ctx) {
            console.log(`subscribing: ${ctx.code}, ${ctx.reason}`);
        }).on('subscribed', function(ctx) {
            console.log('subscribed', ctx);
        }).on('unsubscribed', function(ctx) {
            console.log(`unsubscribed: ${ctx.code}, ${ctx.reason}`);
        }).subscribe();
    </script>
</body>

</html>