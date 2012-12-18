<? for ($i = 1; $i <= $slidesCount; $i++): ?>
	<div class="module-slider-box">
		<h3 class="alternative"><?= $i?>.</h3>
		<div class="module-slider-right-box">
			<div class="module-slider-input-box">
				<input type="button" class="vet-show" value="<?= $wf->msg('marketing-toolbox-edithub-add-file-button')?>" />
				<span class="alternative filename-placeholder">File name</span>
				<?=$app->renderView(
					'MarketingToolbox',
					'FormField',
					array('inputData' => $fields['photo' . $i])
				);
				?>
				<?=$app->renderView(
					'MarketingToolbox',
					'FormField',
					array('inputData' => $fields['shortDesc' . $i])
				);
				?>
				<?=$app->renderView(
					'MarketingToolbox',
					'FormField',
					array('inputData' => $fields['longDesc' . $i])
				);
				?>
				<?=$app->renderView(
					'MarketingToolbox',
					'FormField',
					array('inputData' => $fields['url' . $i])
				);
				?>


				<input class="secondary" type="button" value="<?= $wf->msg('marketing-toolbox-edithub-clear-button')?>" />
			</div>
			<div class="module-slider-image-box">
				<div class="image-placeholder"></div>
			</div>

			<button class="secondary navigation nav-up"><img class="chevron chevron-up" src="<?= $wg->BlankImgUrl; ?>"></button>
			<button class="secondary navigation nav-down"><img class="chevron chevron-down" src="<?= $wg->BlankImgUrl; ?>"></span></button>
		</div>
	</div>
<? endfor ?>