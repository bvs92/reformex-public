<!-- SIDE-BAR -->
<div class="sidebar sidebar-right sidebar-animate">

    <div class="p-4 border-bottom">
            <span class="fs-17">Notificări</span>
         <a href="#" class="sidebar-icon text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x"></i></a>
     </div>

     <a href="{{ route('notifications.all.vue') }}" class="btn btn-sm btn-default m-4 d-flex justify-content-center">Vezi tot</a>


    <sidebar-notifications-component></sidebar-notifications-component>

 </div>
<!-- SIDE-BAR CLOSED -->
