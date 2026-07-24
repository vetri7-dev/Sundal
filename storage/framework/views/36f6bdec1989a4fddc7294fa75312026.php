<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .header img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                margin: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 10px !important;
            }
            .header {
                padding: 15px !important;
            }
        }

        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img alt="<?php echo e(config('app.name')); ?>" height="auto" src="<?php echo e(config('app.url') . getSidebarLogo()); ?><?php echo e('?' . time()); ?>" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;max-height:60px;" title="<?php echo e(config('app.name')); ?>" width="110"/>
        </div>

        <?php if(isset($content)): ?>
            <?php echo $content; ?>

        <?php else: ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php endif; ?>

        <div class="footer">
            <p><?php echo $__env->yieldContent('footer', 'This is an automated email from ' . config('app.name')); ?></p>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\vgopalakrishnan\Desktop\AI\sundal\main-file\resources\views\emails\layout.blade.php ENDPATH**/ ?>