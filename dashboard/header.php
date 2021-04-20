<!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#page-top">SITR2</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                     <li class="page-scroll">
                        <a href="<?php echo '#home'?>">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="<?php echo '#jadwal'?>">Jadwal Terbaru</a>
                    </li>
                    <li class="page-scroll">
                        <a href="<?php echo '#about'?>">About</a>
                    </li>
                    <li class="page-scroll <?php if(isset($_SESSION['sip_masuk_aja'])) echo 'signed'; ?>">
						<?php 
                            
                            if(isset($_SESSION['sip_masuk_aja'])){
                                if($status=='petugas'){
                                    echo'<a href="admin/index.php">'.$rAdmin->nama.'</a>';
                                }elseif($status=='mahasiswa'){
                                    echo'<a href="mahasiswa/index.php">'.$rMahasiswa->nama.'</a>';
                                }elseif($status=='dosen'){
                                    echo'<a href="dosen/index.php">'.$rDosen->nama_dosen.'</a>';
                                }elseif($status=='laboratorium'){
                                    echo'<a href="laboratorium/index.php">'.$rLaboratorium->nama_lab.'</a>';
                                }
                            }else
                                echo '<a href="login/login.php">LOGIN</a>';
                            
                        ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>