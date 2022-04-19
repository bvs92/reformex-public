@if(session('error'))

    <?php toast(session('error') ,'error'); ?>

@elseif(session('success'))

    <?php toast(session('success') ,'success'); ?>

@elseif(session('info'))

    <?php toast(session('info') ,'info'); ?>

@endif


