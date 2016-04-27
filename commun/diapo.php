<div class="flex-container">
					<div id = "test"class="flexslider">
						<ul class="slides">
							<?php
								for ($i = 1; $i <= $nbPhoto; $i++) {
									if ($i == 1) {
										echo "<li><a href=\"#\"><img src=\"Images/".$critique."/".$i.".jpg\" /></a></li>\n";
									}
									else {
										echo "\t\t\t\t\t\t\t<li><img src=\"Images/".$critique."/".$i.".jpg\" /></li>\n";
									}
								}
							?>
						</ul>
					</div>
				</div>
				<div class="flex-containermini">
					<div class="flexslidermini">
						<ul class="slides">
							<?php
								for ($i = 1; $i <= $nbPhoto; $i++) {
									if ($i == 1) {
										echo "<li><a href=\"#\"><img src=\"Images/".$critique."/".$i.".jpg\" /></a></li>\n";
									}
									else {
										echo "\t\t\t\t\t\t\t<li><img src=\"Images/".$critique."/".$i.".jpg\" /></li>\n";
									}
								}
							?>
						</ul>
					</div>
				</div>
