<?php $__env->startSection('title', $subject); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $content; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vgopalakrishnan\Desktop\AI\sundal\main-file\resources\views\emails\notification.blade.php ENDPATH**/ ?>