<!-- ROW-4 -->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="card overflow-hidden">
            <div class="card-body pb-0">
                <div class="">
                    <div class="d-flex">
                        <h6 class="mb-3">Total tichete</h6>
                        <div class="ml-auto">
                            <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                            <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <h3 class="number-font mb-1">{{ $tickets->count() }}</h3>
                    <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>+24%</span></span><span class="text-muted ml-2">From Last Month</span>
                    <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="widgetChart1" class="chart-dropshadow-info"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="card overflow-hidden">
            <div class="card-body pb-0">
                <div class="">
                    <div class="d-flex">
                        <h6 class="mb-3">Utilizatori Firme</h6>
                        <div class="ml-auto">
                            <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                            <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <h3 class="number-font mb-1">##</h3>
                    <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>90.5%</span></span><span class="text-muted ml-2">From Last Month</span>
                    <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="widgetChart2" class="chart-dropshadow-secondary"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="card overflow-hidden">
            <div class="card-body pb-0">
                <div class="">
                    <div class="d-flex">
                        <h6 class="mb-3">Ultimele 7 zile</h6>
                        <div class="ml-auto">
                            <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                            <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <h3 class="number-font mb-1">8963</h3>
                    <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>20.8%</span></span><span class="text-muted ml-2">From Last Month</span>
                    <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="widgetChart3" class="chart-dropshadow-success"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- ROW-4 END -->