<?php

/**
 * Template for output of a single wishlist
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="m_popup">
	<div class="m_popup__step is-active" data-step="1">
		<ul>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
		</ul>
		<p class="m_popup__content">Step 1</p>
		<button class="m_btn m_btn--next">Next</button>
		<button class="m_btn m_btn--close">x</button>
	</div>
	<div class="m_popup__step" data-step="2">
		<ul>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
		</ul>
		<p class="m_popup__content">Step 2</p>
		<button class="m_btn m_btn--prev">Back</button>
		<button class="m_btn m_btn--next">Next</button>
		<button class="m_btn m_btn--close">x</button>
	</div>
	<div class="m_popup__step" data-step="3">
		<ul>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
			<li class="m_popup__list-item"></li>
		</ul>
		<p class="m_popup__content">Step 3</p>
		<button class="m_btn m_btn--prev">Back</button>
		<button class="m_btn m_btn--save">Save</button>
		<button class="m_btn m_btn--close">x</button>
	</div>
</div>