<?php require __DIR__ . '/header.php'; ?>
<script src="js/service.js" type="text/javascript"></script>

<div id="wrapper_top"></div>
	<div id="wrapper_mid">
		<div id="content_wrapper" class="service_bg">
			<a href="http://www.lafacebeauty.ca"><div id="logo"></div></a>
			<?php require_once __DIR__ . '/navi.html'; ?>
			<div id="content">
				<div id="content_top_pics">
					<div id="service_pic1"></div>
					<div id="service_pic2"></div>
					<div id="service_pic3"></div>
				</div>

				
                <?php include __DIR__ . '/include/service_facials.php'; ?>
                <?php include __DIR__ . '/include/service_special.php'; ?>
                <?php include __DIR__ . '/include/service_a_la_carte.php'; ?>
                <?php include __DIR__ . '/include/service_state_of_art.php'; ?>
                <?php include __DIR__ . '/include/service_laser_hair.php'; ?>
                <?php include __DIR__ . '/include/service_pigmentation.php'; ?>
                <?php include __DIR__ . '/include/service_electrolysis.php'; ?>
                <?php include __DIR__ . '/include/service_waxing.php'; ?>

        
        <div class="content_box_footer"></div>
			</div>
			
			<div id="event_panel">
				<h2>OUR SERVICES</h2>
				
				<div id="service_list">
				<ul>
					<li class="facial active">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						FACIALS
						</div>
						<div class="service_list_btm">
						</div>
					</li>
					<li class="special_treat">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						SPECIALIZED FACIAL TREATMENTS
						</div>
						<div class="service_list_btm">
						</div>
					</li>
					<li class="alacarte">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						A LA CARTE
						</div>
						<div class="service_list_btm">
						</div>
					</li>
					<li class="stateofart">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						STATE OF THE ART TREATMENTS
						</div>
						<div class="service_list_btm">
						</div>
					</li>
					<li class="laserhair">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						LASER HAIR REMOVAL
						</div>
						<div class="service_list_btm">
						</div>
					</li>
                    <li class="pigmentation">
                        <div class="service_list_top">
                        </div>
                        
                        <div class="service_list_mid">
                            PIGEMENTATION &amp; PORES REDUCTION
                        </div>
                        <div class="service_list_btm">
                        </div>
                    </li>
					<li class="electrolysis">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						ELECTROLYSIS
						</div>
						<div class="service_list_btm">
						</div>
					</li>
					<li class="waxing">
						<div class="service_list_top">
						</div>
						<div class="service_list_mid">
						WAXING
						</div>
						<div class="service_list_btm">
						</div>
					</li>
				</ul>
				</div>
			</div>
		</div>
	</div>
<?php require __DIR__ . '/footer.php'; ?>
