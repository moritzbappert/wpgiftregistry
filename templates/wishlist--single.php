<?php

/**
 * Template for output of a single wishlist
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<section>

<?php
    $i = 0;

    foreach ( $wishlist as $gift ):

        $classes = array('wpgr-m_card', 'is-collapsed');

        // needs to be replaced
        $is_bought = false;
        $is_single = true;
        $has_buyer = false;

        if ( $is_bought ) {
            $classes[] = 'wpgr-m_card--buyed';
        }
        if ( $is_single ) {
            $classes[] = 'wpgr-m_card--single';
        }

    ?>
        <div class="<?= implode(' ', $classes) ?>">

        <?php if ( !empty($gift['gift_price']) ): ?>
            <div class="wpgr-m_card__price-wrapper">
                <p class="wpgr-m_card__price">
                    <?= $currency_placement === 'before' ? $currency . $gift['gift_price'] : $gift['gift_price'] . $currency ?>
                </p>
                <?php if ( $is_single ): ?>
                    <p class="wpgr-m_card__price-text">
                    <?php
                        /* translators: (price per) each part of the gift */
                        echo __('each', 'wpgiftregistry');
                    ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

            <div class="wpgr-m_card__main">
                <div class="wpgr-m_card__figure-wrapper">
                    <div class="wpgr-m_card__figure" <?= empty($gift['gift_image']) ?: "style='background-image:url('" . $gift['gift_image'] . "')'" ?>></div>
                </div>
                <div class="wpgr-m_card__content">
                    <?php if ( !empty($gift['gift_title']) ): ?>
                        <h4 class="wpgr-m_card__heading"><?= $gift['gift_title'] ?></h4>
                    <?php endif; ?>
                    <div class="wpgr-m_card__content-details is-hidden">
                        <?php if ( !empty($gift['gift_description']) ): ?>
                            <p class="wpgr-m_card__desc">
                                <?= $gift['gift_description'] ?>
                            </p>
                        <?php endif; ?>
                        <div class="wpgr-m_card__btn-wrapper">
                            <?php if ( !empty($gift['gift_url']) ): ?>
                                <a class="wpgr-m_card__btn wpgr-m_btn" href="<?= transform_to_affiliate_link( $gift['gift_url'] ) ?>"><?= __('View', 'wpgiftregistry') ?></a>
                            <?php endif; ?>
                            <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">    <?= $is_single ? __('Give', 'wpgiftregistry') : __('Give Part', 'wpgiftregistry') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ( !$is_single ): ?>
                <footer class="wpgr-m_card__footer is-hidden">
                    <div class="wpgr-m_card__buyer-wrapper">
                        <i class="wpgr-m_card__buyer"></i>
                    </div>
                    <div class="wpgr-m_card__process">
                        <div class="wpgr-m_card__process-bar"></div>
                        <p class="wpgr-m_card__process-price">
                            <span class="wpgr-m_card__process-price-partial">100€</span>
                            <span class="wpgr-m_card__process-price-total"> / 200€</span>
                        </p>
                        <p class="wpgr-m_card__process-count">
                            <span class="wpgr-m_card__process-count-partial">10</span>
                            <span class="wpgr-m_card__process-count-total"> / 20</span>
                        </p>
                    </div>
                </footer>
            <?php endif; ?>
            <div class="wpgr-m_card__toggle">
                <i class="wpgr-m_card__toggle-icon"></i>
            </div>
        </div>
<?php
    endforeach;
?>

    <!-- <div class="wpgr-m_card">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <footer class="wpgr-m_card__footer is-hidden">
            <div class="wpgr-m_card__buyer-wrapper">
                <i class="wpgr-m_card__buyer"></i>
            </div>
            <div class="wpgr-m_card__process">
                <div class="wpgr-m_card__process-bar"></div>
                <p class="wpgr-m_card__process-price">
                    <span class="wpgr-m_card__process-price-partial">100€</span>
                    <span class="wpgr-m_card__process-price-total"> / 200€</span>
                </p>
                <p class="wpgr-m_card__process-count">
                    <span class="wpgr-m_card__process-count-partial">10</span>
                    <span class="wpgr-m_card__process-count-total"> / 20</span>
                </p>
            </div>
        </footer>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <footer class="wpgr-m_card__footer is-hidden">
            <div class="wpgr-m_card__buyer-wrapper">
                <i class="wpgr-m_card__buyer"></i>
            </div>
            <div class="wpgr-m_card__process">
                <div class="wpgr-m_card__process-bar"></div>
                <p class="wpgr-m_card__process-price">
                    <span class="wpgr-m_card__process-price-partial">100€</span>
                    <span class="wpgr-m_card__process-price-total"> / 200€</span>
                </p>
                <p class="wpgr-m_card__process-count">
                    <span class="wpgr-m_card__process-count-partial">10</span>
                    <span class="wpgr-m_card__process-count-total"> / 20</span>
                </p>
            </div>
        </footer>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single wpgr-m_card--buyed is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">OUT</p>
            <p class="wpgr-m_card__price-text">bought</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>
</section> -->

<div class="wpgr-o_popup wpgr-o_popup--single">
    <form class="wpgr-o_popup__form">

        <div id="wpgr_popup_name" class="wpgr-o_popup__step is-active">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Wie ist Dein Name?</p>
                <p class="wpgr-o_popup__desc">Lasse den Beschenkten wissen, dass das Geschenk von Dir ist.</p>
            </header>
            <input type="text" name="name" value="" placeholder="Dein Name">
            <button class="wpgr-m_btn wpgr-m_btn--next">Next</button>
            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-icon">x</i>
            </button>
            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
            </ul>
        </div>

        <div id="wpgr_popup_parts" class="wpgr-o_popup__step">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Wieviele Teile möchtest Du schenken?</p>
                <p class="wpgr-o_popup__desc">Ein Teil entspricht 50€.</p>
            </header>
            <input type="number" name="" value="">
            <button class="wpgr-m_btn wpgr-m_btn--next">Next</button>
            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-icon">x</i>
            </button>
            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
            </ul>
        </div>

        <div id="wpgr_popup_buyer" class="wpgr-o_popup__step">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Willst du den Kauf übernehmen?</p>
                <p class="wpgr-o_popup__desc">Übernheme die Organisation und hinterlege Kontaktdaten (z.B. Tel/Email), damit die Schenker sich mit Dir absprechen können.</p>
            </header>
            <textarea name="" value="" placeholder="Kontaktdaten z.B. E-mail oder Telefon"></textarea>
            <div class="wpgr-m_btn-wrapper">
                <button class="wpgr-m_btn wpgr-m_btn--next">Nein</button>
                <button class="wpgr-m_btn wpgr-m_btn--next">Ja</button>
            </div>
            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-icon">x</i>
            </button>
            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
            </ul>
        </div>

        <div id="wpgr_popup_email" class="wpgr-o_popup__step">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Wie ist Deine Email?</p>
                <p class="wpgr-o_popup__desc">Wenn du Deine Email hinterlegst, kannst Du deine Reservierung via zugeschicktem Link rückgängig machen.</p>
            </header>
            <input type="text" name="" value="">
            <input class="wpgr-m_btn wpgr-m_btn--next" type="submit" name="save">
            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-icon">x</i>
            </button>
            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-03"></li>
            </ul>
        </div>
    </form>
</div>
