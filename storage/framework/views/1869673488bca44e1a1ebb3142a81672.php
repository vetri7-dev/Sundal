<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">
        function closethisasap() { 
            document.forms["redirectpost"].submit(); 
        }
    </script>
</head>
<body onLoad="closethisasap();">
    <form name="redirectpost" method="post" action="<?php echo e($redirectUrl); ?>">
        <input type="hidden" name="_token" value="<?php echo e($token); ?>">
    </form>
</body>
</html><?php /**PATH C:\Users\vgopalakrishnan\Desktop\AI\sundal\main-file\resources\views\aamarpay-redirect.blade.php ENDPATH**/ ?>