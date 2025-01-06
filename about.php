

<div class="row justify-content-center" style="width: 100%;">
        <div class="col-md-7">
            <div class="card rounded-0 card-outline card-navy shadow" style="border-color: #800000;">
                <div class="card-body rounded-0">
                    <h2 class="text-center">About</h2>
                    <hr>
                    
                    <div>
                        <?= file_get_contents("about_us.html") ?>
                    </div>
            <div class="card card-outline card-dark rounded-0 shadow" >
                <div class="card-header">
                    <h4 class="text-center">Contact</h4>
                </div>
                    <div class="card-body rounded-0 text-center">
                            <dl>
                                <dt class="text-muted"> Email</dt>
                                <dd class="text-dark"><?= $_settings->info('email') ?></dd>
                                <dt class="text-muted"> Contact Number</dt>
                                <dd class="text-dark"><?= $_settings->info('contact') ?></dd>
                                <dt class="text-muted"> Location</dt>
                                <dd class="text-dark"><?= $_settings->info('address') ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>   
            </div>
            </div>
        