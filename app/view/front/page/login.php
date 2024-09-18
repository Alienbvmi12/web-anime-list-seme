<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php $this->getAdditionalBefore(); ?>
    <?php $this->getAdditional(); ?>
    <?php $this->getAdditionalAfter(); ?>
    <style>
        body{
            /* background-image: url(https://pic.re/image?max_size=3000&mix_size=1500); */
            /* background-image: url(https://picsum.photos/2000/1300); */
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body >
    <?php $this->getJsFooter(); ?>
    <?php $this->getJsReady(); ?>
    <?php $this->getThemeContent(); ?>
    <script>
        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;

        document.body.style.backgroundImage = "url(https://picsum.photos/"+screenWidth+"/"+screenHeight+")"
    </script>
</body>

</html>