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
        .dt-length>select {
            min-width: 65px;
        }

        label,
        p,
        .dt-info {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <?php $this->getJsFooter(); ?>
    <?php $this->getJsReady(); ?>
    <?php $this->getThemeElement("page/html/sidebar", $__forward); ?>
    <main class="content">
        <?php $this->getThemeElement("page/html/header", $__forward); ?>
        <div class="container py-4">
            <?= count($this->breadcrumb->contents) < 1  ? '' : $this->breadcrumb->html() ?>
            <div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
                <div class="card-body overflow-auto">
                    <?php $this->getThemeContent(); ?>
                </div>
            </div>
        </div>
        <?php $this->getThemeElement("page/html/footer", $__forward); ?>
    </main>


</body>

</html>